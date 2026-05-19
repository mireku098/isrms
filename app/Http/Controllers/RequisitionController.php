<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\Item;
use App\Models\Sra;
use Illuminate\Http\Request;

class RequisitionController extends Controller
{
    /**
     * Display a listing of requisitions.
     */
    public function index(Request $request)
    {
        $this->authorize('accessRequisitions');
        
        $user = auth()->user();
        $search = trim((string) $request->get('search', ''));
        $status = trim((string) $request->get('status', ''));
        
        $query = Requisition::with('requester', 'approver');
        
        // Filter based on user role
        if ($user->hasRole('requester')) {
            $query->where('requested_by', $user->id);
        }

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('id', $search)
                    ->orWhere('requisition_id', 'LIKE', "%{$search}%")
                    ->orWhereHas('requester', function ($requesterQuery) use ($search) {
                        $requesterQuery->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        if (in_array($status, ['pending', 'approved', 'rejected', 'issued'], true)) {
            if ($status === 'issued') {
                $query->whereHas('issues');
            } else {
                $query->where('status', $status);
            }
        }
        
        $requisitions = $query->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();
        
        return view('requisitions.index', compact('requisitions'));
    }

    /**
     * Show the form for creating a new requisition.
     */
    public function create()
    {
        $this->authorize('isRequester');
        
        $items = Item::select('id', 'name', 'category', 'unit')
            ->orderBy('name')
            ->get();
        
        return view('requisitions.create', compact('items'));
    }

    /**
     * Store a newly created requisition in database.
     */
    public function store(Request $request)
    {
        $this->authorize('isRequester');
        
        $validated = $request->validate([
            'department' => 'required|string|max:100',
            'request_date' => 'required|date',
            'purpose' => 'required|string',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity_requested' => 'required|integer|min:1',
        ]);

        $requisition = Requisition::create([
            'requested_by' => auth()->id(),
            'department' => $validated['department'],
            'request_date' => $validated['request_date'],
            'purpose' => $validated['purpose'],
            'status' => 'pending',
        ]);

        // Generate requisition_id based on the ID
        $requisition->requisition_id = 'REQ-' . str_pad($requisition->id, 5, '0', STR_PAD_LEFT);
        $requisition->save();

        // Attach items
        foreach ($validated['items'] as $item) {
            $requisition->requisitionItems()->create([
                'item_id' => $item['item_id'],
                'quantity_requested' => $item['quantity_requested'],
            ]);
        }

        // Log to audit trail
        $this->logAudit('CREATE', 'requisitions', $requisition->id);

        return redirect()->route('requisitions.show', $requisition)
            ->with('success', 'Requisition created successfully.');
    }

    /**
     * Display the specified requisition.
     */
    public function show(Requisition $requisition)
    {
        $this->authorize('accessRequisitions');
        
        $requisition->load('requester', 'approver', 'requisitionItems.item', 'issues');
        return view('requisitions.show', compact('requisition'));
    }

    /**
     * Show the form for editing the specified requisition.
     */
    public function edit(Requisition $requisition)
    {
        $this->authorize('isRequester');
        
        if ($requisition->status !== 'pending') {
            return back()->with('error', 'Cannot edit approved/rejected requisition.');
        }

        $requisition->load('requisitionItems');
        $items = Item::orderBy('name')->get();
        
        return view('requisitions.edit', compact('requisition', 'items'));
    }

    /**
     * Update the specified requisition in database.
     */
    public function update(Request $request, Requisition $requisition)
    {
        $this->authorize('isRequester');
        
        if ($requisition->status !== 'pending') {
            return back()->with('error', 'Cannot edit approved/rejected requisition.');
        }

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity_requested' => 'required|integer|min:1',
        ]);

        // Remove old items
        $requisition->requisitionItems()->delete();

        // Add new items
        foreach ($validated['items'] as $item) {
            $requisition->requisitionItems()->create([
                'item_id' => $item['item_id'],
                'quantity_requested' => $item['quantity_requested'],
            ]);
        }

        // Log to audit trail
        $this->logAudit('UPDATE', 'requisitions', $requisition->id);

        return redirect()->route('requisitions.show', $requisition)
            ->with('success', 'Requisition updated successfully.');
    }

    /**
     * Remove the specified requisition from database.
     */
    public function destroy(Requisition $requisition)
    {
        $this->authorize('isRequester');
        
        if ($requisition->status !== 'pending') {
            return back()->with('error', 'Cannot delete approved/rejected requisition.');
        }

        $requisition->delete();

        // Log to audit trail
        $this->logAudit('DELETE', 'requisitions', $requisition->id);

        return redirect()->route('requisitions.index')
            ->with('success', 'Requisition deleted successfully.');
    }

    /**
     * Approve requisition
     */
    public function approve(Request $request, Requisition $requisition)
    {
        $this->authorize('isPrincipal');
        
        $requisition->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        // Log to audit trail
        $this->logAudit('APPROVE', 'requisitions', $requisition->id);

        return back()->with('success', 'Requisition approved successfully.');
    }

    /**
     * Reject requisition
     */
    public function reject(Request $request, Requisition $requisition)
    {
        $this->authorize('isPrincipal');
        
        $requisition->update([
            'status' => 'rejected',
        ]);

        // Log to audit trail
        $this->logAudit('REJECT', 'requisitions', $requisition->id);

        return back()->with('success', 'Requisition rejected successfully.');
    }

    /**
     * Log action to audit trail
     */
    private function logAudit($action, $table, $recordId)
    {
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'table_name' => $table,
            'record_id' => $recordId,
        ]);
    }

    /**
     * API: Get all items with real-time stock data
     */
    public function apiItems()
    {
        $items = Item::with('categoryRelation')
            ->orderBy('name')
            ->get()
            ->map(function ($item) {
                $lastLedger = \App\Models\InventoryLedger::where('item_id', $item->id)
                    ->latest()
                    ->first();
                
                $currentStock = $lastLedger ? $lastLedger->balance_after : 0;
                $stockStatus = 'in_stock';
                
                if ($currentStock == 0) {
                    $stockStatus = 'out_of_stock';
                } elseif ($currentStock < ($item->reorder_level ?? 10)) {
                    $stockStatus = 'low_stock';
                }

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => $item->code,
                    'category' => $item->categoryRelation->name ?? $item->category ?? 'Uncategorized',
                    'category_id' => $item->category_id,
                    'unit' => $item->unit ?? 'Pieces',
                    'current_stock' => $currentStock,
                    'reorder_level' => $item->reorder_level ?? 0,
                    'stock_status' => $stockStatus,
                    'last_updated' => $lastLedger ? $lastLedger->created_at->diffForHumans() : 'Never',
                ];
            });

        return response()->json($items);
    }

    /**
     * API: Get single item stock data
     */
    public function apiItemStock($itemId)
    {
        $item = Item::findOrFail($itemId);
        
        $lastLedger = \App\Models\InventoryLedger::where('item_id', $itemId)
            ->latest()
            ->first();
        
        $currentStock = $lastLedger ? $lastLedger->balance_after : 0;
        $stockStatus = 'in_stock';
        
        if ($currentStock == 0) {
            $stockStatus = 'out_of_stock';
        } elseif ($currentStock < ($item->reorder_level ?? 10)) {
            $stockStatus = 'low_stock';
        }

        return response()->json([
            'item_id' => $item->id,
            'name' => $item->name,
            'code' => $item->code,
            'category' => $item->categoryRelation->name ?? $item->category ?? 'Uncategorized',
            'unit' => $item->unit ?? 'Pieces',
            'current_stock' => $currentStock,
            'reorder_level' => $item->reorder_level ?? 0,
            'stock' => $currentStock,
            'min_stock' => $item->reorder_level ?? 10,
            'status' => $stockStatus,
            'last_updated' => $lastLedger ? $lastLedger->created_at->diffForHumans() : 'Never',
        ]);
    }

    /**
     * API: Get requester's dashboard data
     */
    public function apiDashboard()
    {
        try {
            $user = auth()->user();
            
            if ($user->hasRole('principal')) {
                $today = now()->startOfDay();
                $thisWeek = now()->startOfWeek();
                $thisMonth = now()->startOfMonth();

                $pendingRequisitions = Requisition::where('status', 'pending')
                    ->with('requester', 'requisitionItems')
                    ->latest()
                    ->take(5)
                    ->get();

                $pendingSras = Sra::where('status', 'pending')
                    ->where('signed_storekeeper', true)
                    ->where('signed_auditor', true)
                    ->where('signed_principal', false)
                    ->with('sraItems')
                    ->latest()
                    ->take(5)
                    ->get();

                $recentRequisitions = Requisition::where('status', '!=', 'pending')
                    ->with('requester', 'requisitionItems')
                    ->latest()
                    ->take(5)
                    ->get();

                $stats = [
                    'pending_requisitions' => Requisition::where('status', 'pending')->count(),
                    'pending_sras' => Sra::where('status', 'pending')
                        ->where('signed_storekeeper', true)
                        ->where('signed_auditor', true)
                        ->where('signed_principal', false)
                        ->count(),
                    'total_requisitions' => Requisition::count(),
                    'approvals_today' => Requisition::where('status', 'approved')
                        ->where('updated_at', '>=', $today)
                        ->count() + 
                        Sra::where('signed_principal', true)
                        ->where('updated_at', '>=', $today)
                        ->count(),
                    'approvals_this_week' => Requisition::where('status', 'approved')
                        ->where('updated_at', '>=', $thisWeek)
                        ->count() + 
                        Sra::where('signed_principal', true)
                        ->where('updated_at', '>=', $thisWeek)
                        ->count(),
                    'approvals_this_month' => Requisition::where('status', 'approved')
                        ->where('updated_at', '>=', $thisMonth)
                        ->count() + 
                        Sra::where('signed_principal', true)
                        ->where('updated_at', '>=', $thisMonth)
                        ->count(),
                ];

                $totalReqs = Requisition::count();
                $approvedReqs = Requisition::where('status', 'approved')->count();
                $stats['approval_rate'] = $totalReqs > 0 ? round(($approvedReqs / $totalReqs) * 100, 1) : 0;

                return response()->json([
                    'stats' => $stats,
                    'pending_requisitions' => $pendingRequisitions,
                    'pending_sras' => $pendingSras,
                    'recent_requisitions' => $recentRequisitions,
                    'timestamp' => now()->toIso8601String(),
                ]);
            }

            if ($user->hasRole('auditor')) {
                $thisMonth = now()->startOfMonth();
                $recentSras = Sra::with('createdBy')->latest()->take(5)->get();
                $recentRequisitions = Requisition::where('status', '!=', 'pending')
                    ->with('requester', 'approver', 'requisitionItems')
                    ->latest()
                    ->take(5)
                    ->get();

                $stats = [
                    'pending_verifications' => Sra::where('status', 'pending')
                        ->where('signed_storekeeper', true)
                        ->where('signed_auditor', false)
                        ->count(),
                    'total_sras' => Sra::count(),
                    'verified_this_month' => Sra::where('signed_auditor', true)
                        ->where('updated_at', '>=', $thisMonth)
                        ->count(),
                ];

                return response()->json([
                    'stats' => $stats,
                    'recent_sras' => $recentSras,
                    'recent_requisitions' => $recentRequisitions,
                    'timestamp' => now()->toIso8601String(),
                ]);
            }

            $stats = [
                'pending_requisitions' => Requisition::where('requested_by', $user->id)
                    ->where('status', 'pending')
                    ->count(),
                'approved_requisitions' => Requisition::where('requested_by', $user->id)
                    ->where('status', 'approved')
                    ->count(),
                'issued_requisitions' => Requisition::where('requested_by', $user->id)
                    ->whereHas('issues')
                    ->count(),
                'rejected_requisitions' => Requisition::where('requested_by', $user->id)
                    ->where('status', 'rejected')
                    ->count(),
            ];

            $recentRequisitions = Requisition::where('requested_by', $user->id)
                ->with('requisitionItems.item')
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($req) {
                    return [
                        'id' => $req->id,
                        'requisition_number' => $req->requisition_number,
                        'department' => $req->department ?? 'N/A',
                        'status' => $req->status,
                        'items_count' => $req->requisitionItems ? $req->requisitionItems->count() : 0,
                        'created_at' => $req->created_at ? $req->created_at->format('M d, Y') : 'N/A',
                        'created_at_raw' => $req->created_at ? $req->created_at->toIso8601String() : null,
                    ];
                });

            return response()->json([
                'stats' => $stats,
                'recent_requisitions' => $recentRequisitions,
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API Dashboard Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Get requester's requisitions with filters
     */
    public function apiMyRequisitions(Request $request)
    {
        try {
            $user = auth()->user();
            $status = $request->get('status', '');
            
            $query = Requisition::where('requested_by', $user->id)
                ->with('requisitionItems.item');
            
            if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
                $query->where('status', $status);
            }

            $requisitions = $query->orderByDesc('created_at')
                ->get()
                ->map(function ($req) {
                    return [
                        'id' => $req->id,
                        'requisition_number' => $req->requisition_number,
                        'department' => $req->department ?? 'N/A',
                        'status' => $req->status,
                        'purpose' => $req->purpose ?? '',
                        'items_count' => $req->requisitionItems ? $req->requisitionItems->count() : 0,
                        'created_at' => $req->created_at ? $req->created_at->format('M d, Y') : 'N/A',
                        'total_items' => $req->requisitionItems ? $req->requisitionItems->sum('quantity_requested') : 0,
                    ];
                });

            return response()->json($requisitions);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API My Requisitions Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Get requester's requisitions list for dashboard
     */
    public function apiMyList()
    {
        try {
            $user = auth()->user();
            
            $requisitions = Requisition::where('requested_by', $user->id)
                ->with('requisitionItems.item')
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($req) {
                    return [
                        'id' => $req->id,
                        'requisition_number' => $req->requisition_number,
                        'department' => $req->department ?? 'N/A',
                        'status' => $req->status,
                        'purpose' => $req->purpose ?? '',
                        'items_count' => $req->requisitionItems ? $req->requisitionItems->count() : 0,
                        'created_at' => $req->created_at ? $req->created_at->format('M d, Y') : 'N/A',
                        'total_items' => $req->requisitionItems ? $req->requisitionItems->sum('quantity_requested') : 0,
                    ];
                });

            return response()->json($requisitions);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API My List Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

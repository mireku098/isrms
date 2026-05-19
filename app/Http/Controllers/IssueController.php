<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Requisition;
use App\Models\Item;
use App\Models\InventoryLedger;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Display a listing of issues.
     */
    public function index()
    {
        $this->authorize('accessIssues');
        
        $issues = Issue::with('requisition.requester', 'issuedBy', 'receivedBy')
            ->orderByDesc('created_at')
            ->paginate(20);
        
        // Calculate stats
        $allIssues = Issue::all();
        $stats = [
            'total_issued' => $allIssues->count(),
            'pending_received' => $allIssues->whereNull('received_by')->count(),
            'received_by_dept' => $allIssues->whereNotNull('received_by')->count(),
        ];
        
        return view('issues.index', compact('issues', 'stats'));
    }

    /**
     * Show the form for creating a new issue.
     */
    public function create()
    {
        $this->authorize('isStorekeeper');
        
        // Fetch approved requisitions with their items and real-time inventory data
        $requisitions = Requisition::where('status', 'approved')
            ->whereDoesntHave('issues')
            ->with('requisitionItems.item', 'requester', 'approvedBy')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($req) {
                return [
                    'id' => $req->id,
                    'requisition_number' => $req->requisition_number,
                    'department' => $req->department,
                    'requester' => $req->requester->name ?? 'Unknown',
                    'approved_by' => $req->approvedBy->name ?? 'N/A',
                    'approval_date' => $req->approved_at ? $req->approved_at->format('M d, Y') : null,
                    'items_count' => $req->requisitionItems->count(),
                    'created_at' => $req->created_at->format('M d, Y'),
                ];
            });

        return view('issues.create', compact('requisitions'));
    }

    /**
     * Store a newly created issue in database.
     */
    public function store(Request $request)
    {
        $this->authorize('isStorekeeper');

        $validated = $request->validate([
            'requisition_id' => 'required|exists:requisitions,id',
            'receiver_name' => 'nullable|string|max:255',
            'receiver_signature' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity_issued' => 'required|integer|min:1',
        ]);

        $requisition = Requisition::find($validated['requisition_id']);

        // Filter items to only include those with quantity > 0
        $itemsToIssue = array_filter($validated['items'], function($item) {
            return $item['quantity_issued'] > 0;
        });

        if (empty($itemsToIssue)) {
            return back()->with('error', 'Please specify at least one item to issue.');
        }

        $issue = Issue::create([
            'requisition_id' => $requisition->id,
            'issued_by' => auth()->id(),
            'receiver_name' => $validated['receiver_name'] ?? null,
            'receiver_signature' => $validated['receiver_signature'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
        ]);

        // Attach items and update inventory
        foreach ($itemsToIssue as $item) {
            $issue->issueItems()->create([
                'item_id' => $item['item_id'],
                'quantity_issued' => $item['quantity_issued'],
            ]);

            // Record ledger entry
            $this->recordLedgerEntry(
                $item['item_id'],
                'ISSUE',
                $item['quantity_issued'],
                'ISSUE',
                $issue->id
            );
        }

        // Log to audit trail
        $this->logAudit('CREATE', 'issues', $issue->id);

        return redirect()->route('issues.show', $issue)
            ->with('success', 'Items issued successfully to ' . $requisition->department . '.');
    }

    /**
     * Display the specified issue.
     */
    public function show(Issue $issue)
    {
        $this->authorize('accessIssues');
        
        $issue->load('requisition.requester', 'issuedBy', 'receivedBy', 'issueItems.item');
        return view('issues.show', compact('issue'));
    }

    /**
     * Show the form for editing the specified issue (before received).
     */
    public function edit(Issue $issue)
    {
        $this->authorize('isStorekeeper');
        
        if ($issue->receivedBy) {
            return back()->with('error', 'Cannot edit received issue.');
        }

        $issue->load('issueItems');
        return view('issues.edit', compact('issue'));
    }

    /**
     * Update the specified issue in database.
     */
    public function update(Request $request, Issue $issue)
    {
        $this->authorize('isStorekeeper');
        
        if ($issue->receivedBy) {
            return back()->with('error', 'Cannot edit received issue.');
        }

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity_issued' => 'required|integer|min:1',
        ]);

        // Remove old items and ledger entries
        foreach ($issue->issueItems as $item) {
            InventoryLedger::where('reference_type', 'ISSUE')
                ->where('reference_id', $issue->id)
                ->where('item_id', $item->item_id)
                ->delete();
        }
        $issue->issueItems()->delete();

        // Add new items
        foreach ($validated['items'] as $item) {
            $issue->issueItems()->create([
                'item_id' => $item['item_id'],
                'quantity_issued' => $item['quantity_issued'],
            ]);

            // Record ledger entry
            $this->recordLedgerEntry(
                $item['item_id'],
                'ISSUE',
                $item['quantity_issued'],
                'ISSUE',
                $issue->id
            );
        }

        // Log to audit trail
        $this->logAudit('UPDATE', 'issues', $issue->id);

        return redirect()->route('issues.show', $issue)
            ->with('success', 'Issue updated successfully.');
    }

    /**
     * Remove the specified issue from database.
     */
    public function destroy(Issue $issue)
    {
        $this->authorize('isStorekeeper');
        
        if ($issue->receivedBy) {
            return back()->with('error', 'Cannot delete received issue.');
        }

        $issue->delete();

        // Log to audit trail
        $this->logAudit('DELETE', 'issues', $issue->id);

        return redirect()->route('issues.index')
            ->with('success', 'Issue deleted successfully.');
    }

    /**
     * Mark issue as received
     */
    public function receive(Request $request, Issue $issue)
    {
        $this->authorize('isRequester');

        if ($issue->receivedBy) {
            return back()->with('error', 'Issue already received.');
        }

        $validated = $request->validate([
            'comments' => 'nullable|string',
        ]);

        $issue->update([
            'received_by' => auth()->id(),
            'received_at' => now(),
            'comments' => $validated['comments'] ?? null,
        ]);

        // Log to audit trail
        $this->logAudit('RECEIVED', 'issues', $issue->id);

        return back()->with('success', 'Issue marked as received.');
    }

    /**
     * Record inventory ledger entry
     */
    private function recordLedgerEntry($itemId, $type, $quantity, $refType, $refId)
    {
        $lastEntry = InventoryLedger::where('item_id', $itemId)->latest()->first();
        $balanceBefore = $lastEntry ? $lastEntry->balance_after : 0;
        $balanceAfter = $type === 'RECEIVE' ? $balanceBefore + $quantity : $balanceBefore - $quantity;

        InventoryLedger::create([
            'item_id' => $itemId,
            'transaction_type' => $type,
            'quantity' => $quantity,
            'balance_after' => $balanceAfter,
            'reference_type' => $refType,
            'reference_id' => $refId,
        ]);
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
     * Get requisition details for real-time display
     */
    public function getRequisitionDetails($requisitionId)
    {
        $this->authorize('isStorekeeper');

        $requisition = Requisition::findOrFail($requisitionId);
        $requisition->load('requester', 'approvedBy');

        return response()->json([
            'id' => $requisition->id,
            'requisition_number' => $requisition->requisition_id ?? ('REQ-' . str_pad($requisition->id, 5, '0', STR_PAD_LEFT)),
            'department' => $requisition->department,
            'requested_by' => $requisition->requester->name ?? 'Unknown',
            'approval_date' => $requisition->approved_at ? $requisition->approved_at->format('M d, Y') : null,
            'approval_by' => $requisition->approvedBy->name ?? 'N/A',
            'status' => $requisition->status,
            'priority' => $requisition->priority ?? 'Normal',
            'request_date' => $requisition->created_at->format('M d, Y'),
        ]);
    }

    /**
     * Get requisition items with real-time inventory data
     */
    public function getRequisitionItems($requisitionId)
    {
        $this->authorize('isStorekeeper');

        $requisition = Requisition::findOrFail($requisitionId);
        $requisition->load('requisitionItems.item');

        $items = $requisition->requisitionItems->map(function ($reqItem) {
            $item = $reqItem->item;
            $lastLedger = InventoryLedger::where('item_id', $item->id)
                ->latest()
                ->first();
            
            $currentStock = $lastLedger ? $lastLedger->balance_after : 0;
            $stockStatus = $currentStock >= $reqItem->quantity_requested ? 'in_stock' : 'low_stock';

            return [
                'id' => $reqItem->id,
                'item_id' => $item->id,
                'item_name' => $item->name,
                'item_code' => $item->code,
                'requested_quantity' => $reqItem->quantity_requested,
                'current_stock' => $currentStock,
                'unit' => $item->unit ?? 'Pieces',
                'stock_status' => $stockStatus,
                'reorder_level' => $item->reorder_level ?? 0,
                'can_issue' => $currentStock >= $reqItem->quantity_requested,
            ];
        });

        return response()->json($items);
    }

    /**
     * Get real-time item inventory
     */
    public function getItemInventory($itemId)
    {
        $this->authorize('isStorekeeper');

        $item = \App\Models\Item::findOrFail($itemId);
        $lastLedger = InventoryLedger::where('item_id', $itemId)
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
            'item_name' => $item->name,
            'item_code' => $item->code,
            'current_stock' => $currentStock,
            'reorder_level' => $item->reorder_level ?? 0,
            'unit' => $item->unit ?? 'Pieces',
            'status' => $stockStatus,
            'last_updated' => $lastLedger ? $lastLedger->created_at->diffForHumans() : 'Never',
        ]);
    }

    /**
     * Get issues pending receipt for current requester (real-time API)
     */
    public function apiPendingIssues()
    {
        $this->authorize('isRequester');

        $issues = Issue::where('received_by', null)
            ->with(['requisition.requester', 'issuedBy', 'issueItems.item'])
            ->whereHas('requisition', function($q) {
                $q->where('requested_by', auth()->id());
            })
            ->orderByDesc('created_at')
            ->get()
            ->map(function($issue) {
                return [
                    'id' => $issue->id,
                    'issue_number' => 'ISSUE-' . str_pad($issue->id, 5, '0', STR_PAD_LEFT),
                    'requisition_number' => $issue->requisition->requisition_number ?? ('REQ-' . str_pad($issue->requisition_id, 5, '0', STR_PAD_LEFT)),
                    'department' => $issue->requisition->department ?? 'N/A',
                    'issued_by' => $issue->issuedBy->name ?? 'Unknown',
                    'created_at' => $issue->created_at->format('M d, Y'),
                    'items_count' => $issue->issueItems->count(),
                    'status' => 'pending_receipt',
                ];
            });

        return response()->json([
            'pending_count' => $issues->count(),
            'pending_issues' => $issues,
        ]);
    }
}

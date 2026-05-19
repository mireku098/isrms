<?php

namespace App\Http\Controllers;

use App\Models\Sra;
use App\Models\Item;
use Illuminate\Http\Request;

class SraController extends Controller
{
    /**
     * Display a listing of SRAs.
     */
    public function index(Request $request)
    {
        $this->authorize('accessSra');

        $search = trim((string) $request->get('search', ''));
        $status = trim((string) $request->get('status', ''));

        $query = Sra::with('createdBy');

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('sra_number', 'LIKE', "%{$search}%")
                    ->orWhere('supplier_details', 'LIKE', "%{$search}%");
            });
        }

        if (in_array($status, ['pending', 'approved'], true)) {
            $query->where('status', $status);
        }

        $sras = $query->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();
        
        return view('sra.index', compact('sras'));
    }

    /**
     * Show the form for creating a new SRA.
     */
    public function create()
    {
        $this->authorize('isStorekeeper');
        
        $items = Item::orderBy('name')->get();
        return view('sra.create', compact('items'));
    }

    /**
     * Store a newly created SRA in database.
     */
    public function store(Request $request)
    {
        $this->authorize('isStorekeeper');
        
        $validated = $request->validate([
            'sra_number' => 'nullable|string|max:50|unique:sra',
            'supplier_details' => 'nullable|string',
            'supplier_name' => 'nullable|string|max:255',
            'bill_number' => 'nullable|string|max:100',
            'waybill_number' => 'nullable|string|max:100',
            'delivery_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $supplierDetails = $validated['supplier_details'] ?? $this->buildSupplierDetails($validated);

        $sra = Sra::create([
            'sra_number' => $validated['sra_number'] ?? 'SRA-' . time(),
            'supplier_details' => $supplierDetails,
            'created_by' => auth()->id(),
            'status' => 'pending',
            'signed_storekeeper' => true, // Storekeeper signs by creating it
        ]);

        // Attach items
        foreach ($validated['items'] as $item) {
            $sra->sraItems()->create([
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        // Note: Inventory ledger entries are recorded when SRA is fully approved
        // (when all three signatures are collected), not when initially created.

        // Log to audit trail
        $this->logAudit('CREATE', 'sra', $sra->id);

        return redirect()->route('sra.show', $sra)
            ->with('success', "SRA '{$sra->sra_number}' created successfully.");
    }

    /**
     * Display the specified SRA.
     */
    public function show(Sra $sra)
    {
        $this->authorize('accessSra');
        
        $sra->load('sraItems.item', 'createdBy');
        return view('sra.show', compact('sra'));
    }

    /**
     * Show the form for editing the specified SRA.
     */
    public function edit(Sra $sra)
    {
        $this->authorize('isStorekeeper');
        
        $sra->load('sraItems');
        $items = Item::orderBy('name')->get();
        return view('sra.edit', compact('sra', 'items'));
    }

    /**
     * Update the specified SRA in database.
     */
    public function update(Request $request, Sra $sra)
    {
        $this->authorize('isStorekeeper');
        
        if ($sra->status !== 'pending') {
            return back()->with('error', 'Cannot edit approved SRA.');
        }

        $validated = $request->validate([
            'supplier_details' => 'nullable|string',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Update basic info
        $sra->update(['supplier_details' => $validated['supplier_details']]);

        // Remove old items
        $sra->sraItems()->delete();

        // Add new items
        foreach ($validated['items'] as $item) {
            $sra->sraItems()->create([
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        // Log to audit trail
        $this->logAudit('UPDATE', 'sra', $sra->id);

        return redirect()->route('sra.show', $sra)
            ->with('success', 'SRA updated successfully.');
    }

    /**
     * Remove the specified SRA from database.
     */
    public function destroy(Sra $sra)
    {
        $this->authorize('isStorekeeper');
        
        if ($sra->status !== 'pending') {
            return back()->with('error', 'Cannot delete approved SRA.');
        }

        $sra->delete();

        // Log to audit trail
        $this->logAudit('DELETE', 'sra', $sra->id);

        return redirect()->route('sra.index')
            ->with('success', 'SRA deleted successfully.');
    }

    /**
     * Approve SRA (multi-signature workflow)
     */
    public function approve(Request $request, Sra $sra)
    {
        $this->authorize('accessSra');
        
        $user = auth()->user();
        $previousStatus = $sra->status;

        if ($user->hasRole('auditor')) {
            $sra->signed_auditor = true;
        } elseif ($user->hasRole('principal')) {
            $sra->signed_principal = true;
        } else {
            return back()->with('error', 'You do not have permission to approve SRA.');
        }

        // If both Auditor and Principal signatures obtained, mark as approved and record inventory
        // Note: Storekeeper only creates, does not approve
        if ($sra->signed_auditor && $sra->signed_principal) {
            // Only update status if not already approved
            if ($sra->status !== 'approved') {
                $sra->status = 'approved';
                
                // Record inventory ledger entries for all items in the SRA
                // This only happens once when transitioning to 'approved' status
                foreach ($sra->sraItems as $sraItem) {
                    $this->recordLedgerEntry(
                        $sraItem->item_id, 
                        'RECEIVE', 
                        $sraItem->quantity, 
                        'SRA', 
                        $sra->id
                    );
                }
            }
        }

        $sra->save();

        // Log to audit trail
        $this->logAudit('APPROVE', 'sra', $sra->id);

        if ($sra->status === 'approved' && $previousStatus !== 'approved') {
            return back()->with('success', 'SRA fully approved and inventory has been recorded.');
        } else {
            return back()->with('success', 'SRA signed by ' . $user->getRoleDisplayName());
        }
    }

    /**
     * Record inventory ledger entry
     */
    private function recordLedgerEntry($itemId, $type, $quantity, $refType, $refId)
    {
        $lastEntry = \App\Models\InventoryLedger::where('item_id', $itemId)->latest()->first();
        $balanceBefore = $lastEntry ? $lastEntry->balance_after : 0;
        $balanceAfter = $type === 'RECEIVE' ? $balanceBefore + $quantity : $balanceBefore - $quantity;

        \App\Models\InventoryLedger::create([
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
     * Build a readable supplier details string from form fields.
     */
    private function buildSupplierDetails(array $validated): ?string
    {
        $parts = [];

        if (!empty($validated['supplier_name'])) {
            $parts[] = 'Supplier: ' . $validated['supplier_name'];
        }
        if (!empty($validated['bill_number'])) {
            $parts[] = 'Bill #: ' . $validated['bill_number'];
        }
        if (!empty($validated['waybill_number'])) {
            $parts[] = 'Waybill #: ' . $validated['waybill_number'];
        }
        if (!empty($validated['delivery_date'])) {
            $parts[] = 'Delivery Date: ' . $validated['delivery_date'];
        }

        if (empty($parts)) {
            return null;
        }

        return implode(' | ', $parts);
    }
}

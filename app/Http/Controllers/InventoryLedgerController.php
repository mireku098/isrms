<?php

namespace App\Http\Controllers;

use App\Models\InventoryLedger;
use App\Models\Item;
use Illuminate\Http\Request;

class InventoryLedgerController extends Controller
{
    /**
     * Display a listing of inventory transactions.
     */
    public function index(Request $request)
    {
        $this->authorize('accessLedger');
        
        $query = InventoryLedger::with('item');
        
        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $transactions = $query->orderByDesc('created_at')->paginate(50);
        
        $items = Item::orderBy('name')->get();
        
        $selectedItem = null;
        $stats = null;
        
        if ($request->filled('item_id')) {
            $selectedItem = Item::find($request->item_id);
            if ($selectedItem) {
                $stats = [
                    'total_receipts' => InventoryLedger::where('item_id', $selectedItem->id)->where('transaction_type', 'RECEIVE')->sum('quantity'),
                    'total_issues' => InventoryLedger::where('item_id', $selectedItem->id)->where('transaction_type', 'ISSUE')->sum('quantity'),
                    'current_balance' => $selectedItem->getCurrentStock(),
                    'min_stock' => $selectedItem->min_stock,
                    'max_stock' => $selectedItem->max_stock,
                    'unit' => $selectedItem->unit ?? 'units',
                ];
            }
        } else {
            $stats = [
                'total_receipts' => InventoryLedger::where('transaction_type', 'RECEIVE')->sum('quantity'),
                'total_issues' => InventoryLedger::where('transaction_type', 'ISSUE')->sum('quantity'),
                'current_balance' => Item::all()->sum(function($item) { return $item->getCurrentStock(); }),
                'low_stock_items' => Item::all()->filter(function($item) { return $item->getCurrentStock() < $item->min_stock; })->count(),
            ];
        }
        
        return view('ledger.index', compact('transactions', 'items', 'selectedItem', 'stats'));
    }

    /**
     * Show transactions for a specific item
     */
    public function byItem(Item $item)
    {
        $transactions = $item->ledgerEntries()
            ->orderByDesc('created_at')
            ->paginate(50);
        
        $currentStock = $item->getCurrentStock();
        $isLowStock = $item->isLowStock();
        $isOverStock = $item->isOverStock();
        
        return view('ledger.by-item', compact('item', 'transactions', 'currentStock', 'isLowStock', 'isOverStock'));
    }

    /**
     * Filter transactions by type
     */
    public function byType($type)
    {
        if (!in_array($type, ['RECEIVE', 'ISSUE'])) {
            return back()->with('error', 'Invalid transaction type.');
        }

        $transactions = InventoryLedger::where('transaction_type', $type)
            ->with('item')
            ->orderByDesc('created_at')
            ->paginate(50);
        
        return view('ledger.by-type', compact('transactions', 'type'));
    }

    /**
     * Filter transactions by reference
     */
    public function byReference($type, $id)
    {
        if (!in_array($type, ['SRA', 'ISSUE'])) {
            return back()->with('error', 'Invalid reference type.');
        }

        $transactions = InventoryLedger::where('reference_type', $type)
            ->where('reference_id', $id)
            ->with('item')
            ->orderByDesc('created_at')
            ->paginate(50);
        
        return view('ledger.by-reference', compact('transactions', 'type', 'id'));
    }

    /**
     * Get statistics
     */
    public function statistics()
    {
        $totalReceived = InventoryLedger::where('transaction_type', 'RECEIVE')
            ->sum('quantity');
        
        $totalIssued = InventoryLedger::where('transaction_type', 'ISSUE')
            ->sum('quantity');
        
        $thisMonthReceived = InventoryLedger::where('transaction_type', 'RECEIVE')
            ->whereMonth('created_at', now()->month)
            ->sum('quantity');
        
        $thisMonthIssued = InventoryLedger::where('transaction_type', 'ISSUE')
            ->whereMonth('created_at', now()->month)
            ->sum('quantity');
        
        $itemStats = Item::all()->map(function ($item) {
            return [
                'name' => $item->name,
                'current_stock' => $item->getCurrentStock(),
                'min_stock' => $item->min_stock,
                'max_stock' => $item->max_stock,
                'status' => $item->isLowStock() ? 'low' : ($item->isOverStock() ? 'over' : 'normal'),
            ];
        });
        
        return view('ledger.statistics', compact(
            'totalReceived',
            'totalIssued',
            'thisMonthReceived',
            'thisMonthIssued',
            'itemStats'
        ));
    }

    /**
     * Export ledger to CSV
     */
    public function export()
    {
        $transactions = InventoryLedger::with('item')->get();
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="ledger-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Date', 'Item', 'Type', 'Quantity', 'Balance', 'Reference']);
            
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->created_at->format('Y-m-d H:i'),
                    $transaction->item->name,
                    $transaction->transaction_type,
                    $transaction->quantity,
                    $transaction->balance_after,
                    "{$transaction->reference_type}-{$transaction->reference_id}",
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

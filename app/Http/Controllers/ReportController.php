<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Requisition;
use App\Models\Sra;
use App\Models\Issue;
use App\Models\InventoryLedger;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Show reports dashboard
     */
    public function index()
    {
        $this->authorize('accessReports');
        
        $items = Item::orderBy('name')->get();
        $lowStockItems = $items->filter(function($item) {
            return $item->getCurrentStock() < $item->min_stock;
        });

        $stats = [
            'total_items' => $items->count(),
            'low_stock_count' => $lowStockItems->count(),
            'total_inventory_value' => 0, // In a real system, you'd calculate this based on prices
        ];

        return view('reports.index', compact('items', 'lowStockItems', 'stats'));
    }

    /**
     * Inventory report - Low stock items
     */
    public function lowStock()
    {
        $items = Item::all()
            ->filter(fn($item) => $item->isLowStock())
            ->values()
            ->paginate(20);
        
        return view('reports.low-stock', compact('items'));
    }

    /**
     * Inventory report - Over stock items
     */
    public function overStock()
    {
        $items = Item::all()
            ->filter(fn($item) => $item->isOverStock())
            ->values()
            ->paginate(20);
        
        return view('reports.over-stock', compact('items'));
    }

    /**
     * Requisition report - Pending
     */
    public function pendingRequisitions()
    {
        $requisitions = Requisition::where('status', 'pending')
            ->with('requester')
            ->orderByDesc('created_at')
            ->paginate(20);
        
        return view('reports.pending-requisitions', compact('requisitions'));
    }

    /**
     * Requisition report - Approved
     */
    public function approvedRequisitions()
    {
        $requisitions = Requisition::where('status', 'approved')
            ->with('requester', 'approver')
            ->orderByDesc('created_at')
            ->paginate(20);
        
        return view('reports.approved-requisitions', compact('requisitions'));
    }

    /**
     * Transaction report - All transactions
     */
    public function transactions()
    {
        $transactions = InventoryLedger::with('item')
            ->orderByDesc('created_at')
            ->paginate(50);
        
        return view('reports.transactions', compact('transactions'));
    }

    /**
     * Transaction report - By item
     */
    public function itemTransactions(Item $item)
    {
        $transactions = $item->ledgerEntries()
            ->orderByDesc('created_at')
            ->paginate(50);
        
        $currentStock = $item->getCurrentStock();
        
        return view('reports.item-transactions', compact('item', 'transactions', 'currentStock'));
    }

    /**
     * SRA report
     */
    public function sraReport()
    {
        $sras = Sra::with('createdBy')
            ->orderByDesc('created_at')
            ->paginate(20);
        
        return view('reports.sra-report', compact('sras'));
    }

    /**
     * User activity report (Admin only)
     */
    public function userActivity()
    {
        $this->authorize('isAdmin');
        
        $auditLogs = \App\Models\AuditLog::with('user')
            ->orderByDesc('created_at')
            ->paginate(50);
        
        return view('reports.user-activity', compact('auditLogs'));
    }

    /**
     * Export transactions to CSV
     */
    public function exportTransactions()
    {
        $transactions = InventoryLedger::with('item')->get();
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="transactions-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, ['Date', 'Item', 'Type', 'Quantity', 'Balance', 'Reference']);
            
            // Data rows
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

    /**
     * Export requisitions to CSV
     */
    public function exportRequisitions()
    {
        $requisitions = Requisition::with('requester', 'approver')->get();
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="requisitions-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($requisitions) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, ['ID', 'Requester', 'Status', 'Approver', 'Created', 'Updated']);
            
            // Data rows
            foreach ($requisitions as $req) {
                fputcsv($file, [
                    $req->id,
                    $req->requester->name,
                    $req->status,
                    ($req->approver ? $req->approver->name : 'N/A'),
                    $req->created_at->format('Y-m-d'),
                    $req->updated_at->format('Y-m-d'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

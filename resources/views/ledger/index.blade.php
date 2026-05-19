@extends('layouts.app')

@section('title', 'Inventory Ledger (Telecard)')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Inventory Ledger (Telecard)</h2>
                <p class="mb-0 text-secondary small">Real-time digital record of all inventory transactions for each item.</p>
            </div>
            <button class="btn btn-primary" onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print Ledger
            </button>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="col-12">
        <x-card class="border-0 shadow-sm">
            <form action="{{ route('ledger.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">Select Item</label>
                    <select name="item_id" class="form-select">
                        <option value="">All Items</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }} ({{ $item->category ?? 'Uncategorized' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-secondary">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-secondary">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-sm">Apply Filter</button>
                    <a href="{{ route('ledger.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>
        </x-card>
    </div>

    @if($selectedItem)
    <!-- Item Summary -->
    <div class="col-12">
        <div class="row g-3 mb-4">
            <div class="col-xl-4 col-md-6">
                <x-card class="border-0 shadow-sm h-100 bg-primary-subtle">
                    <h6 class="fw-semibold text-primary mb-1">Item Name</h6>
                    <div class="fs-5 fw-bold">{{ $selectedItem->name }}</div>
                    <small class="text-secondary">ID: ITEM-{{ str_pad($selectedItem->id, 3, '0', STR_PAD_LEFT) }}</small>
                </x-card>
            </div>
            <div class="col-xl-4 col-md-6">
                <x-card class="border-0 shadow-sm h-100">
                    <h6 class="fw-semibold mb-1">Total Receipts</h6>
                    <div class="fs-5 fw-bold text-success">+{{ $stats['total_receipts'] }}</div>
                    <small class="text-secondary">{{ $selectedItem->unit ?? 'units' }} received</small>
                </x-card>
            </div>
            <div class="col-xl-4 col-md-6">
                <x-card class="border-0 shadow-sm h-100">
                    <h6 class="fw-semibold mb-1">Current Balance</h6>
                    <div class="fs-5 fw-bold text-primary">{{ $stats['current_balance'] }}</div>
                    <small class="text-secondary">Running balance</small>
                </x-card>
            </div>
        </div>
    </div>
    @endif

    <!-- Ledger Transactions -->
    <div class="col-12">
        <x-card title="Transaction History" class="border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Transaction Type</th>
                            <th>Reference #</th>
                            <th>Qty In</th>
                            <th>Qty Out</th>
                            <th>Running Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $transaction->item ? $transaction->item->name : 'N/A' }}</td>
                            <td>
                                @if($transaction->transaction_type === 'RECEIVE')
                                    <x-badge type="success" text="RECEIPT (SRA)" />
                                @else
                                    <x-badge type="danger" text="ISSUE" />
                                @endif
                            </td>
                            <td>{{ $transaction->reference_type }}-{{ $transaction->reference_id }}</td>
                            <td class="text-success fw-bold">{{ $transaction->transaction_type === 'RECEIVE' ? '+' . $transaction->quantity : '-' }}</td>
                            <td class="text-danger fw-bold">{{ $transaction->transaction_type === 'ISSUE' ? '-' . $transaction->quantity : '-' }}</td>
                            <td class="fw-bold">{{ $transaction->balance_after }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-secondary">No transactions found matching your criteria.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </x-card>
    </div>

    <!-- Ledger Summary -->
    <div class="col-12">
        <div class="row g-3">
            <div class="col-md-6">
                <x-card title="{{ $selectedItem ? 'Summary Statistics: ' . $selectedItem->name : 'Global Summary Statistics' }}" class="border-0 shadow-sm">
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span>Total Receipts:</span>
                        <strong class="text-success">+{{ number_format($stats['total_receipts']) }} {{ $selectedItem ? $selectedItem->unit : 'items' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span>Total Issues:</span>
                        <strong class="text-danger">-{{ number_format($stats['total_issues']) }} {{ $selectedItem ? $selectedItem->unit : 'items' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">{{ $selectedItem ? 'Current Balance:' : 'Total System Balance:' }}</span>
                        <strong class="text-primary fs-5">{{ number_format($stats['current_balance']) }} {{ $selectedItem ? $selectedItem->unit : 'items' }}</strong>
                    </div>
                </x-card>
            </div>
            <div class="col-md-6">
                <x-card title="Stock Status" class="border-0 shadow-sm">
                    @if($selectedItem)
                        @php
                            $stock = $stats['current_balance'];
                            $badgeType = 'success';
                            $statusText = 'In Stock';
                            if ($stock <= 0) { $badgeType = 'danger'; $statusText = 'Out of Stock'; }
                            elseif ($stock < $stats['min_stock']) { $badgeType = 'warning'; $statusText = 'Low Stock'; }
                        @endphp
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span>Current Stock:</span>
                            <span class="badge bg-{{ $badgeType }} px-2 py-1">{{ $stock }} / Max: {{ $stats['max_stock'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span>Min. Stock Level:</span>
                            <span>{{ $stats['min_stock'] }} {{ $selectedItem->unit }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Status:</span>
                            <span class="badge bg-{{ $badgeType }}">{{ $statusText }}</span>
                        </div>
                    @else
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span>Total Items in Registry:</span>
                            <span class="fw-bold">{{ $items->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span>Items with Low Stock:</span>
                            <span class="badge bg-warning text-body-emphasis px-2 py-1">{{ $stats['low_stock_items'] }} items</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>System Health:</span>
                            @if($stats['low_stock_items'] > 0)
                                <span class="badge bg-warning">Attention Required</span>
                            @else
                                <span class="badge bg-success">Optimal</span>
                            @endif
                        </div>
                    @endif
                </x-card>
            </div>
        </div>
    </div>
</div>
@endsection

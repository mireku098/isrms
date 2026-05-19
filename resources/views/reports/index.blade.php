@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Reports & Analytics</h2>
                <p class="mb-0 text-secondary small">Generate comprehensive reports on inventory, receipts, issues, and stock status.</p>
            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="window.print()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    Print
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Export
                </button>
            </div>
        </div>
    </div>

    <!-- Report Selection Cards -->
    <div class="col-md-6 col-xl-3">
        <a href="#stockBalanceReport" class="text-decoration-none">
            <x-card class="border-0 shadow-sm h-100 cursor-pointer hover-shadow transition">
                <div class="text-center">
                    <div class="icon-shape icon-lg rounded-circle bg-info-subtle text-info mx-auto mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                            <path d="M12 12l8 -4.5" />
                            <path d="M12 12l0 9" />
                            <path d="M12 12l-8 -4.5" />
                        </svg>
                    </div>
                    <h5 class="fw-semibold mb-1">Stock Balance</h5>
                    <p class="small text-secondary mb-0">Current inventory positions</p>
                </div>
            </x-card>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="#itemLedgerReport" class="text-decoration-none">
            <x-card class="border-0 shadow-sm h-100 cursor-pointer hover-shadow transition">
                <div class="text-center">
                    <div class="icon-shape icon-lg rounded-circle bg-success-subtle text-success mx-auto mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 8l0 4l2 2" />
                            <path d="M3 21l18 0" />
                            <path d="M5 21l-2 -8a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1l-2 8" />
                            <path d="M9 5a3 3 0 0 1 6 0" />
                        </svg>
                    </div>
                    <h5 class="fw-semibold mb-1">Item Ledger</h5>
                    <p class="small text-secondary mb-0">Transaction history per item</p>
                </div>
            </x-card>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="#receivedItemsReport" class="text-decoration-none">
            <x-card class="border-0 shadow-sm h-100 cursor-pointer hover-shadow transition">
                <div class="text-center">
                    <div class="icon-shape icon-lg rounded-circle bg-primary-subtle text-primary mx-auto mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                            <path d="M7 9l5 5l5 -5" />
                            <path d="M12 4l0 12" />
                        </svg>
                    </div>
                    <h5 class="fw-semibold mb-1">Received Items</h5>
                    <p class="small text-secondary mb-0">All SRA transactions</p>
                </div>
            </x-card>
        </a>
    </div>

    <div class="col-md-6 col-xl-3">
        <a href="#lowStockReport" class="text-decoration-none">
            <x-card class="border-0 shadow-sm h-100 cursor-pointer hover-shadow transition">
                <div class="text-center">
                    <div class="icon-shape icon-lg rounded-circle bg-warning-subtle text-warning mx-auto mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="12" r="9" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    </div>
                    <h5 class="fw-semibold mb-1">Low Stock Alert</h5>
                    <p class="small text-secondary mb-0">Items below min. levels</p>
                </div>
            </x-card>
        </a>
    </div>

    <!-- Stock Balance Report -->
    <div class="col-12" id="stockBalanceReport">
        <x-card title="Stock Balance Report" class="border-0 shadow-sm">
            <x-slot name="headerAction">
                <div class="d-flex gap-2">
                    <input type="date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" style="max-width: 150px;">
                    <button class="btn btn-sm btn-primary">Generate</button>
                </div>
            </x-slot>

            <x-table :headers="['Item Code', 'Item Name', 'Category', 'Current Stock', 'Min Level', 'Max Level', 'Status']">
                @forelse($items as $item)
                @php
                    $stock = $item->getCurrentStock();
                    $status = 'In Stock';
                    $badgeType = 'success';
                    if($stock <= 0) { $status = 'Out of Stock'; $badgeType = 'danger'; }
                    elseif($stock < $item->min_stock) { $status = 'Low Stock'; $badgeType = 'warning'; }
                @endphp
                <tr>
                    <td>ITEM-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category ?? 'Uncategorized' }}</td>
                    <td class="fw-bold">{{ $stock }}</td>
                    <td>{{ $item->min_stock }}</td>
                    <td>{{ $item->max_stock }}</td>
                    <td><x-badge :type="$badgeType" :text="$status" /></td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-secondary">No items found in the registry.</td>
                </tr>
                @endforelse
            </x-table>

            <x-slot name="footer">
                <div class="d-flex justify-content-between">
                    <span class="small text-secondary">Total Items: {{ $stats['total_items'] }}</span>
                </div>
            </x-slot>
        </x-card>
    </div>

    <!-- Low Stock Alert Report -->
    <div class="col-12" id="lowStockReport">
        <x-card title="Low Stock & Out of Stock Items" class="border-0 shadow-sm">
            @if($lowStockItems->count() > 0)
            <x-alert type="warning" message="⚠️ The following items require immediate attention!" />
            @endif
            
            <x-table :headers="['Item', 'Current Stock', 'Min Required', 'Deficit', 'Status']">
                @forelse($lowStockItems as $item)
                @php
                    $stock = $item->getCurrentStock();
                    $deficit = $item->min_stock - $stock;
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $item->name }}</div>
                        <div class="small text-secondary">ITEM-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td class="fw-bold {{ $stock <= 0 ? 'text-danger' : 'text-warning' }}">{{ $stock }}</td>
                    <td>{{ $item->min_stock }}</td>
                    <td class="text-danger fw-bold">-{{ $deficit }}</td>
                    <td><x-badge :type="$stock <= 0 ? 'danger' : 'warning'" :text="$stock <= 0 ? 'Out of Stock' : 'Low Stock'" /></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <p class="mb-0">All items are currently above minimum stock levels.</p>
                    </td>
                </tr>
                @endforelse
            </x-table>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time stock updates every 10 seconds
        setInterval(async function() {
            const tableRows = document.querySelectorAll('#stockBalanceReport tbody tr');
            
            for (const row of tableRows) {
                const itemCode = row.cells[0].textContent;
                if (!itemCode.startsWith('ITEM-')) continue;
                
                const itemId = parseInt(itemCode.replace('ITEM-', ''));
                const minStock = parseInt(row.cells[4].textContent);
                
                try {
                    const response = await fetch(`/api/requisitions/items/${itemId}/stock`);
                    if (!response.ok) continue;
                    
                    const data = await response.json();
                    const currentStock = data.current_stock;
                    
                    // Update stock cell
                    const stockCell = row.cells[3];
                    stockCell.textContent = currentStock;
                    
                    // Update status badge
                    const statusCell = row.cells[6];
                    const badge = statusCell.querySelector('.badge');
                    
                    if (badge) {
                        badge.classList.remove('bg-success', 'bg-warning', 'bg-danger');
                        if (currentStock <= 0) {
                            badge.textContent = 'Out of Stock';
                            badge.classList.add('bg-danger');
                            stockCell.classList.add('text-danger');
                        } else if (currentStock < minStock) {
                            badge.textContent = 'Low Stock';
                            badge.classList.add('bg-warning');
                            stockCell.classList.add('text-warning');
                            stockCell.classList.remove('text-danger');
                        } else {
                            badge.textContent = 'In Stock';
                            badge.classList.add('bg-success');
                            stockCell.classList.remove('text-danger', 'text-warning');
                        }
                    }
                } catch (error) {
                    console.error('Error fetching stock:', error);
                }
            }
        }, 10000);
    });
</script>
@endpush

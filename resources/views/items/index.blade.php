@extends('layouts.app')

@section('title', 'Inventory Management')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Inventory Management</h2>
                <p class="mb-0 text-secondary small">Central registry of all store items.</p>
            </div>
            <a href="{{ route('items.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add New Item
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-primary-subtle text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                    </svg>
                </div>
                <div>
                    <h5 class="mb-0">Total Items</h5>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_items']) }}</div>
                </div>
            </div>
        </x-card>
    </div>
    <div class="col-xl-3 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-warning-subtle text-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                        <path d="M12 3a9 9 0 1 0 9 9" />
                    </svg>
                </div>
                <div>
                    <h5 class="mb-0">Low Stock</h5>
                    <div class="fs-4 fw-bold">{{ number_format($stats['low_stock']) }}</div>
                </div>
            </div>
        </x-card>
    </div>
    <div class="col-xl-3 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-danger-subtle text-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div>
                    <h5 class="mb-0">Out of Stock</h5>
                    <div class="fs-4 fw-bold">{{ number_format($stats['out_of_stock']) }}</div>
                </div>
            </div>
        </x-card>
    </div>
    <div class="col-xl-3 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-success-subtle text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 11l5 5l5 -5" />
                        <path d="M12 4l0 12" />
                    </svg>
                </div>
                <div>
                    <h5 class="mb-0">Categories</h5>
                    <div class="fs-4 fw-bold">{{ number_format($stats['categories']) }}</div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Items Table -->
    <div class="col-12">
        <x-card title="All Items" class="border-0 shadow-sm">
            <x-slot name="headerAction">
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="Search items...">
                    <form method="GET" action="{{ route('items.index') }}">
                        <select name="category_id" class="form-select form-select-sm" style="width: 180px;" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (string) $selectedCategory === (string) $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </x-slot>

            <x-table :headers="['Item Name', 'Category', 'Unit', 'Balance', 'Min Stock', 'Max Stock', 'Status', 'Actions']">
                @forelse($items as $item)
                @php
                    $stock = $item->getCurrentStock();
                    $status = 'In Stock';
                    $badgeType = 'success';
                    
                    if ($stock <= 0) {
                        $status = 'Out of Stock';
                        $badgeType = 'danger';
                    } elseif ($stock < $item->min_stock) {
                        $status = 'Low Stock';
                        $badgeType = 'warning';
                    }
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold text-body-emphasis">{{ $item->name }}</div>
                        <div class="small text-secondary">ID: ITEM-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td>{{ $item->category ?? 'Uncategorized' }}</td>
                    <td>{{ $item->unit ?? 'N/A' }}</td>
                    <td class="fw-bold {{ $stock <= 0 ? 'text-danger' : 'text-body-emphasis' }}">{{ $stock }}</td>
                    <td>{{ $item->min_stock }}</td>
                    <td>{{ $item->max_stock }}</td>
                    <td><x-badge :type="$badgeType" :text="$status" /></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-ghost btn-icon btn-sm rounded-circle" type="button" data-bs-toggle="dropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="12" cy="5" r="1"></circle>
                                    <circle cx="12" cy="19" r="1"></circle>
                                </svg>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('items.edit', $item->id) }}">Edit</a></li>
                                <li><a class="dropdown-item" href="{{ route('ledger.index', ['item_id' => $item->id]) }}">View Ledger</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-secondary">
                        No items found in the registry. 
                        <a href="{{ route('items.create') }}" class="text-primary fw-semibold">Add your first item</a>
                    </td>
                </tr>
                @endforelse
            </x-table>

            <x-slot name="footer">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-secondary">
                        Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} items
                    </span>
                    <div>
                        {{ $items->links() }}
                    </div>
                </div>
            </x-slot>
        </x-card>
    </div>
</div>
@endsection

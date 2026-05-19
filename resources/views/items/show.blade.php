@extends('layouts.app')

@section('title', 'View Item')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Item Details</h3>
            <div class="gap-2 d-flex">
                <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-primary">Edit</a>
                <a href="{{ route('items.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <p class="text-secondary mb-2">Item ID</p>
                        <p class="fs-5 fw-semibold mb-4">#ITEM{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</p>

                        <p class="text-secondary mb-2">Name</p>
                        <p class="fs-5 fw-semibold mb-4">{{ $item->name }}</p>

                        <p class="text-secondary mb-2">Category</p>
                        <p class="fs-5 fw-semibold mb-4">
                            {{ $item->categoryRelation ? $item->categoryRelation->name : ($item->category ?? 'Uncategorized') }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-secondary mb-2">Unit</p>
                        <p class="fs-5 fw-semibold mb-4">{{ $item->unit ?? 'N/A' }}</p>

                        <p class="text-secondary mb-2">Current Stock</p>
                        <div class="mb-4">
                            <span class="fs-5 fw-semibold">{{ $stock }}</span>
                            <span class="text-secondary">units</span>
                            @if($item->isLowStock())
                                <span class="badge bg-warning ms-2">Low Stock</span>
                            @elseif($item->isOverStock())
                                <span class="badge bg-info ms-2">Over Stock</span>
                            @else
                                <span class="badge bg-success ms-2">In Stock</span>
                            @endif
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-4">
                    <div class="col-md-3">
                        <p class="text-secondary mb-2 small">Minimum Stock Level</p>
                        <p class="fs-6 fw-semibold">{{ $item->min_stock }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-secondary mb-2 small">Maximum Stock Level</p>
                        <p class="fs-6 fw-semibold">{{ $item->max_stock }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-secondary mb-2 small">Created</p>
                        <p class="fs-6 fw-semibold">{{ $item->created_at->format('M j, Y') }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-secondary mb-2 small">Last Updated</p>
                        <p class="fs-6 fw-semibold">{{ $item->updated_at->format('M j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Transaction History -->
<div class="row mt-6">
    <div class="col-12">
        <h5 class="fw-bold mb-4">Stock Transaction History</h5>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Balance</th>
                            <th>Reference</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ledger as $entry)
                        <tr>
                            <td>{{ $entry->created_at->format('M j, Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $entry->transaction_type === 'RECEIVE' ? 'success' : 'danger' }}">
                                    {{ $entry->transaction_type }}
                                </span>
                            </td>
                            <td>{{ $entry->quantity }}</td>
                            <td>
                                <strong>{{ $entry->balance_after }}</strong>
                            </td>
                            <td>
                                @if($entry->reference_type && $entry->reference_id)
                                    {{ $entry->reference_type }} #{{ $entry->reference_id }}
                                @else
                                    <small class="text-secondary">-</small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-secondary">No transactions found for this item.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($ledger->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $ledger->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

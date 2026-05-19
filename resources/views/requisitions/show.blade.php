@extends('layouts.app')

@section('title', 'View Requisition')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('requisitions.index') }}">Requisitions</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $requisition->requisition_number }}</li>
            </ol>
        </nav>
    </div>

    <!-- Requisition Header -->
    <div class="col-lg-8">
        <x-card title="Requisition Details" class="border-0 shadow-sm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Requisition #</label>
                        <p class="text-body-emphasis fw-bold">{{ $requisition->requisition_number }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Status</label>
                        <p>
                            @php
                                $statusType = 'warning';
                                $statusText = 'Pending Approval';
                                if ($requisition->status === 'approved') {
                                    $statusType = 'success';
                                    $statusText = 'Approved';
                                } elseif ($requisition->status === 'rejected') {
                                    $statusType = 'danger';
                                    $statusText = 'Rejected';
                                } elseif ($requisition->issues()->exists()) {
                                    $statusType = 'info';
                                    $statusText = 'Issued';
                                }
                            @endphp
                            <x-badge :type="$statusType" :text="$statusText" />
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Date Requested</label>
                        <p class="text-body-emphasis">{{ $requisition->request_date ? $requisition->request_date->format('M d, Y') : $requisition->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Requested By</label>
                        <p class="text-body-emphasis">{{ $requisition->requester->name ?? 'Unknown' }} ({{ $requisition->department ?? 'N/A' }})</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Department</label>
                        <p class="text-body-emphasis">{{ $requisition->department ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Purpose / Remarks</label>
                        <p class="text-body-emphasis">{{ $requisition->purpose ?? 'No purpose provided' }}</p>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Approval Status Card -->
    <div class="col-lg-4">
        <x-card title="Approval Status" class="border-0 shadow-sm">
            <div class="d-flex flex-column gap-2">
                @if($requisition->status === 'pending')
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning-subtle text-warning p-2 rounded-circle" title="Awaiting Principal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle></svg>
                    </span>
                    <div>
                        <div class="small fw-semibold">Principal Approval</div>
                        <div class="small text-secondary">Awaiting review</div>
                    </div>
                </div>
                @elseif($requisition->status === 'approved')
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success-subtle text-success p-2 rounded-circle" title="Approved">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                    <div>
                        <div class="small fw-semibold">Approved by {{ $requisition->approver->name ?? 'Principal' }}</div>
                        <div class="small text-secondary">{{ $requisition->updated_at->format('M d, Y H:i') }}</div>
                    </div>
                </div>
                @else
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger-subtle text-danger p-2 rounded-circle" title="Rejected">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </span>
                    <div>
                        <div class="small fw-semibold">Rejected</div>
                        <div class="small text-secondary">{{ $requisition->updated_at->format('M d, Y H:i') }}</div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Approval Actions (for Principal only) -->
            @if($requisition->status === 'pending' && auth()->user() && auth()->user()->role === 'principal')
            <div class="border-top mt-4 pt-4">
                <p class="small text-secondary mb-2">As Principal, you can approve or reject this requisition:</p>
                <div class="d-grid gap-2">
                    <form action="{{ route('requisitions.approve', $requisition) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="display: inline;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Approve
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="display: inline;"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        Reject
                    </button>
                </div>
            </div>
            @endif
        </x-card>
    </div>

    <!-- Requested Items -->
    <div class="col-12">
        <x-card title="Requested Items" class="border-0 shadow-sm">
            <x-table :headers="['Item', 'Unit', 'Quantity Requested', 'Current Stock', 'Notes']">
                @foreach($requisition->requisitionItems as $reqItem)
                @php
                    $item = $reqItem->item;
                    $stock = $item->getCurrentStock();
                    $stockBadgeType = 'success';
                    if ($stock <= 0) {
                        $stockBadgeType = 'danger';
                    } elseif ($stock < $item->min_stock) {
                        $stockBadgeType = 'warning';
                    }
                @endphp
                <tr>
                    <td class="fw-bold">{{ $item->name }}</td>
                    <td>{{ $item->unit ?? 'N/A' }}</td>
                    <td>{{ $reqItem->quantity_requested }}</td>
                    <td>
                        <span class="badge bg-{{ $stockBadgeType }} stock-badge" data-item-id="{{ $item->id }}" data-min-stock="{{ $item->min_stock }}">
                            {{ $stock }} available
                        </span>
                    </td>
                    <td class="small text-secondary">{{ $reqItem->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </x-table>
        </x-card>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Requisition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('requisitions.reject', $requisition) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label fw-semibold small text-secondary">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" id="rejection_reason" class="form-control @error('rejection_reason') is-invalid @enderror" rows="4" placeholder="Explain why this requisition is being rejected..." required></textarea>
                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Requisition</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time stock updates every 5 seconds
        setInterval(async function() {
            const stockBadges = document.querySelectorAll('.stock-badge');
            
            for (const badge of stockBadges) {
                const itemId = badge.dataset.itemId;
                const minStock = parseInt(badge.dataset.minStock);
                
                try {
                    const response = await fetch(`/api/requisitions/items/${itemId}/stock`);
                    if (!response.ok) continue;
                    
                    const data = await response.json();
                    const currentStock = data.current_stock;
                    
                    badge.textContent = `${currentStock} available`;
                    
                    // Update badge color
                    badge.classList.remove('bg-success', 'bg-warning', 'bg-danger');
                    if (currentStock <= 0) {
                        badge.classList.add('bg-danger');
                    } else if (currentStock < minStock) {
                        badge.classList.add('bg-warning');
                    } else {
                        badge.classList.add('bg-success');
                    }
                } catch (error) {
                    console.error('Error fetching stock:', error);
                }
            }
        }, 5000);
    });
</script>
@endpush
@endsection

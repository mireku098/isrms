@extends('layouts.app')

@section('title', 'View Issue')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('issues.index') }}">Issues</a></li>
                <li class="breadcrumb-item active" aria-current="page">ISSUE-{{ str_pad($issue->id, 5, '0', STR_PAD_LEFT) }}</li>
            </ol>
        </nav>
    </div>

    <!-- Issue Header -->
    <div class="col-lg-8">
        <x-card title="Issue Details" class="border-0 shadow-sm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Issue #</label>
                        <p class="text-body-emphasis fw-bold">ISSUE-{{ str_pad($issue->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Status</label>
                        <p>
                            @if($issue->receivedBy)
                                <x-badge type="success" text="Received" />
                            @else
                                <x-badge type="primary" text="Pending Receipt" />
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Date Issued</label>
                        <p class="text-body-emphasis">{{ $issue->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">From Requisition</label>
                        <p class="text-body-emphasis">
                            <a href="{{ route('requisitions.show', $issue->requisition_id) }}">
                                {{ $issue->requisition->requisition_number }}
                            </a>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Department</label>
                        <p class="text-body-emphasis">{{ $issue->requisition->department ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Requested By</label>
                        <p class="text-body-emphasis">{{ $issue->requisition->requester->name ?? 'Unknown' }}</p>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Receipt Status Card -->
    <div class="col-lg-4">
        <x-card title="Receipt Status" class="border-0 shadow-sm">
            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Status</label>
                <div>
                    @if($issue->receivedBy)
                        <span class="badge bg-success-subtle text-success">Items received</span>
                    @else
                        <span class="badge bg-warning-subtle text-warning">Items not yet received</span>
                    @endif
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Received By</label>
                <p class="text-secondary small">{{ $issue->receivedBy->name ?? 'Not yet marked as received' }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Receipt Date</label>
                <p class="text-secondary small">{{ $issue->received_at ? $issue->received_at->format('M d, Y') : 'Pending' }}</p>
            </div>

            @if($issue->receiver_name)
            <div class="border-top mt-3 pt-3">
                <label class="form-label fw-semibold small text-secondary">Receiver Name</label>
                <p class="text-secondary small">{{ $issue->receiver_name }}</p>
            </div>
            @endif

            @if($issue->receiver_signature)
            <div class="mt-2">
                <label class="form-label fw-semibold small text-secondary">Signature/Initial</label>
                <p class="text-secondary small">{{ $issue->receiver_signature }}</p>
            </div>
            @endif

            @if($issue->remarks)
            <div class="mt-2">
                <label class="form-label fw-semibold small text-secondary">Remarks</label>
                <p class="text-secondary small">{{ $issue->remarks }}</p>
            </div>
            @endif

            @if($issue->comments)
            <div class="mt-2 border-top pt-3">
                <label class="form-label fw-semibold small text-secondary">Receipt Comments</label>
                <p class="text-secondary small">{{ $issue->comments }}</p>
            </div>
            @endif

            <!-- Receipt Action -->
            @if(!$issue->receivedBy && auth()->user()->id === $issue->requisition->requested_by)
            <div class="border-top mt-4 pt-4">
                <button type="button" class="btn btn-primary w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#receiptModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="display: inline;"><polyline points="9 11 12 14 22 4"></polyline><path d="M20 21H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2z"></path></svg>
                    Confirm Receipt
                </button>
            </div>
            @endif
        </x-card>
    </div>

    <!-- Issued Items -->
    <div class="col-12">
        <x-card title="Items Issued" class="border-0 shadow-sm">
            <x-table :headers="['Item', 'Unit', 'Quantity Issued', 'Status']">
                @foreach($issue->issueItems as $issueItem)
                <tr>
                    <td class="fw-bold">{{ $issueItem->item->name }}</td>
                    <td>{{ $issueItem->item->unit ?? 'Pieces' }}</td>
                    <td>{{ $issueItem->quantity_issued }}</td>
                    <td>
                        @if($issue->receivedBy)
                            <x-badge type="success" text="Received" />
                        @else
                            <x-badge type="warning" text="Pending" />
                        @endif
                    </td>
                </tr>
                @endforeach
            </x-table>
        </x-card>
    </div>

    <!-- Receipt Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Receipt of Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('issues.receive', $issue->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>By clicking "Confirm Receipt", you acknowledge that you have received all the items listed above.</p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Comments (Optional)</label>
                            <textarea name="comments" class="form-control" rows="3" placeholder="Any comments regarding the received items..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm Receipt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

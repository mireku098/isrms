@extends('layouts.app')

@section('title', 'Edit Issue')

@section('content')
<div class="row justify-content-center g-6">
    <div class="col-xl-10">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('issues.index') }}">Issues</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('issues.show', $issue->id) }}">ISSUE-{{ str_pad($issue->id, 5, '0', STR_PAD_LEFT) }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
            <h2 class="mb-0">Edit Issue</h2>
            <p class="text-secondary small">Modify the quantities and details for this issue. Cannot edit after items are received.</p>
        </div>

        @if($issue->receivedBy)
            <div class="alert alert-danger alert-dismissible fade show">
                Cannot edit this issue. Items have already been received.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <div class="text-end">
                <a href="{{ route('issues.show', $issue->id) }}" class="btn btn-ghost">Back to Issue</a>
            </div>
        @else
            <x-card class="border-0 shadow-sm">
                <!-- Alert Messages -->
                <div id="alertContainer"></div>

                <form action="{{ route('issues.update', $issue->id) }}" method="POST" id="issueForm">
                    @csrf
                    @method('PUT')

                    <!-- Issue Details (Read-only) -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold mb-3">Issue Details</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">Issue #</label>
                                    <input type="text" class="form-control" value="ISSUE-{{ str_pad($issue->id, 5, '0', STR_PAD_LEFT) }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">From Requisition</label>
                                    <input type="text" class="form-control" value="{{ $issue->requisition->requisition_number }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">Department</label>
                                    <input type="text" class="form-control" value="{{ $issue->requisition->department }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">Requested By</label>
                                    <input type="text" class="form-control" value="{{ $issue->requisition->requester->name }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Receiver Information -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold mb-3">Receiver Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="receiver_name" class="form-label fw-semibold small text-secondary">Receiver Name</label>
                                    <input type="text" name="receiver_name" id="receiver_name" class="form-control @error('receiver_name') is-invalid @enderror" value="{{ old('receiver_name', $issue->receiver_name) }}" placeholder="Name of person receiving items">
                                    @error('receiver_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="receiver_signature" class="form-label fw-semibold small text-secondary">Signature/Initial</label>
                                    <input type="text" name="receiver_signature" id="receiver_signature" class="form-control @error('receiver_signature') is-invalid @enderror" value="{{ old('receiver_signature', $issue->receiver_signature) }}" placeholder="Type initials or signature">
                                    @error('receiver_signature')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="remarks" class="form-label fw-semibold small text-secondary">Remarks</label>
                                    <input type="text" name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror" value="{{ old('remarks', $issue->remarks) }}" placeholder="Any notes...">
                                    @error('remarks')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items to Issue -->
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold mb-3">Items Issued</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Current Stock</th>
                                        <th>Issue Qty <span class="text-danger">*</span></th>
                                        <th>Unit</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTableBody">
                                    @foreach($issue->issueItems as $index => $issueItem)
                                        @php
                                            $currentStock = \App\Models\InventoryLedger::where('item_id', $issueItem->item_id)->latest()->first();
                                            $stock = $currentStock ? $currentStock->balance_after : 0;
                                        @endphp
                                        <tr>
                                            <td class="fw-bold">{{ $issueItem->item->name }}</td>
                                            <td class="text-center"><span class="badge bg-info">{{ $stock }}</span></td>
                                            <td>
                                                <input type="hidden" name="items[{{ $index }}][item_id]" value="{{ $issueItem->item_id }}">
                                                <input type="number" name="items[{{ $index }}][quantity_issued]" class="form-control form-control-sm" min="0" value="{{ old('items.' . $index . '.quantity_issued', $issueItem->quantity_issued) }}">
                                            </td>
                                            <td>{{ $issueItem->item->unit ?? 'Pieces' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('issues.show', $issue->id) }}" class="btn btn-ghost">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Issue</button>
                    </div>
                </form>
            </x-card>
        @endif
    </div>
</div>
@endsection

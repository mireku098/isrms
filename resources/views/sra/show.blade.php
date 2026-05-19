@extends('layouts.app')

@section('title', 'View SRA')

@section('content')
@php
    $statusType = $sra->status === 'approved' ? 'success' : 'warning';
    $supplierLines = collect(explode('|', (string) ($sra->supplier_details ?? '')))
        ->map(fn ($line) => trim($line))
        ->filter();
@endphp
<div class="row mb-6 g-6">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('sra.index') }}">SRA Receipts</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $sra->sra_number }}</li>
            </ol>
        </nav>
    </div>

    <!-- SRA Header -->
    <div class="col-lg-8">
        <x-card title="Stores Received Advice Details" class="border-0 shadow-sm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">SRA #</label>
                        <p class="text-body-emphasis fw-bold">{{ $sra->sra_number }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Status</label>
                        <p><x-badge :type="$statusType" :text="ucfirst($sra->status)" /></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Created Date</label>
                        <p class="text-body-emphasis">{{ $sra->created_at ? $sra->created_at->format('M d, Y h:i A') : '-' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Created By</label>
                        <p class="text-body-emphasis">{{ $sra->createdBy ? $sra->createdBy->name : 'Unknown' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Supplier Details</label>
                        @if($supplierLines->isNotEmpty())
                            @foreach($supplierLines as $line)
                                <p class="mb-1 text-body-emphasis">{{ $line }}</p>
                            @endforeach
                        @else
                            <p class="text-secondary">No supplier details provided.</p>
                        @endif
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Approval Status Card -->
    <div class="col-lg-4">
        <x-card title="Multi-Signature Approval" class="border-0 shadow-sm">
            <div class="d-flex flex-column gap-3">
                <!-- Auditor -->
                <div class="d-flex align-items-center gap-2">
                    <span class="badge {{ $sra->signed_auditor ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} p-2 rounded-circle" title="Auditor Status">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                    <div>
                        <div class="small fw-semibold">Auditor</div>
                        <div class="small text-secondary">{{ $sra->signed_auditor ? 'Signed' : 'Pending review' }}</div>
                    </div>
                </div>

                <!-- Principal -->
                <div class="d-flex align-items-center gap-2">
                    <span class="badge {{ $sra->signed_principal ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} p-2 rounded-circle" title="Principal Status">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle></svg>
                    </span>
                    <div>
                        <div class="small fw-semibold">Principal</div>
                        <div class="small text-secondary">{{ $sra->signed_principal ? 'Signed' : 'Pending review' }}</div>
                    </div>
                </div>
            </div>

            <!-- Approval Action -->
            @if(auth()->user() && auth()->user()->hasRole('auditor') && !$sra->signed_auditor)
            <div class="border-top mt-4 pt-4">
                <p class="small text-secondary mb-2">As Auditor, you must verify and sign:</p>
                <form action="{{ route('sra.approve', $sra->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100 btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="display: inline;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Review & Sign as Auditor
                    </button>
                </form>
            </div>
            @elseif(auth()->user() && auth()->user()->hasRole('principal') && !$sra->signed_principal)
            <div class="border-top mt-4 pt-4">
                <p class="small text-secondary mb-2">As Principal, you must review and approve:</p>
                <form action="{{ route('sra.approve', $sra->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success w-100 btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="display: inline;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Final Approval
                    </button>
                </form>
            </div>
            @endif
        </x-card>
    </div>

    <!-- Received Items -->
    <div class="col-12">
        <x-card title="Received Items" class="border-0 shadow-sm">
            <x-table :headers="['Item', 'Category', 'Unit', 'Quantity Received']">
                @forelse($sra->sraItems as $sraItem)
                    <tr>
                        <td class="fw-bold text-body-emphasis">{{ $sraItem->item ? $sraItem->item->name : 'Unknown Item' }}</td>
                        <td>{{ $sraItem->item ? $sraItem->item->category : 'Uncategorized' }}</td>
                        <td>{{ $sraItem->item ? $sraItem->item->unit : 'N/A' }}</td>
                        <td>{{ number_format($sraItem->quantity) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-secondary">No received items for this SRA.</td>
                    </tr>
                @endforelse
            </x-table>
        </x-card>
    </div>

    <!-- Approval Timeline -->
    <div class="col-12">
        <x-card title="Approval Timeline" class="border-0 shadow-sm">
            <div class="timeline">
                <div class="timeline-item pb-4 border-bottom">
                    <div class="d-flex gap-3">
                        <div class="timeline-marker">
                            <span class="badge bg-success rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">SRA Created</h6>
                            <p class="small text-secondary">{{ $sra->created_at ? $sra->created_at->format('M d, Y h:i A') : '-' }} by {{ $sra->createdBy ? $sra->createdBy->name : 'Unknown' }} (Storekeeper)</p>
                        </div>
                    </div>
                </div>
                <div class="timeline-item py-4 border-bottom">
                    <div class="d-flex gap-3">
                        <div class="timeline-marker">
                            <span class="badge {{ $sra->signed_auditor ? 'bg-success' : 'bg-secondary' }} rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Auditor Signature</h6>
                            <p class="small text-secondary">{{ $sra->signed_auditor ? 'Completed' : 'Pending review' }}</p>
                        </div>
                    </div>
                </div>
                <div class="timeline-item pt-4">
                    <div class="d-flex gap-3">
                        <div class="timeline-marker">
                            <span class="badge {{ $sra->signed_principal ? 'bg-success' : 'bg-secondary' }} rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Principal Signature</h6>
                            <p class="small text-secondary">{{ $sra->signed_principal ? 'Completed' : 'Pending' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection

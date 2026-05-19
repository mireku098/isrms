@extends('layouts.app')

@section('title', 'Requisitions')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Requisitions</h2>
                <p class="mb-0 text-secondary small">Submit and track requests for store items.</p>
            </div>
            @can('isRequester')
            <a href="{{ route('requisitions.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Requisition
            </a>
            @endcan
        </div>
    </div>

    <!-- Requisition List -->
    <div class="col-12">
        <x-card title="Requisition History" class="border-0 shadow-sm">
            <x-slot name="headerAction">
                <form method="GET" action="{{ route('requisitions.index') }}" class="d-flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search Requisition #...">
                    <select name="status" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="issued" {{ request('status') === 'issued' ? 'selected' : '' }}>Issued</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    @if(request()->filled('search') || request()->filled('status'))
                        <a href="{{ route('requisitions.index') }}" class="btn btn-sm btn-ghost">Reset</a>
                    @endif
                </form>
            </x-slot>

            <x-table :headers="['REQ #', 'Requested By', 'Department', 'Date', 'Status', 'Approvals', 'Actions']">
                @forelse($requisitions as $req)
                <tr>
                    <td>
                        <div class="fw-bold text-body-emphasis">{{ $req->requisition_number }}</div>
                        <div class="small text-secondary">{{ $req->requisitionItems->count() }} items requested</div>
                    </td>
                    <td>{{ $req->requester ? $req->requester->name : 'Unknown' }}</td>
                    <td>{{ $req->department ?? 'N/A' }}</td>
                    <td>{{ $req->created_at->format('M d, Y') }}</td>
                    <td>
                        @php
                            $statusType = 'warning';
                            if($req->status === 'approved') $statusType = 'success';
                            if($req->status === 'rejected') $statusType = 'danger';
                            if($req->issues->count() > 0) $statusType = 'info';
                        @endphp
                        <x-badge :type="$statusType" :text="ucfirst($req->status)" />
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <span class="badge {{ $req->approved_by ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} p-1 rounded-circle" title="{{ $req->approved_by ? 'Principal Signed' : 'Waiting for Principal' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    @if($req->approved_by)
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    @else
                                        <circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>
                                    @endif
                                </svg>
                            </span>
                            @if($req->issues->count() > 0)
                            <span class="badge bg-info-subtle text-info p-1 rounded-circle" title="Items Issued">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                            </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('requisitions.show', $req->id) }}" class="btn btn-ghost btn-icon btn-sm rounded-circle text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>
                            @if($req->status === 'approved' && $req->issues->count() === 0 && auth()->user()->hasRole('storekeeper'))
                            <a href="{{ route('issues.create', ['requisition_id' => $req->id]) }}" class="btn btn-sm btn-outline-success">Issue</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-secondary">No requisition records found.</td>
                </tr>
                @endforelse
            </x-table>
            <div class="mt-4">
                {{ $requisitions->links() }}
            </div>
        </x-card>
    </div>
</div>
@endsection

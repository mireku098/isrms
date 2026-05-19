@extends('layouts.app')

@section('title', 'Issue Items')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Issue Items</h2>
                <p class="mb-0 text-secondary small">Track and issue approved requisitions from store inventory.</p>
            </div>
            <a href="{{ route('issues.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Issue Items
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-xl-4 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-info-subtle text-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                        <path d="M7 9l5 5l5 -5" />
                        <path d="M12 4l0 12" />
                    </svg>
                </div>
                <div>
                    <h5 class="mb-0">Total Issued</h5>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_issued']) }}</div>
                </div>
            </div>
        </x-card>
    </div>
    <div class="col-xl-4 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-warning-subtle text-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="1" />
                        <circle cx="12" cy="5" r="1" />
                        <circle cx="12" cy="19" r="1" />
                    </svg>
                </div>
                <div>
                    <h5 class="mb-0">Pending Receipt</h5>
                    <div class="fs-4 fw-bold text-warning">{{ number_format($stats['pending_received']) }}</div>
                </div>
            </div>
        </x-card>
    </div>
    <div class="col-xl-4 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-success-subtle text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </div>
                <div>
                    <h5 class="mb-0">Received by Dept</h5>
                    <div class="fs-4 fw-bold text-success">{{ number_format($stats['received_by_dept']) }}</div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Issues Table -->
    <div class="col-12">
        <x-card title="All Issue Records" class="border-0 shadow-sm">
            <x-slot name="headerAction">
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="Search Issue #...">
                    <select class="form-select form-select-sm" style="width: 150px;">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="issued">Issued</option>
                        <option value="received">Received</option>
                    </select>
                </div>
            </x-slot>

            <x-table :headers="['Issue #', 'Requisition', 'Department', 'Items', 'Issued Date', 'Status', 'Actions']">
                @forelse($issues as $issue)
                <tr>
                    <td class="fw-semibold">ISSUE-{{ str_pad($issue->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $issue->requisition->requisition_number ?? ('REQ-' . str_pad($issue->requisition_id, 5, '0', STR_PAD_LEFT)) }}</td>
                    <td>{{ $issue->requisition && $issue->requisition->requester ? $issue->requisition->requester->department : 'N/A' }}</td>
                    <td>{{ $issue->issueItems->count() }} items</td>
                    <td>{{ $issue->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($issue->receivedBy)
                            <x-badge type="success" text="Received" />
                        @else
                            <x-badge type="warning" text="Pending Receipt" />
                        @endif
                    </td>
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
                                <li><a class="dropdown-item" href="{{ route('issues.show', $issue->id) }}">View Details</a></li>
                                @if($issue->receivedBy)
                                <li><a class="dropdown-item" href="#">Print Receipt</a></li>
                                @elseif(auth()->user()->id === ($issue->requisition ? $issue->requisition->requested_by : null))
                                <li>
                                    <form action="{{ route('issues.receive', $issue->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Confirm Receipt</button>
                                    </form>
                                </li>
                                @endif
                                @if(!$issue->receivedBy && auth()->user()->hasRole('storekeeper'))
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('issues.destroy', $issue->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">Cancel Issue</button>
                                    </form>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-secondary">No issue records found.</td>
                </tr>
                @endforelse
            </x-table>

            <x-slot name="footer">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-secondary">Showing {{ $issues->firstItem() ?? 0 }} to {{ $issues->lastItem() ?? 0 }} of {{ $issues->total() }} issues</span>
                    <div>
                        {{ $issues->links() }}
                    </div>
                </div>
            </x-slot>
        </x-card>
    </div>
</div>
@endsection

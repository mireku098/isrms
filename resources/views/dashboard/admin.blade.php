@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <div>
            <h2 class="mb-1">Welcome, Administrator</h2>
            <p class="text-secondary">{{ date('l, F j, Y') }} - System overview and management</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-xl-3 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-primary-subtle text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">Total Users</h6>
                    <div class="fs-4 fw-bold">{{ $stats['total_users'] }}</div>
                    <small class="text-secondary">{{ $stats['active_users'] }} active</small>
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
                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">Total Items</h6>
                    <div class="fs-4 fw-bold">{{ $stats['total_items'] }}</div>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-xl-3 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-info-subtle text-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">Requisitions</h6>
                    <div class="fs-4 fw-bold">{{ $stats['total_requisitions'] }}</div>
                    <small class="text-warning">{{ $stats['pending_requisitions'] }} pending</small>
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
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                        <path d="M7 11l5 5l5 -5" />
                        <path d="M12 4l0 12" />
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">SRAs & Issues</h6>
                    <div class="fs-4 fw-bold">{{ $stats['total_sras'] + $stats['total_issues'] }}</div>
                    <small class="text-secondary">{{ $stats['total_sras'] }} SRA, {{ $stats['total_issues'] }} Issues</small>
                </div>
            </div>
        </x-card>
    </div>
</div>

<!-- System Overview -->
<div class="row g-6">
    <div class="col-xl-8">
        <x-card class="border-0 shadow-sm" title="System Activity (Audit Logs)">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recent_logs'] as $log)
                        <tr>
                            <td>{{ $log->user ? $log->user->name : 'System' }}</td>
                            <td>{{ $log->action }} {{ $log->model_type }} #{{ $log->model_id }}</td>
                            <td>{{ $log->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-secondary">No recent activity logs found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    <!-- Quick Actions -->
    <div class="col-xl-4">
        <x-card class="border-0 shadow-sm" title="Quick Actions">
            <div class="d-grid gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="display: inline;">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        <path d="M16 11h6" />
                        <path d="M19 8v6" />
                    </svg>
                    Manage Users
                </a>
                <a href="{{ route('items.index') }}" class="btn btn-outline-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="display: inline;">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 7l11 5l-11 5v-10" />
                    </svg>
                    Manage Items
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="display: inline;">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                        <path d="M7 11l5 5l5 -5" />
                        <path d="M12 4l0 12" />
                    </svg>
                    View Reports
                </a>
                <a href="{{ route('ledger.index') }}" class="btn btn-outline-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="display: inline;">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 10h4v10h-4z" />
                        <path d="M3 20h18" />
                        <path d="M7 15h1" />
                        <path d="M16 15h1" />
                    </svg>
                    Inventory Ledger
                </a>
            </div>
        </x-card>
    </div>
</div>

<!-- Recent Requisitions -->
<div class="row mt-6">
    <div class="col-12">
        <x-card class="border-0 shadow-sm" title="Recent Requisitions">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Requisition ID</th>
                            <th>Requester</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recent_requisitions'] as $req)
                        <tr>
                            <td><strong>#REQ-{{ $req->id }}</strong></td>
                            <td>{{ $req->requester ? $req->requester->name : 'N/A' }}</td>
                            <td>
                                @php
                                    $itemCount = $req->requisitionItems->count();
                                @endphp
                                {{ $itemCount }} {{ $itemCount === 1 ? 'item' : 'items' }}
                            </td>
                            <td>
                                @if($req->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($req->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($req->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($req->status) }}</span>
                                @endif
                            </td>
                            <td><small>{{ $req->created_at->format('M j, Y') }}</small></td>
                            <td><a href="{{ route('requisitions.show', $req) }}" class="text-primary text-decoration-none">View</a></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-secondary">No requisitions found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Storekeeper Dashboard')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <div>
            <h2 class="mb-1">Welcome, Storekeeper</h2>
            <p class="text-secondary">{{ date('l, F j, Y') }}</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-xl-3 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-primary-subtle text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">Pending SRA Approvals</h6>
                    <div class="fs-4 fw-bold">{{ $stats['pending_sras'] }}</div>
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
                        <circle cx="12" cy="12" r="1" />
                        <circle cx="12" cy="5" r="1" />
                        <circle cx="12" cy="19" r="1" />
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">Items to Issue</h6>
                    <div class="fs-4 fw-bold">{{ $stats['approved_requisitions'] }}</div>
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
                    <h6 class="mb-0 text-secondary">Low Stock Items</h6>
                    <div class="fs-4 fw-bold">{{ $stats['low_stock_items'] }}</div>
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

    <!-- Quick Actions -->
    <div class="col-12">
        <h5 class="fw-bold mb-3">Quick Actions</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('sra.create') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                        <path d="M7 9l5 5l5 -5" />
                        <path d="M12 4l0 12" />
                    </svg>
                    Record Receipt (SRA)
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('issues.create') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" />
                        <path d="M7 11l5 5l5-5" />
                        <path d="M12 4l0 12" />
                    </svg>
                    Issue Items
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('items.index') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                    </svg>
                    Manage Inventory
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('ledger.index') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                    </svg>
                    View Ledger
                </a>
            </div>
        </div>
    </div>

    <!-- Tasks Section -->
    <div class="col-lg-8">
        <x-card title="Pending Tasks" class="border-0 shadow-sm">
            <div class="list-group list-group-flush">
                @forelse($stats['pending_tasks'] as $task)
                <a href="{{ $task['link'] }}" class="list-group-item list-group-item-action px-0 py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">{{ $task['title'] }}</h6>
                            <p class="mb-0 small text-secondary">{{ $task['desc'] }}</p>
                        </div>
                        <span class="badge {{ $task['badge_class'] }}">{{ $task['badge'] }}</span>
                    </div>
                </a>
                @empty
                <div class="py-4 text-center text-secondary">
                    <p class="mb-0 small">No pending tasks at the moment.</p>
                </div>
                @endforelse
            </div>
        </x-card>
    </div>

    <!-- Recent Activity -->
    <div class="col-lg-4">
        <x-card title="Recent SRA Receipts" class="border-0 shadow-sm">
            <div class="activity-feed">
                @forelse($stats['recent_sras'] as $sra)
                <div class="activity-item mb-3 pb-3 border-bottom">
                    <div class="d-flex gap-2">
                        <div class="activity-icon bg-success-subtle text-success rounded p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                <path d="M7 11l5 5l5 -5" />
                                <path d="M12 4l0 12" />
                            </svg>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 small"><strong>{{ $sra->sra_number }}</strong></p>
                            <small class="text-secondary">{{ $sra->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-4 text-center text-secondary">
                    <p class="mb-0 small">No recent SRAs.</p>
                </div>
                @endforelse
            </div>
        </x-card>
    </div>
</div>
@endsection

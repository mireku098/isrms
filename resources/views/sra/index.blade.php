@extends('layouts.app')

@section('title', 'Stores Received Advice (SRA)')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Stores Received Advice (SRA)</h2>
                <p class="mb-0 text-secondary small">Record and manage goods received from suppliers.</p>
            </div>
            @can('isStorekeeper')
            <a href="{{ route('sra.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Receipt (SRA)
            </a>
            @endcan
        </div>
    </div>

    <!-- SRA List -->
    <div class="col-12">
        <x-card title="SRA History" class="border-0 shadow-sm">
            <x-slot name="headerAction">
                <form method="GET" action="{{ route('sra.index') }}" class="d-flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search SRA #...">
                    <select name="status" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    @if(request()->filled('search') || request()->filled('status'))
                        <a href="{{ route('sra.index') }}" class="btn btn-sm btn-ghost">Reset</a>
                    @endif
                </form>
            </x-slot>

            <x-table :headers="['SRA #', 'Supplier', 'Date Received', 'Status', 'Approvals', 'Actions']">
                @forelse($sras as $sra)
                <tr>
                    <td>
                        <div class="fw-bold text-body-emphasis">{{ $sra->sra_number }}</div>
                        <div class="small text-secondary">Created by {{ $sra->createdBy ? $sra->createdBy->name : 'Unknown' }}</div>
                    </td>
                    <td>{{ Str::limit($sra->supplier_details, 30) }}</td>
                    <td>{{ $sra->created_at->format('M d, Y') }}</td>
                    <td>
                        <span class="badge {{ $sra->status === 'approved' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($sra->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <span class="badge {{ $sra->signed_auditor ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} p-1 rounded-circle" title="Auditor Signed">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    @if($sra->signed_auditor)
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    @else
                                        <circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>
                                    @endif
                                </svg>
                            </span>
                            <span class="badge {{ $sra->signed_principal ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} p-1 rounded-circle" title="Principal Signed">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    @if($sra->signed_principal)
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    @else
                                        <circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>
                                    @endif
                                </svg>
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('sra.show', $sra->id) }}" class="btn btn-ghost btn-icon btn-sm rounded-circle text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>
                            @if($sra->status === 'approved')
                            <a href="#" class="btn btn-ghost btn-icon btn-sm rounded-circle text-secondary" title="Print PDF">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-secondary">No SRA records found.</td>
                </tr>
                @endforelse
            </x-table>
            <div class="mt-4">
                {{ $sras->links() }}
            </div>
        </x-card>
    </div>
</div>
@endsection

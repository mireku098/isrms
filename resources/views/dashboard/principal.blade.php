@extends('layouts.app')

@section('title', 'Principal Dashboard')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <div>
            <h2 class="mb-1">Welcome, Principal</h2>
            <p class="text-secondary">{{ date('l, F j, Y') }} - Review and approve pending requests</p>
        </div>
    </div>

    <!-- Quick Stats -->
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
                    <h6 class="mb-0 text-secondary">Pending Requisitions</h6>
                    <div class="fs-4 fw-bold">{{ $stats['pending_requisitions'] }}</div>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-xl-4 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-info-subtle text-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                        <path d="M7 11l5 5l5 -5" />
                        <path d="M12 4l0 12" />
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">Pending SRA Approvals</h6>
                    <div class="fs-4 fw-bold">{{ $stats['pending_sras'] }}</div>
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
                    <h6 class="mb-0 text-secondary">Total Requisitions</h6>
                    <div class="fs-4 fw-bold">{{ $stats['total_requisitions'] }}</div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Pending Requisitions for Approval -->
    <div class="col-12">
        <x-card title="Pending Requisitions for Approval" class="border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="pendingRequisitionsTable">
                    <thead class="table-light">
                        <tr>
                            <th>REQ #</th>
                            <th>Requested By</th>
                            <th>Department</th>
                            <th>Items</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['pending_requisition_list'] as $req)
                        <tr>
                            <td class="fw-semibold">{{ $req->requisition_number }}</td>
                            <td>{{ $req->requester->name ?? 'Unknown' }}</td>
                            <td>{{ $req->department ?? 'N/A' }}</td>
                            <td>{{ $req->requisitionItems->count() }} items</td>
                            <td>{{ $req->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('requisitions.show', $req) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    <form action="{{ route('requisitions.approve', $req) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this requisition?')">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $req->id }}">
                                        Reject
                                    </button>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Requisition {{ $req->requisition_number }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('requisitions.reject', $req) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold small text-secondary">Rejection Reason</label>
                                                        <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Provide a reason for rejection..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger btn-sm">Reject Requisition</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-secondary">No pending requisitions awaiting approval.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    <!-- Recent Requisitions (History) -->
    <div class="col-12">
        <x-card title="Recent Activity (History)" class="border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="recentRequisitionsTable">
                    <thead class="table-light">
                        <tr>
                            <th>REQ #</th>
                            <th>Requested By</th>
                            <th>Items</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Approved By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recent_requisitions'] as $req)
                        <tr>
                            <td class="fw-semibold">{{ $req->requisition_number }}</td>
                            <td>{{ $req->requester->name ?? 'Unknown' }}</td>
                            <td>{{ $req->requisitionItems->count() }}</td>
                            <td>{{ $req->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge {{ $req->status === 'approved' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td>
                                @if($req->status === 'approved' && $req->approver)
                                    <span class="text-success fw-semibold">{{ $req->approver->name }}</span>
                                @elseif($req->status === 'approved')
                                    <span class="text-muted">System Approved</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('requisitions.show', $req) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-secondary">No recent requisition history found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    <!-- SRA Approvals -->
    <div class="col-12">
        <x-card title="Pending SRA Approvals" class="border-0 shadow-sm">
            <x-alert type="info" message="After Storekeeper and Auditor signatures, these require your final approval before they can be printed." />

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="pendingSrasTable">
                    <thead class="table-light">
                        <tr>
                            <th>SRA #</th>
                            <th>Supplier</th>
                            <th>Items</th>
                            <th>Auditor Signed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['pending_sra_list'] as $sra)
                        <tr>
                            <td class="fw-semibold">{{ $sra->sra_number }}</td>
                            <td>{{ $sra->supplier_details }}</td>
                            <td>{{ $sra->sraItems->count() }}</td>
                            <td><x-badge type="success" text="✓ Signed" /></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('sra.show', $sra) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    <form action="{{ route('sra.approve', $sra) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve & Sign</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-secondary">No SRAs awaiting your signature.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    <!-- Summary Stats -->
    <div class="col-lg-8">
        <x-card title="Approval Trends" class="border-0 shadow-sm">
            <div class="p-5 text-center text-secondary border rounded-3 bg-light">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-50">
                    <path d="M3 3v18h18" />
                    <path d="M18 17l-6-4-2 2-4-4" />
                </svg>
                <p class="mb-0">Approval trend visualization will be available as more data is collected.</p>
            </div>
        </x-card>
    </div>

    <div class="col-lg-4">
        <x-card title="Quick Stats" class="border-0 shadow-sm">
            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                <span>Approvals Today:</span>
                <strong id="approvalsToday">{{ $stats['approvals_today'] }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                <span>This Week:</span>
                <strong id="approvalsThisWeek">{{ $stats['approvals_this_week'] }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                <span>This Month:</span>
                <strong id="approvalsThisMonth">{{ $stats['approvals_this_month'] }}</strong>
            </div>
            <div class="d-flex justify-content-between">
                <span>Approval Rate:</span>
                <strong class="text-success" id="approvalRate">{{ $stats['approval_rate'] }}%</strong>
            </div>
        </x-card>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function updatePendingRequisitions(reqs) {
            const tbody = document.querySelector('#pendingRequisitionsTable tbody');
            if (!tbody || !reqs) return;
            if (reqs.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-secondary">No pending requisitions awaiting approval.</td></tr>';
                return;
            }
            let html = '';
            reqs.forEach(req => {
                const date = new Date(req.created_at).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                const reqNumber = req.requisition_number || `REQ-${String(req.id).padStart(5, '0')}`;
                html += `
                    <tr>
                        <td class="fw-semibold">${reqNumber}</td>
                        <td>${req.requester ? req.requester.name : 'Unknown'}</td>
                        <td>${req.department || 'N/A'}</td>
                        <td>${req.requisition_items ? req.requisition_items.length : 0} items</td>
                        <td>${date}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="/requisitions/${req.id}" class="btn btn-sm btn-outline-primary">View</a>
                                <form action="/requisitions/${req.id}/approve" method="POST" style="display: inline;">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this requisition?')">Approve</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal${req.id}">
                                    Reject
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        function updateRecentRequisitions(reqs) {
            const tbody = document.querySelector('#recentRequisitionsTable tbody');
            if (!tbody || !reqs) return;
            if (reqs.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-secondary">No recent requisition history found.</td></tr>';
                return;
            }
            let html = '';
            reqs.forEach(req => {
                const date = new Date(req.created_at).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                const reqNumber = req.requisition_number || `REQ-${String(req.id).padStart(5, '0')}`;
                const statusBadge = req.status === 'approved' ? 'bg-success' : 'bg-danger';
                const approvedBy = req.status === 'approved' 
                    ? (req.approver ? req.approver.name : 'System Approved') 
                    : '-';
                html += `
                    <tr>
                        <td class="fw-semibold">${reqNumber}</td>
                        <td>${req.requester ? req.requester.name : 'Unknown'}</td>
                        <td>${req.requisition_items ? req.requisition_items.length : 0}</td>
                        <td>${date}</td>
                        <td><span class="badge ${statusBadge}">${req.status.charAt(0).toUpperCase() + req.status.slice(1)}</span></td>
                        <td><span class="${req.status === 'approved' ? 'text-success fw-semibold' : 'text-muted'}">${approvedBy}</span></td>
                        <td><a href="/requisitions/${req.id}" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        function updatePendingSras(sras) {
            const tbody = document.querySelector('#pendingSrasTable tbody');
            if (!tbody || !sras) return;
            if (sras.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-secondary">No SRAs awaiting your signature.</td></tr>';
                return;
            }
            let html = '';
            sras.forEach(sra => {
                html += `
                    <tr>
                        <td class="fw-semibold">${sra.sra_number}</td>
                        <td>${sra.supplier_details || '-'}</td>
                        <td>${sra.sra_items ? sra.sra_items.length : 0}</td>
                        <td><span class="badge bg-success">✓ Signed</span></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="/sra/${sra.id}" class="btn btn-sm btn-outline-primary">View</a>
                                <form action="/sra/${sra.id}/approve" method="POST">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <button type="submit" class="btn btn-sm btn-success">Approve & Sign</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        // Real-time stats polling every 10 seconds
        setInterval(async function() {
            try {
                const response = await fetch('/api/requisitions/dashboard/data');
                if (!response.ok) return;
                
                const data = await response.json();
                
                if (data.stats) {
                    // Update Counter Cards
                    const pendingReqCount = document.querySelector('.col-xl-4:nth-child(2) .fs-4');
                    if (pendingReqCount) pendingReqCount.textContent = data.stats.pending_requisitions;

                    const pendingSraCount = document.querySelector('.col-xl-4:nth-child(3) .fs-4');
                    if (pendingSraCount) pendingSraCount.textContent = data.stats.pending_sras;

                    const totalReqCount = document.querySelector('.col-xl-4:nth-child(4) .fs-4');
                    if (totalReqCount) totalReqCount.textContent = data.stats.total_requisitions;

                    // Update Quick Stats
                    const approvalsToday = document.getElementById('approvalsToday');
                    if (approvalsToday) approvalsToday.textContent = data.stats.approvals_today;

                    const approvalsThisWeek = document.getElementById('approvalsThisWeek');
                    if (approvalsThisWeek) approvalsThisWeek.textContent = data.stats.approvals_this_week;

                    const approvalsThisMonth = document.getElementById('approvalsThisMonth');
                    if (approvalsThisMonth) approvalsThisMonth.textContent = data.stats.approvals_this_month;

                    const approvalRate = document.getElementById('approvalRate');
                    if (approvalRate) approvalRate.textContent = data.stats.approval_rate + '%';
                }

                if (data.pending_requisitions) updatePendingRequisitions(data.pending_requisitions);
                if (data.recent_requisitions) updateRecentRequisitions(data.recent_requisitions);
                if (data.pending_sras) updatePendingSras(data.pending_sras);
            } catch (error) {
                console.error('Error fetching dashboard updates:', error);
            }
        }, 10000);
    });
</script>
@endpush
@endsection
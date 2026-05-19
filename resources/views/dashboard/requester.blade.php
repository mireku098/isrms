@extends('layouts.app')

@section('title', 'Department Requisitioner Dashboard')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">Welcome, Requisitioner</h2>
                <p class="text-secondary">{{ date('l, F j, Y') }} - Manage your store item requests</p>
            </div>
            <div id="lastUpdateTime" class="text-muted small">Last updated: just now</div>
        </div>
    </div>

    <!-- Alert Messages -->
    <div class="col-12" id="alertContainer"></div>

    <!-- Quick Stats -->
    <div class="col-xl-3 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-warning-subtle text-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="1" />
                        <circle cx="12" cy="5" r="1" />
                        <circle cx="12" cy="19" r="1" />
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">Pending</h6>
                    <div class="fs-4 fw-bold" id="pendingCount">{{ $stats['pending_requisitions'] }}</div>
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
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">Approved</h6>
                    <div class="fs-4 fw-bold" id="approvedCount">{{ $stats['approved_requisitions'] }}</div>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-xl-3 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-info-subtle text-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 11l3 3L22 3"></path>
                        <path d="M20.84 4.61a2.5 2.5 0 0 0-3.54 0l-2.54 2.54a2.5 2.5 0 0 0 0 3.54l2.54 2.54a2.5 2.5 0 0 0 3.54 0l2.54-2.54a2.5 2.5 0 0 0 0-3.54z"></path>
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">Issued</h6>
                    <div class="fs-4 fw-bold" id="issuedCount">{{ $stats['issued_requisitions'] }}</div>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-xl-3 col-md-6">
        <x-card class="border-0 shadow-sm h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-danger-subtle text-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">Rejected</h6>
                    <div class="fs-4 fw-bold" id="rejectedCount">{{ $stats['rejected_requisitions'] }}</div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Quick Action -->
    <div class="col-12">
        <a href="{{ route('requisitions.create') }}" class="btn btn-lg btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Create New Requisition
        </a>
    </div>

    <!-- My Requisitions Table -->
    <div class="col-12">
        <x-card title="Recent Requisitions" class="border-0 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <small class="text-muted">Showing recent requisitions (refreshes every 10 seconds)</small>
                </div>
                <div class="spinner-border spinner-border-sm" id="tableLoadingSpinner" style="display: none;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <x-table :headers="['REQ #', 'Date', 'Department', 'Items', 'Status', 'Actions']">
                <tbody id="requisitionsTableBody">
                    @forelse($stats['recent_my_requisitions'] as $req)
                    <tr>
                        <td class="fw-semibold">{{ $req->requisition_number }}</td>
                        <td>{{ $req->created_at->format('M d, Y') }}</td>
                        <td>{{ $req->department }}</td>
                        <td>{{ $req->requisitionItems->count() }}</td>
                        <td>
                            <span class="badge {{ $req->status === 'approved' ? 'bg-success' : ($req->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('requisitions.show', $req) }}" class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-secondary">You haven't made any requisitions yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </x-table>
        </x-card>
    </div>
</div>

<script>
    // Real-time dashboard data
    let dashboardUpdateInterval = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Load data immediately and then every 10 seconds
        loadDashboardData();
        setupRealtimeUpdates();
    });

    /**
     * Load dashboard data from API
     */
    async function loadDashboardData() {
        try {
            document.getElementById('tableLoadingSpinner').style.display = 'inline-block';

            const response = await fetch('/api/requisitions/dashboard/data');
            if (!response.ok) throw new Error('Failed to load dashboard data');

            const data = await response.json();

            // Update stats
            updateStatsCards(data.stats);

            // Update requisitions table
            await updateRequisitionsTable();

            // Update last update time
            updateLastUpdateTime();

        } catch (error) {
            console.error('Error loading dashboard:', error);
            showAlert('danger', 'Error loading dashboard data: ' + error.message);
        } finally {
            document.getElementById('tableLoadingSpinner').style.display = 'none';
        }
    }

    /**
     * Update stats cards
     */
    function updateStatsCards(stats) {
        const pendingEl = document.getElementById('pendingCount');
        const approvedEl = document.getElementById('approvedCount');
        const issuedEl = document.getElementById('issuedCount');
        const rejectedEl = document.getElementById('rejectedCount');

        // Update with animation if changed
        updateCountWithAnimation(pendingEl, stats.pending_requisitions);
        updateCountWithAnimation(approvedEl, stats.approved_requisitions);
        updateCountWithAnimation(issuedEl, stats.issued_requisitions);
        updateCountWithAnimation(rejectedEl, stats.rejected_requisitions);
    }

    /**
     * Update count with animation
     */
    function updateCountWithAnimation(element, newValue) {
        const oldValue = parseInt(element.textContent);
        if (oldValue !== newValue) {
            element.style.transition = 'all 0.3s ease';
            element.style.opacity = '0.5';
            setTimeout(() => {
                element.textContent = newValue;
                element.style.opacity = '1';
            }, 150);
        }
    }

    /**
     * Update requisitions table
     */
    async function updateRequisitionsTable() {
        try {
            const response = await fetch('/api/requisitions/my-list');
            if (!response.ok) return;

            const requisitions = await response.json();
            const tbody = document.getElementById('requisitionsTableBody');

            if (requisitions.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-secondary">No requisitions found</td></tr>';
                return;
            }

            tbody.innerHTML = requisitions.map(req => `
                <tr>
                    <td class="fw-semibold">${escapeHtml(req.requisition_number)}</td>
                    <td>${req.created_at}</td>
                    <td>${escapeHtml(req.department)}</td>
                    <td>${req.items_count}</td>
                    <td>
                        <span class="badge ${getStatusBadgeClass(req.status)}">
                            ${capitalizeFirst(req.status)}
                        </span>
                    </td>
                    <td>
                        <a href="/requisitions/${req.id}" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
            `).join('');

        } catch (error) {
            console.error('Error updating table:', error);
        }
    }

    /**
     * Get status badge class
     */
    function getStatusBadgeClass(status) {
        const classes = {
            'pending': 'bg-warning',
            'approved': 'bg-success',
            'rejected': 'bg-danger',
            'issued': 'bg-info'
        };
        return classes[status] || 'bg-secondary';
    }

    /**
     * Setup real-time updates
     */
    function setupRealtimeUpdates() {
        dashboardUpdateInterval = setInterval(() => {
            loadDashboardData();
        }, 10000); // Update every 10 seconds
    }

    /**
     * Update last update time
     */
    function updateLastUpdateTime() {
        const element = document.getElementById('lastUpdateTime');
        const now = new Date();
        element.textContent = `Last updated: ${now.toLocaleTimeString()}`;
    }

    /**
     * Show alert message
     */
    function showAlert(type, message) {
        const alertContainer = document.getElementById('alertContainer');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.role = 'alert';
        alert.innerHTML = `
            ${escapeHtml(message)}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        alertContainer.innerHTML = '';
        alertContainer.appendChild(alert);

        if (type === 'success' || type === 'info') {
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 5000);
        }
    }

    /**
     * Escape HTML
     */
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    /**
     * Capitalize first letter
     */
    function capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (dashboardUpdateInterval) {
            clearInterval(dashboardUpdateInterval);
        }
    });
</script>
@endsection

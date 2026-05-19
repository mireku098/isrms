@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">User Management</h2>
                <p class="mb-0 text-secondary small">Manage system users, roles, and permissions.</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add New User
            </a>
        </div>
    </div>

    <!-- Stats -->
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
                    <h5 class="mb-0">Total Users</h5>
                    <div class="fs-4 fw-bold">{{ $stats['total_users'] }}</div>
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
                        <circle cx="12" cy="12" r="9" />
                        <path d="M8 12l3 3l5 -5" />
                    </svg>
                </div>
                <div>
                    <h5 class="mb-0">Active Users</h5>
                    <div class="fs-4 fw-bold">{{ $stats['active_users'] }}</div>
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
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                        <path d="M12 3a9 9 0 1 0 9 9" />
                    </svg>
                </div>
                <div>
                    <h5 class="mb-0">Inactive Users</h5>
                    <div class="fs-4 fw-bold">{{ $stats['inactive_users'] }}</div>
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
                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                    </svg>
                </div>
                <div>
                    <h5 class="mb-0">Roles</h5>
                    <div class="fs-4 fw-bold">{{ $stats['roles_count'] }}</div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Users Table -->
    <div class="col-12">
        <x-card title="All Users" class="border-0 shadow-sm">
            <x-slot name="headerAction">
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="Search users...">
                    <select class="form-select form-select-sm" style="width: 150px;">
                        <option value="">All Roles</option>
                        <option value="admin">Admin</option>
                        <option value="storekeeper">Storekeeper</option>
                        <option value="auditor">Auditor</option>
                        <option value="principal">Principal</option>
                        <option value="requester">Requester</option>
                    </select>
                </div>
            </x-slot>

            <x-table :headers="['Name', 'Email', 'Role', 'Department', 'Status', 'Actions']">
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('assets/images/avatar/avatar-' . (($user->id % 20) + 1) . '.jpg') }}" alt="" class="avatar avatar-sm rounded-circle">
                            <div>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <small class="text-secondary">ID: USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @php
                            $roleBadge = 'primary';
                            if($user->role === 'admin') $roleBadge = 'danger';
                            if($user->role === 'auditor') $roleBadge = 'info';
                            if($user->role === 'principal') $roleBadge = 'success';
                            if($user->role === 'requester') $roleBadge = 'warning';
                        @endphp
                        <x-badge :type="$roleBadge" :text="ucfirst($user->role)" />
                    </td>
                    <td>{{ $user->department ?? 'N/A' }}</td>
                    <td>
                        <x-badge :type="$user->is_active ? 'success' : 'secondary'" :text="$user->is_active ? 'Active' : 'Inactive'" />
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
                                <li><a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('users.toggle', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item {{ $user->is_active ? 'text-danger' : 'text-success' }}">
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-secondary">No users found.</td>
                </tr>
                @endforelse
            </x-table>

            <x-slot name="footer">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-secondary">Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users</span>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            </x-slot>
        </x-card>
    </div>

    <!-- Roles Overview -->
    <div class="col-12">
        <x-card title="System Roles Overview" class="border-0 shadow-sm">
            <div class="row g-3">
                @foreach($roles_overview as $role_stat)
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-3 mb-3 p-3 border rounded">
                        <div class="icon-shape rounded bg-light text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ ucfirst($role_stat->role) }}</h6>
                            <small class="text-secondary">{{ $role_stat->count }} assigned users</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </x-card>
    </div>
</div>
@endsection

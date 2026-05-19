@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="row justify-content-center g-6">
    <div class="col-xl-8">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                </ol>
            </nav>
            <h2 class="mb-0">Edit User</h2>
            <p class="text-secondary small">Update user account information and role assignment.</p>
        </div>

        <x-card class="border-0 shadow-sm">
            <form action="{{ route('users.update', 1) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Personal Information -->
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold mb-3">Personal Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <x-input label="Full Name" name="name" placeholder="Enter full name" value="Ahmed Hassan" required error="{{ $errors->first('name') }}" />
                        </div>
                        <div class="col-md-6">
                            <x-input label="Email Address" name="email" type="email" placeholder="user@example.com" value="ahmed.hassan@isrms.com" required error="{{ $errors->first('email') }}" />
                        </div>
                    </div>
                </div>

                <!-- Role Assignment -->
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold mb-3">Role Assignment</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label fw-semibold small text-secondary">Select Role <span class="text-danger">*</span></label>
                                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="">Choose role...</option>
                                    <option value="admin">Admin</option>
                                    <option value="storekeeper" selected>Storekeeper</option>
                                    <option value="auditor">Internal Auditor</option>
                                    <option value="principal">Principal</option>
                                    <option value="requester">Requester (Department)</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Account Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active Account
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Security (Optional) -->
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold mb-3">Change Password (Optional)</h5>
                    <p class="small text-secondary mb-3">Leave blank to keep existing password</p>
                    <div class="row">
                        <div class="col-md-6">
                            <x-input label="New Password" name="password" type="password" placeholder="Leave blank to keep current password" error="{{ $errors->first('password') }}" />
                        </div>
                        <div class="col-md-6">
                            <x-input label="Confirm New Password" name="password_confirmation" type="password" placeholder="Confirm new password" error="{{ $errors->first('password_confirmation') }}" />
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('users.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection

                <!-- Role & Permissions -->
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold mb-3">Role Assignment</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label fw-semibold small text-secondary">Select Role</label>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="storekeeper" selected>Storekeeper</option>
                                    <option value="admin">Admin</option>
                                    <option value="auditor">Internal Auditor</option>
                                    <option value="principal">Principal</option>
                                    <option value="requester">Requester (Department)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="active" id="active" checked>
                                    <label class="form-check-label" for="active">
                                        Active Account
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Permissions</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="perms[]" value="view_inventory" id="perm1" checked>
                                    <label class="form-check-label" for="perm1">
                                        View Inventory
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="perms[]" value="create_sra" id="perm2" checked>
                                    <label class="form-check-label" for="perm2">
                                        Create SRA
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="perms[]" value="approve_requisitions" id="perm3">
                                    <label class="form-check-label" for="perm3">
                                        Approve Requisitions
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="perms[]" value="issue_items" id="perm4" checked>
                                    <label class="form-check-label" for="perm4">
                                        Issue Items
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="perms[]" value="view_reports" id="perm5" checked>
                                    <label class="form-check-label" for="perm5">
                                        View Reports
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="perms[]" value="manage_users" id="perm6">
                                    <label class="form-check-label" for="perm6">
                                        Manage Users
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Activity -->
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold mb-3">Account Activity</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Last Login</label>
                                <input type="text" class="form-control" value="Today, 2:45 PM" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Account Created</label>
                                <input type="text" class="form-control" value="March 15, 2026" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Reset -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Change Password</h5>
                    <p class="small text-secondary mb-3">Leave these fields empty if you don't want to change the password</p>
                    <div class="row">
                        <div class="col-md-6">
                            <x-input label="New Password" name="new_password" type="password" placeholder="Leave empty to keep current" />
                        </div>
                        <div class="col-md-6">
                            <x-input label="Confirm New Password" name="new_password_confirmation" type="password" placeholder="Leave empty to keep current" />
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between gap-2 mt-4">
                    <button type="button" class="btn btn-outline-danger" onclick="if(confirm('Delete this user?')) { document.getElementById('deleteForm').submit(); }">Delete User</button>
                    <div>
                        <a href="{{ route('users.index') }}" class="btn btn-ghost">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</div>

<form id="deleteForm" action="{{ route('users.destroy', 1) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

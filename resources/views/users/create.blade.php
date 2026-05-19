@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
<div class="row justify-content-center g-6">
    <div class="col-xl-8">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New User</li>
                </ol>
            </nav>
            <h2 class="mb-0">Add New User</h2>
            <p class="text-secondary small">Create a new system user account with assigned role and permissions.</p>
        </div>

        <x-card class="border-0 shadow-sm">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <!-- Personal Information -->
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold mb-3">Personal Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <x-input label="Full Name" name="name" placeholder="Enter full name" required error="{{ $errors->first('name') }}" />
                        </div>
                        <div class="col-md-6">
                            <x-input label="Email Address" name="email" type="email" placeholder="user@example.com" required error="{{ $errors->first('email') }}" />
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
                                    <option value="" selected disabled>Choose role...</option>
                                    <option value="admin">Admin</option>
                                    <option value="storekeeper">Storekeeper</option>
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

                <!-- Account Security -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Account Security</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <x-input label="Temporary Password" name="password" type="password" placeholder="Minimum 8 characters" required error="{{ $errors->first('password') }}" />
                            <small class="text-secondary">User will be required to change on first login</small>
                        </div>
                        <div class="col-md-6">
                            <x-input label="Confirm Password" name="password_confirmation" type="password" placeholder="Re-enter password" required error="{{ $errors->first('password_confirmation') }}" />
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('users.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', '403 - Unauthorized Access')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-6 text-center">
            <div class="mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-danger icon icon-tabler icons-tabler-outline icon-tabler-shield-lock">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />
                    <path d="M12 11m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M12 12l0 2.5" />
                </svg>
            </div>
            <h1 class="display-1 fw-bold text-danger">403</h1>
            <h2 class="h1 mb-3">Access Denied</h2>
            <p class="lead text-secondary mb-5">
                Sorry, you do not have permission to access this page. 
                If you believe this is an error, please contact your administrator.
            </p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route('dashboard') }}" class="btn btn-primary px-5 py-3">
                    <i class="ti ti-home me-2"></i> Return to Dashboard
                </a>
                <button onclick="window.history.back()" class="btn btn-outline-secondary px-5 py-3">
                    <i class="ti ti-arrow-left me-2"></i> Go Back
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

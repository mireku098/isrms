@extends('layouts.app')

@section('title', 'View User')

@section('content')
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">User Details</h5>
            </div>
            <div class="card-body">
                <p class="text-secondary">User ID: <strong>#USR001</strong></p>
                <p class="text-secondary">Name: <strong>John Doe</strong></p>
                <p class="text-secondary">Email: <strong>john@example.com</strong></p>
                <p class="text-secondary">Role: <strong>Storekeeper</strong></p>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Add Category')

@section('content')
<div class="row justify-content-center g-6">
    <div class="col-xl-6">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Category</li>
                </ol>
            </nav>
            <h2 class="mb-0">Add Category</h2>
            <p class="text-secondary small">Create a category for use in item creation and editing.</p>
        </div>

        <x-card class="border-0 shadow-sm">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <x-input label="Category Name" name="name" placeholder="e.g. Furniture" value="{{ old('name') }}" required error="{{ $errors->first('name') }}" />

                <div class="mt-4 border-top pt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('categories.index') }}" class="btn btn-ghost">Cancel</a>
                    <x-button type="submit">Create Category</x-button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection

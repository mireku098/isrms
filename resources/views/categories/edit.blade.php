@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="row justify-content-center g-6">
    <div class="col-xl-6">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Category</li>
                </ol>
            </nav>
            <h2 class="mb-0">Edit Category</h2>
            <p class="text-secondary small">Update category name used by linked items.</p>
        </div>

        <x-card class="border-0 shadow-sm">
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <x-input label="Category Name" name="name" placeholder="e.g. Furniture" value="{{ old('name', $category->name) }}" required error="{{ $errors->first('name') }}" />

                <div class="mt-4 border-top pt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('categories.index') }}" class="btn btn-ghost">Cancel</a>
                    <x-button type="submit">Update Category</x-button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection

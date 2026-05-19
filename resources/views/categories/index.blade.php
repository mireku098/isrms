@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="row g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Categories</h2>
                <p class="mb-0 text-secondary small">Manage item categories used in inventory forms.</p>
            </div>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">Add Category</a>
        </div>
    </div>

    <div class="col-12">
        <x-card title="Category List" class="border-0 shadow-sm">
            <x-table :headers="['Name', 'Items Using Category', 'Created At', 'Actions']">
                @forelse($categories as $category)
                    <tr>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td>{{ $category->items_count }}</td>
                        <td>{{ optional($category->created_at)->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-secondary">No categories found.</td>
                    </tr>
                @endforelse
            </x-table>

            <x-slot name="footer">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-secondary">
                        Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} categories
                    </span>
                    {{ $categories->links() }}
                </div>
            </x-slot>
        </x-card>
    </div>
</div>
@endsection

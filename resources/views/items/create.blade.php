@extends('layouts.app')

@section('title', 'Add New Item')

@section('content')
<div class="row justify-content-center g-6">
    <div class="col-xl-8">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('items.index') }}">Inventory</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Item</li>
                </ol>
            </nav>
            <h2 class="mb-0">Add New Item</h2>
            <p class="text-secondary small">Register a new item in the store system.</p>
        </div>

        <x-card class="border-0 shadow-sm">
            <form action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <x-input label="Item Name" name="name" placeholder="Enter item name" required error="{{ $errors->first('name') }}" value="{{ old('name') }}" />
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category" class="form-label fw-semibold small text-secondary">Category</label>
                            <select name="category" id="category" class="form-select @error('category') is-invalid @enderror">
                                <option value="" {{ old('category') ? '' : 'selected' }} disabled>Select Category</option>
                                @foreach(($categories ?? collect()) as $category)
                                    <option value="{{ $category->name }}" {{ old('category') === $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-secondary">
                                Need a new one?
                                <a href="{{ route('categories.create') }}" class="text-primary">Create category</a>
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="unit" class="form-label fw-semibold small text-secondary">Unit of Measurement</label>
                            <select name="unit" id="unit" class="form-select @error('unit') is-invalid @enderror">
                                <option value="" selected disabled>Select Unit</option>
                                <option value="Pieces" {{ old('unit') === 'Pieces' ? 'selected' : '' }}>Pieces</option>
                                <option value="Bags" {{ old('unit') === 'Bags' ? 'selected' : '' }}>Bags</option>
                                <option value="Boxes" {{ old('unit') === 'Boxes' ? 'selected' : '' }}>Boxes</option>
                                <option value="Packets" {{ old('unit') === 'Packets' ? 'selected' : '' }}>Packets</option>
                                <option value="Reams" {{ old('unit') === 'Reams' ? 'selected' : '' }}>Reams</option>
                            </select>
                            @error('unit')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <x-input label="Minimum Stock Level" name="min_stock" type="number" placeholder="Alert threshold" value="{{ old('min_stock', 0) }}" min="0" required error="{{ $errors->first('min_stock') }}" />
                    </div>
                    <div class="col-md-6">
                        <x-input label="Maximum Stock Level" name="max_stock" type="number" placeholder="Capacity limit" value="{{ old('max_stock', 0) }}" min="0" required error="{{ $errors->first('max_stock') }}" />
                    </div>
                    <div class="col-md-6">
                        <x-input label="Opening Stock" name="opening_stock" type="number" placeholder="Initial stock quantity" value="{{ old('opening_stock', 0) }}" min="0" error="{{ $errors->first('opening_stock') }}" />
                        <small class="text-secondary">Set this to avoid immediate Out of Stock status after creating the item.</small>
                    </div>
                    <div class="col-12 mt-4 border-top pt-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('items.index') }}" class="btn btn-ghost">Cancel</a>
                            <x-button type="submit">Create Item</x-button>
                        </div>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection

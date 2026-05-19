@extends('layouts.app')

@section('title', 'Edit Item')

@section('content')
<div class="row justify-content-center g-6">
    <div class="col-xl-8">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('items.index') }}">Inventory</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Item</li>
                </ol>
            </nav>
            <h2 class="mb-0">Edit Item</h2>
            <p class="text-secondary small">Update item details and stock thresholds.</p>
        </div>

        <x-card class="border-0 shadow-sm">
            <form action="{{ route('items.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <x-input label="Item Name" name="name" placeholder="Enter item name" value="{{ old('name', $item->name) }}" required error="{{ $errors->first('name') }}" />
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category" class="form-label fw-semibold small text-secondary">Category</label>
                            <select name="category" id="category" class="form-select @error('category') is-invalid @enderror">
                                <option value="" {{ old('category', $item->category) ? '' : 'selected' }} disabled>Select Category</option>
                                @foreach(($categories ?? collect()) as $category)
                                    <option value="{{ $category->name }}" {{ old('category', $item->category) === $category->name ? 'selected' : '' }}>
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
                                <option value="" {{ old('unit', $item->unit) ? '' : 'selected' }} disabled>Select Unit</option>
                                <option value="Pieces" {{ old('unit', $item->unit) === 'Pieces' ? 'selected' : '' }}>Pieces</option>
                                <option value="Bags" {{ old('unit', $item->unit) === 'Bags' ? 'selected' : '' }}>Bags</option>
                                <option value="Boxes" {{ old('unit', $item->unit) === 'Boxes' ? 'selected' : '' }}>Boxes</option>
                                <option value="Packets" {{ old('unit', $item->unit) === 'Packets' ? 'selected' : '' }}>Packets</option>
                                <option value="Reams" {{ old('unit', $item->unit) === 'Reams' ? 'selected' : '' }}>Reams</option>
                            </select>
                            @error('unit')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <x-input label="Current Stock" name="current_stock" type="number" value="{{ old('current_stock', $currentStock) }}" min="0" error="{{ $errors->first('current_stock') }}" />
                        <small class="text-secondary">Last updated: {{ $lastStockUpdate ? $lastStockUpdate->format('M d, Y h:i A') : 'No stock movement yet' }}</small>
                    </div>
                    <div class="col-md-6">
                        <x-input label="Minimum Stock Level" name="min_stock" type="number" placeholder="Alert threshold" value="{{ old('min_stock', $item->min_stock) }}" min="0" required error="{{ $errors->first('min_stock') }}" />
                    </div>
                    <div class="col-md-6">
                        <x-input label="Maximum Stock Level" name="max_stock" type="number" placeholder="Capacity limit" value="{{ old('max_stock', $item->max_stock) }}" min="0" required error="{{ $errors->first('max_stock') }}" />
                    </div>
                    <div class="col-12 mt-4 border-top pt-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('items.index') }}" class="btn btn-ghost">Cancel</a>
                            <x-button type="submit">Update Item</x-button>
                        </div>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection

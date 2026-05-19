@extends('layouts.app')

@section('title', 'New SRA Receipt')

@section('content')
<div class="row justify-content-center g-6">
    <div class="col-xl-10">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('sra.index') }}">SRA Receipts</a></li>
                    <li class="breadcrumb-item active" aria-current="page">New SRA Receipt</li>
                </ol>
            </nav>
            <h2 class="mb-0">New Stores Received Advice (SRA)</h2>
            <p class="text-secondary small">Record items delivered by a supplier.</p>
        </div>

        <form action="{{ route('sra.store') }}" method="POST" id="sraForm">
            @csrf
            <div class="row g-6">
                <!-- Supplier Info -->
                <div class="col-md-4">
                    <x-card title="Supplier Details" class="border-0 shadow-sm">
                        <x-input label="Supplier Name" name="supplier_name" placeholder="e.g. Global Supplies Ltd" required error="{{ $errors->first('supplier_name') }}" value="{{ old('supplier_name') }}" />
                        <x-input label="Bill / Invoice Number" name="bill_number" placeholder="INV-00123" required error="{{ $errors->first('bill_number') }}" value="{{ old('bill_number') }}" />
                        <x-input label="Waybill Number" name="waybill_number" placeholder="WB-9923" required error="{{ $errors->first('waybill_number') }}" value="{{ old('waybill_number') }}" />
                        <x-input label="Date of Delivery" name="delivery_date" type="date" value="{{ old('delivery_date', date('Y-m-d')) }}" required error="{{ $errors->first('delivery_date') }}" />
                    </x-card>
                </div>

                <!-- Items to Receive -->
                <div class="col-md-8">
                    <x-card title="Received Items" class="border-0 shadow-sm">
                        <div id="items-container">
                            <div class="row g-3 item-row mb-3 border-bottom pb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-secondary">Select Item</label>
                                    <select class="form-select @error('items.0.item_id') is-invalid @enderror" name="items[0][item_id]" required>
                                        <option value="" selected disabled>Choose Item...</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" {{ old('items.0.item_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }} ({{ $item->category ?? 'Uncategorized' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('items.0.item_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">Quantity Received</label>
                                    <input type="number" class="form-control @error('items.0.quantity') is-invalid @enderror" name="items[0][quantity]" min="1" value="{{ old('items.0.quantity') }}" required>
                                    @error('items.0.quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger btn-icon rounded-circle" disabled title="Cannot remove first item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-item-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                Add More Item
                            </button>
                        </div>
                        
                        <div class="mt-4 border-top pt-4 text-end">
                            <a href="{{ route('sra.index') }}" class="btn btn-ghost">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">Submit SRA for Approval</button>
                        </div>
                    </x-card>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dynamic items helper
    initDynamicItems('items-container', 'add-item-btn', 1);
    
    // Auto-save form to localStorage
    initAutoSave('sraForm', 'sra_draft');
    
    // Form submission handler
    const form = document.getElementById('sraForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        if (!validateForm('sraForm')) {
            e.preventDefault();
            showToast('Validation Error', 'Please fill in all required fields', 'danger');
        } else {
            showLoadingState(submitBtn);
            form.addEventListener('submit', function() {
                localStorage.removeItem('sra_draft');
            });
        }
    });
});
</script>
@endpush
@endsection

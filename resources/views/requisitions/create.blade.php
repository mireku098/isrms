@extends('layouts.app')

@section('title', 'New Requisition')

@section('content')
<div class="row justify-content-center g-6">
    <div class="col-xl-10">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('requisitions.index') }}">Requisitions</a></li>
                    <li class="breadcrumb-item active" aria-current="page">New Requisition</li>
                </ol>
            </nav>
            <h2 class="mb-0">New Requisition</h2>
            <p class="text-secondary small">Request items from the store. Stock levels update in real-time.</p>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer" class="mb-3"></div>

        <form action="{{ route('requisitions.store') }}" method="POST" id="requisitionForm">
            @csrf
            <div class="row g-6">
                <!-- Request Info -->
                <div class="col-md-4">
                    <x-card title="Request Details" class="border-0 shadow-sm">
                        <x-input label="Department" name="department" value="{{ old('department', auth()->user()->department) }}" placeholder="Enter department name" required />
                        <x-input label="Requested By" name="requested_by" value="{{ auth()->user()->name ?? 'Current User' }}" readonly />
                        <x-input label="Date" name="request_date" type="date" value="{{ old('request_date', date('Y-m-d')) }}" required />
                        <div class="mb-3">
                            <label for="purpose" class="form-label fw-semibold small text-secondary">Purpose / Remarks</label>
                            <textarea name="purpose" id="purpose" class="form-control @error('purpose') is-invalid @enderror" rows="3" placeholder="e.g. For new staff onboarding" required>{{ old('purpose') }}</textarea>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </x-card>
                </div>

                <!-- Items to Request -->
                <div class="col-md-8">
                    <x-card title="Requested Items (Live Inventory)" class="border-0 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="spinner-border spinner-border-sm" id="itemsLoadingSpinner" style="display: none;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <small class="text-muted">Stock refreshes every 5 seconds</small>
                        </div>

                        <div id="items-container">
                            <div class="row g-3 item-row mb-3 border-bottom pb-3" data-index="0">
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold small text-secondary">Select Item <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control item-search" placeholder="Search items..." data-index="0">
                                    <select class="form-select @error('items.0.item_id') is-invalid @enderror" name="items[0][item_id]" data-stock-display data-index="0" style="margin-top: 0.5rem;">
                                        <option value="" selected disabled>Choose Item...</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" data-stock="0" data-name="{{ $item->name }}" {{ old('items.0.item_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="item-stock d-block mt-2"></div>
                                    @error('items.0.item_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">Quantity Requested <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control qty-input @error('items.0.quantity_requested') is-invalid @enderror" name="items[0][quantity_requested]" min="1" value="{{ old('items.0.quantity_requested', 1) }}" required>
                                    @error('items.0.quantity_requested')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-item-btn" disabled title="Cannot remove first item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        Remove
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
                            <a href="{{ route('requisitions.index') }}" class="btn btn-ghost">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">Submit Request</button>
                        </div>
                    </x-card>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let itemsData = {};
    let realtimeUpdateInterval = null;
    let itemIndex = 1;

    document.addEventListener('DOMContentLoaded', function() {
        loadAllItemsData();
        setupRealtimeUpdates();
        setupItemHandlers();
    });

    /**
     * Load all items with stock data
     */
    async function loadAllItemsData() {
        try {
            document.getElementById('itemsLoadingSpinner').style.display = 'inline-block';

            const response = await fetch('/api/requisitions/items/list');
            if (!response.ok) throw new Error('Failed to load items');

            const items = await response.json();
            itemsData = items.reduce((acc, item) => {
                acc[item.id] = item;
                return acc;
            }, {});

            // Update all selects with items
            updateAllSelects();
            updateAllStockDisplays();
            showAlert('success', 'Item inventory loaded successfully');

        } catch (error) {
            console.error('Error:', error);
            showAlert('danger', 'Error loading items: ' + error.message);
        } finally {
            document.getElementById('itemsLoadingSpinner').style.display = 'none';
        }
    }

    /**
     * Update all select dropdowns with items
     */
    function updateAllSelects() {
        const selects = document.querySelectorAll('[data-stock-display]');
        selects.forEach(select => {
            const currentValue = select.value;
            const currentIndex = select.dataset.index;
            
            // Clear existing options except first
            while (select.options.length > 1) {
                select.remove(1);
            }

            // Add all items
            Object.values(itemsData).forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = `${item.name} (${item.category})`;
                option.dataset.stock = item.current_stock;
                option.dataset.name = item.name;
                select.appendChild(option);
            });

            // Restore previous value
            if (currentValue) {
                select.value = currentValue;
            }
        });
    }

    /**
     * Update all stock displays
     */
    function updateAllStockDisplays() {
        const selects = document.querySelectorAll('[data-stock-display]');
        selects.forEach(select => {
            if (select.value) {
                updateStockDisplay(select);
            }
        });
    }

    /**
     * Update stock display for single item
     */
    function updateStockDisplay(selectElement) {
        const itemId = selectElement.value;
        const row = selectElement.closest('.item-row');
        const display = row.querySelector('.item-stock');
        
        if (!itemId || !itemsData[itemId]) {
            display.innerHTML = '';
            return;
        }

        const item = itemsData[itemId];
        const badge = createStockBadge(item);
        const lastUpdate = `<small class="text-muted ms-2">Updated ${item.last_updated}</small>`;
        
        display.innerHTML = `<div>${badge}${lastUpdate}</div>`;
        
        // Update max quantity based on stock
        const qtyInput = row.querySelector('.qty-input');
        if (qtyInput) {
            qtyInput.dataset.currentStock = item.current_stock;
        }
    }

    /**
     * Create stock badge HTML
     */
    function createStockBadge(item) {
        let badgeClass = 'bg-success';
        let statusText = `${item.current_stock} in stock`;

        if (item.current_stock === 0) {
            badgeClass = 'bg-danger';
            statusText = 'Out of Stock';
        } else if (item.stock_status === 'low_stock') {
            badgeClass = 'bg-warning text-dark';
            statusText = `${item.current_stock} Low Stock`;
        }

        return `<span class="badge ${badgeClass}">${statusText}</span>`;
    }

    /**
     * Setup item handlers (search, remove, etc)
     */
    function setupItemHandlers() {
        const container = document.getElementById('items-container');
        const addBtn = document.getElementById('add-item-btn');

        // Add Item
        addBtn.addEventListener('click', addNewItemRow);

        // Existing item handlers
        container.querySelectorAll('.item-row').forEach(row => {
            setupRowHandlers(row);
        });
    }

    /**
     * Setup handlers for a row
     */
    function setupRowHandlers(row) {
        const select = row.querySelector('[data-stock-display]');
        const searchInput = row.querySelector('.item-search');
        const removeBtn = row.querySelector('.remove-item-btn');
        const qtyInput = row.querySelector('.qty-input');

        if (select) {
            select.addEventListener('change', function() {
                updateStockDisplay(this);
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                filterSelectOptions(select, this.value);
            });
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                if (row.closest('#items-container').querySelectorAll('.item-row').length > 1) {
                    row.remove();
                }
            });
        }

        if (qtyInput) {
            qtyInput.addEventListener('change', function() {
                const stock = parseInt(this.dataset.currentStock) || 0;
                if (parseInt(this.value) > stock) {
                    showAlert('warning', `Only ${stock} units available. Adjusted quantity.`);
                    this.value = stock;
                }
            });
        }
    }

    /**
     * Filter select options by search
     */
    function filterSelectOptions(select, searchTerm) {
        const options = select.querySelectorAll('option');
        const searchLower = searchTerm.toLowerCase();
        let hasVisible = false;

        options.forEach(option => {
            if (option.value === '') return;
            
            const visible = option.textContent.toLowerCase().includes(searchLower);
            option.hidden = !visible;
            if (visible) hasVisible = true;
        });

        // If search matches, select first visible
        if (hasVisible && searchTerm) {
            const firstVisible = Array.from(options).find(o => !o.hidden && o.value !== '');
            if (firstVisible) select.value = firstVisible.value;
            updateStockDisplay(select);
        }
    }

    /**
     * Add new item row
     */
    function addNewItemRow() {
        const container = document.getElementById('items-container');
        const firstRow = container.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        
        newRow.setAttribute('data-index', itemIndex);
        
        // Reset form fields
        const select = newRow.querySelector('select');
        select.name = `items[${itemIndex}][item_id]`;
        select.value = '';
        select.dataset.index = itemIndex;
        
        const search = newRow.querySelector('.item-search');
        search.value = '';
        search.dataset.index = itemIndex;
        
        const qty = newRow.querySelector('input[type="number"]');
        qty.name = `items[${itemIndex}][quantity_requested]`;
        qty.value = '1';
        
        const removeBtn = newRow.querySelector('.remove-item-btn');
        removeBtn.disabled = false;
        
        newRow.querySelector('.item-stock').innerHTML = '';
        
        container.appendChild(newRow);
        setupRowHandlers(newRow);
        itemIndex++;
    }

    /**
     * Setup real-time updates
     */
    function setupRealtimeUpdates() {
        realtimeUpdateInterval = setInterval(async () => {
            try {
                const response = await fetch('/api/requisitions/items/list');
                if (!response.ok) return;

                const items = await response.json();
                const newItemsData = items.reduce((acc, item) => {
                    acc[item.id] = item;
                    return acc;
                }, {});

                // Check for changes
                let hasChanges = false;
                Object.keys(newItemsData).forEach(id => {
                    if (!itemsData[id] || itemsData[id].current_stock !== newItemsData[id].current_stock) {
                        hasChanges = true;
                    }
                });

                if (hasChanges) {
                    itemsData = newItemsData;
                    updateAllStockDisplays();
                }

            } catch (error) {
                console.error('Error updating stock:', error);
            }
        }, 5000); // Every 5 seconds
    }

    /**
     * Show alert
     */
    function showAlert(type, message) {
        const container = document.getElementById('alertContainer');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.role = 'alert';
        alert.innerHTML = `
            ${escapeHtml(message)}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        container.innerHTML = '';
        container.appendChild(alert);

        if (type === 'success' || type === 'info') {
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 5000);
        }
    }

    /**
     * Escape HTML
     */
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    /**
     * Form submission
     */
    document.getElementById('requisitionForm').addEventListener('submit', function(e) {
        let hasValidItems = false;
        
        document.querySelectorAll('[name^="items"][name$="[item_id]"]').forEach(select => {
            if (select.value) {
                const row = select.closest('.item-row');
                const qty = row.querySelector('[name$="[quantity_requested]"]').value;
                if (qty > 0) hasValidItems = true;
            }
        });

        if (!hasValidItems) {
            e.preventDefault();
            showAlert('danger', 'Please select at least one item to request');
        }
    });

    // Cleanup
    window.addEventListener('beforeunload', function() {
        if (realtimeUpdateInterval) clearInterval(realtimeUpdateInterval);
    });
</script>

<style>
    .item-search {
        font-size: 0.875rem;
    }
    
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
    }

    .item-stock {
        display: flex;
        align-items: center;
    }
</style>
@endsection

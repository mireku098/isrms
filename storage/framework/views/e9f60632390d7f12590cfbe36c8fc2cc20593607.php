

<?php $__env->startSection('title', 'Issue Items from Approved Requisitions'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center g-6">
    <div class="col-xl-10">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('issues.index')); ?>">Issues</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Issue Items</li>
                </ol>
            </nav>
            <h2 class="mb-0">Issue Items from Requisition</h2>
            <p class="text-secondary small">Issue approved requisitioned items to the requesting department. Data updates in real-time.</p>
        </div>

        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'border-0 shadow-sm']); ?>
            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <form action="<?php echo e(route('issues.store')); ?>" method="POST" id="issueForm">
                <?php echo csrf_field(); ?>
                
                <!-- Select Requisition -->
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold mb-3">Select Requisition to Issue</h5>
                    <div class="mb-3">
                        <label for="requisition_id" class="form-label fw-semibold small text-secondary">
                            Approved Requisition <span class="text-danger">*</span>
                        </label>
                        <select name="requisition_id" id="requisition_id" class="form-select <?php $__errorArgs = ['requisition_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="" selected disabled>Choose a requisition...</option>
                            <?php $__currentLoopData = $requisitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($req['id']); ?>" data-items-count="<?php echo e($req['items_count']); ?>" <?php echo e(old('requisition_id') == $req['id'] ? 'selected' : ''); ?>>
                                    <?php echo e($req['requisition_number']); ?> - <?php echo e($req['department']); ?> (<?php echo e($req['items_count']); ?> items)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['requisition_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Requisition Details (Dynamic) -->
                <div class="mb-4 pb-4 border-bottom" id="requisitionDetailsContainer" style="display: none;">
                    <h5 class="fw-bold mb-3">Requisition Details</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Requested By</label>
                                <input type="text" id="requester_name" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Department</label>
                                <input type="text" id="department" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Approval Date</label>
                                <input type="text" id="approval_date" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Approved By</label>
                                <input type="text" id="approved_by" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items to Issue (Dynamic) -->
                <div class="mb-4 pb-4 border-bottom" id="itemsContainer" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Items to Issue</h5>
                        <div class="spinner-border spinner-border-sm" id="itemsLoadingSpinner" style="display: none;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Requested Qty</th>
                                    <th>Current Stock <span class="small text-muted">(Live)</span></th>
                                    <th>Issue Qty <span class="text-danger">*</span></th>
                                    <th>Unit</th>
                                    <th>Stock Status</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Dynamically populated -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Receiver Information -->
                <div class="mb-4 pb-4 border-bottom" id="receiverContainer" style="display: none;">
                    <h5 class="fw-bold mb-3">Receiver Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="receiver_name" class="form-label fw-semibold small text-secondary">
                                    Receiver/Collector Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="receiver_name" id="receiver_name" class="form-control <?php $__errorArgs = ['receiver_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Name of person receiving items" value="<?php echo e(old('receiver_name')); ?>">
                                <?php $__errorArgs = ['receiver_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="receiver_signature" class="form-label fw-semibold small text-secondary">
                                    Digital Signature/Initial <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="receiver_signature" id="receiver_signature" class="form-control <?php $__errorArgs = ['receiver_signature'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Type initials or signature" value="<?php echo e(old('receiver_signature')); ?>">
                                <?php $__errorArgs = ['receiver_signature'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="remarks" class="form-label fw-semibold small text-secondary">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="2" placeholder="Any additional notes..."><?php echo e(old('remarks')); ?></textarea>
                                <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="<?php echo e(route('issues.index')); ?>" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Issue Items</button>
                </div>
            </form>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>
</div>

<!-- Real-time Data Loading Script -->
<script>
    // Store current requisition ID
    let currentRequisitionId = null;
    let itemsData = {};
    let realtimeUpdateInterval = null;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('requisition_id');
        
        // Load previous selection if exists
        if (select.value) {
            currentRequisitionId = select.value;
            loadRequisitionData(select.value);
        }

        select.addEventListener('change', function() {
            if (this.value) {
                currentRequisitionId = this.value;
                loadRequisitionData(this.value);
            } else {
                resetForm();
            }
        });

        // Real-time inventory updates
        setupRealtimeUpdates();
    });

    /**
     * Load requisition data from API
     */
    async function loadRequisitionData(requisitionId) {
        try {
            // Show loading spinner
            document.getElementById('itemsLoadingSpinner').style.display = 'inline-block';

            // Fetch requisition details
            const detailsResponse = await fetch(`/api/requisitions/${requisitionId}/details`);
            if (!detailsResponse.ok) throw new Error('Failed to load requisition details');
            
            const details = await detailsResponse.json();

            // Fetch items data
            const itemsResponse = await fetch(`/api/requisitions/${requisitionId}/items`);
            if (!itemsResponse.ok) throw new Error('Failed to load items');
            
            const items = await itemsResponse.json();

            // Update form with data
            updateRequisitionDetails(details);
            updateItemsTable(items);

            // Show containers
            document.getElementById('requisitionDetailsContainer').style.display = 'block';
            document.getElementById('itemsContainer').style.display = 'block';
            document.getElementById('receiverContainer').style.display = 'block';
            document.getElementById('submitBtn').disabled = false;

            // Store items data for reference
            itemsData = items.reduce((acc, item) => {
                acc[item.item_id] = item;
                return acc;
            }, {});

            showAlert('success', 'Requisition data loaded successfully. Current inventory stock is displayed.');

        } catch (error) {
            console.error('Error:', error);
            showAlert('danger', 'Error loading requisition data: ' + error.message);
            resetForm();
        } finally {
            document.getElementById('itemsLoadingSpinner').style.display = 'none';
        }
    }

    /**
     * Update requisition details in form
     */
    function updateRequisitionDetails(details) {
        document.getElementById('requester_name').value = details.requested_by || 'N/A';
        document.getElementById('department').value = details.department || 'N/A';
        document.getElementById('approval_date').value = details.approval_date || 'N/A';
        document.getElementById('approved_by').value = details.approval_by || 'N/A';
    }

    /**
     * Update items table with dynamic data
     */
    function updateItemsTable(items) {
        const tbody = document.getElementById('itemsTableBody');
        tbody.innerHTML = '';

        if (items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No items in this requisition</td></tr>';
            return;
        }

        items.forEach((item, index) => {
            const stockStatusBadge = getStockStatusBadge(item.stock_status);
            const canIssueClass = item.can_issue ? '' : 'table-warning';
            
            const row = `
                <tr class="${canIssueClass}" data-item-id="${item.item_id}">
                    <td>
                        <div class="fw-semibold">${escapeHtml(item.item_name)}</div>
                        <div class="small text-secondary">${escapeHtml(item.item_code)}</div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info">${item.requested_quantity}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge ${getStockBadgeClass(item.stock_status)}" data-stock-value="${item.current_stock}" data-item-id="${item.item_id}">
                            ${item.current_stock}
                        </span>
                    </td>
                    <td>
                        <input 
                            type="hidden" 
                            name="items[${index}][item_id]"
                            value="${item.item_id}"
                        >
                        <input 
                            type="number" 
                            name="items[${index}][quantity_issued]"
                            class="form-control form-control-sm quantity-input"
                            min="0" 
                            max="${item.requested_quantity}"
                            value="${item.requested_quantity}"
                            data-item-id="${item.item_id}"
                            data-requested="${item.requested_quantity}"
                            data-current-stock="${item.current_stock}"
                        >
                    </td>
                    <td>${escapeHtml(item.unit)}</td>
                    <td>
                        ${stockStatusBadge}
                        ${item.current_stock < item.requested_quantity ? '<br><small class="text-danger">Insufficient stock</small>' : ''}
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });

        // Add validation listeners
        addQuantityValidation();
    }

    /**
     * Get stock status badge
     */
    function getStockStatusBadge(status) {
        const badges = {
            'in_stock': '<span class="badge bg-success">In Stock</span>',
            'low_stock': '<span class="badge bg-warning">Low Stock</span>',
            'out_of_stock': '<span class="badge bg-danger">Out of Stock</span>'
        };
        return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Get stock badge CSS class
     */
    function getStockBadgeClass(status) {
        const classes = {
            'in_stock': 'bg-success',
            'low_stock': 'bg-warning text-dark',
            'out_of_stock': 'bg-danger'
        };
        return classes[status] || 'bg-secondary';
    }

    /**
     * Add quantity input validation
     */
    function addQuantityValidation() {
        const inputs = document.querySelectorAll('.quantity-input');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                const requested = parseInt(this.dataset.requested);
                const currentStock = parseInt(this.dataset.currentStock);
                const issued = parseInt(this.value) || 0;

                if (issued > requested) {
                    this.value = requested;
                    showAlert('warning', 'Cannot issue more than requested amount');
                }
                if (issued > currentStock) {
                    showAlert('warning', `Limited stock available. Only ${currentStock} units in inventory.`);
                }
            });
        });
    }

    /**
     * Setup real-time inventory updates
     */
    function setupRealtimeUpdates() {
        // Update inventory every 5 seconds if a requisition is selected
        realtimeUpdateInterval = setInterval(async function() {
            if (!currentRequisitionId) return;

            try {
                const response = await fetch(`/api/requisitions/${currentRequisitionId}/items`);
                if (!response.ok) return;

                const items = await response.json();
                
                // Update stock badges and validate quantities
                items.forEach(item => {
                    const badge = document.querySelector(`[data-stock-value][data-item-id="${item.item_id}"]`);
                    if (badge) {
                        const oldValue = parseInt(badge.dataset.stockValue);
                        
                        // Only update if stock changed
                        if (oldValue !== item.current_stock) {
                            badge.textContent = item.current_stock;
                            badge.dataset.stockValue = item.current_stock;
                            badge.className = `badge ${getStockBadgeClass(item.stock_status)}`;
                            
                            // Show notification
                            if (item.current_stock < oldValue) {
                                showAlert('info', `Stock updated for ${item.item_name}: ${item.current_stock} units available (was ${oldValue})`);
                            } else if (item.current_stock > oldValue) {
                                showAlert('info', `Stock replenished for ${item.item_name}: ${item.current_stock} units now available`);
                            }

                            // Update max quantity
                            const input = document.querySelector(`input[data-item-id="${item.item_id}"][type="number"]`);
                            if (input) {
                                input.dataset.currentStock = item.current_stock;
                            }
                        }
                    }
                });

            } catch (error) {
                console.error('Error updating inventory:', error);
            }
        }, 5000); // Update every 5 seconds
    }

    /**
     * Show alert message
     */
    function showAlert(type, message) {
        const alertContainer = document.getElementById('alertContainer');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.role = 'alert';
        alert.innerHTML = `
            ${escapeHtml(message)}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        alertContainer.innerHTML = '';
        alertContainer.appendChild(alert);

        // Auto-dismiss success and info alerts after 5 seconds
        if (type === 'success' || type === 'info') {
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }
    }

    /**
     * Escape HTML to prevent XSS
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
     * Reset form to initial state
     */
    function resetForm() {
        document.getElementById('requisitionDetailsContainer').style.display = 'none';
        document.getElementById('itemsContainer').style.display = 'none';
        document.getElementById('receiverContainer').style.display = 'none';
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('itemsTableBody').innerHTML = '';
        document.getElementById('alertContainer').innerHTML = '';
        currentRequisitionId = null;
        itemsData = {};
    }

    /**
     * Form submission validation
     */
    document.getElementById('issueForm').addEventListener('submit', function(e) {
        let hasValidItems = false;
        
        const inputs = document.querySelectorAll('.quantity-input');
        inputs.forEach(input => {
            const issued = parseInt(input.value) || 0;
            if (issued > 0) {
                hasValidItems = true;
            }
        });

        if (!hasValidItems) {
            e.preventDefault();
            showAlert('danger', 'Please specify at least one item to issue');
        }
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (realtimeUpdateInterval) {
            clearInterval(realtimeUpdateInterval);
        }
    });
</script>

<style>
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
    }
    
    .table-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }

    .badge[data-stock-value] {
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }

    #alertContainer {
        margin-bottom: 1rem;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp74\htdocs\store_management\resources\views/issues/create.blade.php ENDPATH**/ ?>
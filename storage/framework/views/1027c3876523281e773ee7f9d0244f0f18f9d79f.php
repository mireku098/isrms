

<?php $__env->startSection('title', 'View Item'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Item Details</h3>
            <div class="gap-2 d-flex">
                <a href="<?php echo e(route('items.edit', $item)); ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="<?php echo e(route('items.index')); ?>" class="btn btn-sm btn-outline-secondary">Back</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <p class="text-secondary mb-2">Item ID</p>
                        <p class="fs-5 fw-semibold mb-4">#ITEM<?php echo e(str_pad($item->id, 3, '0', STR_PAD_LEFT)); ?></p>

                        <p class="text-secondary mb-2">Name</p>
                        <p class="fs-5 fw-semibold mb-4"><?php echo e($item->name); ?></p>

                        <p class="text-secondary mb-2">Category</p>
                        <p class="fs-5 fw-semibold mb-4">
                            <?php echo e($item->categoryRelation ? $item->categoryRelation->name : ($item->category ?? 'Uncategorized')); ?>

                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-secondary mb-2">Unit</p>
                        <p class="fs-5 fw-semibold mb-4"><?php echo e($item->unit ?? 'N/A'); ?></p>

                        <p class="text-secondary mb-2">Current Stock</p>
                        <div class="mb-4">
                            <span class="fs-5 fw-semibold"><?php echo e($stock); ?></span>
                            <span class="text-secondary">units</span>
                            <?php if($item->isLowStock()): ?>
                                <span class="badge bg-warning ms-2">Low Stock</span>
                            <?php elseif($item->isOverStock()): ?>
                                <span class="badge bg-info ms-2">Over Stock</span>
                            <?php else: ?>
                                <span class="badge bg-success ms-2">In Stock</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-4">
                    <div class="col-md-3">
                        <p class="text-secondary mb-2 small">Minimum Stock Level</p>
                        <p class="fs-6 fw-semibold"><?php echo e($item->min_stock); ?></p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-secondary mb-2 small">Maximum Stock Level</p>
                        <p class="fs-6 fw-semibold"><?php echo e($item->max_stock); ?></p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-secondary mb-2 small">Created</p>
                        <p class="fs-6 fw-semibold"><?php echo e($item->created_at->format('M j, Y')); ?></p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-secondary mb-2 small">Last Updated</p>
                        <p class="fs-6 fw-semibold"><?php echo e($item->updated_at->format('M j, Y')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Transaction History -->
<div class="row mt-6">
    <div class="col-12">
        <h5 class="fw-bold mb-4">Stock Transaction History</h5>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Balance</th>
                            <th>Reference</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $ledger; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($entry->created_at->format('M j, Y H:i')); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($entry->transaction_type === 'RECEIVE' ? 'success' : 'danger'); ?>">
                                    <?php echo e($entry->transaction_type); ?>

                                </span>
                            </td>
                            <td><?php echo e($entry->quantity); ?></td>
                            <td>
                                <strong><?php echo e($entry->balance_after); ?></strong>
                            </td>
                            <td>
                                <?php if($entry->reference_type && $entry->reference_id): ?>
                                    <?php echo e($entry->reference_type); ?> #<?php echo e($entry->reference_id); ?>

                                <?php else: ?>
                                    <small class="text-secondary">-</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-secondary">No transactions found for this item.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if($ledger->hasPages()): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($ledger->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp74\htdocs\store_management\resources\views/items/show.blade.php ENDPATH**/ ?>
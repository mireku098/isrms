

<?php $__env->startSection('title', 'Inventory Ledger (Telecard)'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Inventory Ledger (Telecard)</h2>
                <p class="mb-0 text-secondary small">Real-time digital record of all inventory transactions for each item.</p>
            </div>
            <button class="btn btn-primary" onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print Ledger
            </button>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="col-12">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'border-0 shadow-sm']); ?>
            <form action="<?php echo e(route('ledger.index')); ?>" method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-secondary">Select Item</label>
                    <select name="item_id" class="form-select">
                        <option value="">All Items</option>
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->id); ?>" <?php echo e(request('item_id') == $item->id ? 'selected' : ''); ?>>
                                <?php echo e($item->name); ?> (<?php echo e($item->category ?? 'Uncategorized'); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-secondary">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="<?php echo e(request('from_date')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-secondary">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="<?php echo e(request('to_date')); ?>">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-sm">Apply Filter</button>
                    <a href="<?php echo e(route('ledger.index')); ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>

    <?php if($selectedItem): ?>
    <!-- Item Summary -->
    <div class="col-12">
        <div class="row g-3 mb-4">
            <div class="col-xl-4 col-md-6">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['class' => 'border-0 shadow-sm h-100 bg-primary-subtle']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'border-0 shadow-sm h-100 bg-primary-subtle']); ?>
                    <h6 class="fw-semibold text-primary mb-1">Item Name</h6>
                    <div class="fs-5 fw-bold"><?php echo e($selectedItem->name); ?></div>
                    <small class="text-secondary">ID: ITEM-<?php echo e(str_pad($selectedItem->id, 3, '0', STR_PAD_LEFT)); ?></small>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
            </div>
            <div class="col-xl-4 col-md-6">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['class' => 'border-0 shadow-sm h-100']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'border-0 shadow-sm h-100']); ?>
                    <h6 class="fw-semibold mb-1">Total Receipts</h6>
                    <div class="fs-5 fw-bold text-success">+<?php echo e($stats['total_receipts']); ?></div>
                    <small class="text-secondary"><?php echo e($selectedItem->unit ?? 'units'); ?> received</small>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
            </div>
            <div class="col-xl-4 col-md-6">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['class' => 'border-0 shadow-sm h-100']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'border-0 shadow-sm h-100']); ?>
                    <h6 class="fw-semibold mb-1">Current Balance</h6>
                    <div class="fs-5 fw-bold text-primary"><?php echo e($stats['current_balance']); ?></div>
                    <small class="text-secondary">Running balance</small>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Ledger Transactions -->
    <div class="col-12">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => 'Transaction History','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Transaction History','class' => 'border-0 shadow-sm']); ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Transaction Type</th>
                            <th>Reference #</th>
                            <th>Qty In</th>
                            <th>Qty Out</th>
                            <th>Running Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($transaction->created_at->format('M d, Y H:i')); ?></td>
                            <td><?php echo e($transaction->item ? $transaction->item->name : 'N/A'); ?></td>
                            <td>
                                <?php if($transaction->transaction_type === 'RECEIVE'): ?>
                                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.badge','data' => ['type' => 'success','text' => 'RECEIPT (SRA)']]); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'success','text' => 'RECEIPT (SRA)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                <?php else: ?>
                                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.badge','data' => ['type' => 'danger','text' => 'ISSUE']]); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'danger','text' => 'ISSUE']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($transaction->reference_type); ?>-<?php echo e($transaction->reference_id); ?></td>
                            <td class="text-success fw-bold"><?php echo e($transaction->transaction_type === 'RECEIVE' ? '+' . $transaction->quantity : '-'); ?></td>
                            <td class="text-danger fw-bold"><?php echo e($transaction->transaction_type === 'ISSUE' ? '-' . $transaction->quantity : '-'); ?></td>
                            <td class="fw-bold"><?php echo e($transaction->balance_after); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-secondary">No transactions found matching your criteria.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <?php echo e($transactions->links()); ?>

            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>

    <!-- Ledger Summary -->
    <div class="col-12">
        <div class="row g-3">
            <div class="col-md-6">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => ''.e($selectedItem ? 'Summary Statistics: ' . $selectedItem->name : 'Global Summary Statistics').'','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => ''.e($selectedItem ? 'Summary Statistics: ' . $selectedItem->name : 'Global Summary Statistics').'','class' => 'border-0 shadow-sm']); ?>
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span>Total Receipts:</span>
                        <strong class="text-success">+<?php echo e(number_format($stats['total_receipts'])); ?> <?php echo e($selectedItem ? $selectedItem->unit : 'items'); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span>Total Issues:</span>
                        <strong class="text-danger">-<?php echo e(number_format($stats['total_issues'])); ?> <?php echo e($selectedItem ? $selectedItem->unit : 'items'); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold"><?php echo e($selectedItem ? 'Current Balance:' : 'Total System Balance:'); ?></span>
                        <strong class="text-primary fs-5"><?php echo e(number_format($stats['current_balance'])); ?> <?php echo e($selectedItem ? $selectedItem->unit : 'items'); ?></strong>
                    </div>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
            </div>
            <div class="col-md-6">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => 'Stock Status','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Stock Status','class' => 'border-0 shadow-sm']); ?>
                    <?php if($selectedItem): ?>
                        <?php
                            $stock = $stats['current_balance'];
                            $badgeType = 'success';
                            $statusText = 'In Stock';
                            if ($stock <= 0) { $badgeType = 'danger'; $statusText = 'Out of Stock'; }
                            elseif ($stock < $stats['min_stock']) { $badgeType = 'warning'; $statusText = 'Low Stock'; }
                        ?>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span>Current Stock:</span>
                            <span class="badge bg-<?php echo e($badgeType); ?> px-2 py-1"><?php echo e($stock); ?> / Max: <?php echo e($stats['max_stock']); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span>Min. Stock Level:</span>
                            <span><?php echo e($stats['min_stock']); ?> <?php echo e($selectedItem->unit); ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Status:</span>
                            <span class="badge bg-<?php echo e($badgeType); ?>"><?php echo e($statusText); ?></span>
                        </div>
                    <?php else: ?>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span>Total Items in Registry:</span>
                            <span class="fw-bold"><?php echo e($items->count()); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span>Items with Low Stock:</span>
                            <span class="badge bg-warning text-body-emphasis px-2 py-1"><?php echo e($stats['low_stock_items']); ?> items</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>System Health:</span>
                            <?php if($stats['low_stock_items'] > 0): ?>
                                <span class="badge bg-warning">Attention Required</span>
                            <?php else: ?>
                                <span class="badge bg-success">Optimal</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp74\htdocs\store_management\resources\views/ledger/index.blade.php ENDPATH**/ ?>
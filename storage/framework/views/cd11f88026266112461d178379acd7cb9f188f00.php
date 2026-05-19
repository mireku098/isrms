<?php $__env->startSection('title', 'Stores Received Advice (SRA)'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Stores Received Advice (SRA)</h2>
                <p class="mb-0 text-secondary small">Record and manage goods received from suppliers.</p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isStorekeeper')): ?>
            <a href="<?php echo e(route('sra.create')); ?>" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Receipt (SRA)
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- SRA List -->
    <div class="col-12">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => 'SRA History','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'SRA History','class' => 'border-0 shadow-sm']); ?>
             <?php $__env->slot('headerAction', null, []); ?> 
                <form method="GET" action="<?php echo e(route('sra.index')); ?>" class="d-flex gap-2">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control form-control-sm" placeholder="Search SRA #...">
                    <select name="status" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>>Approved</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <?php if(request()->filled('search') || request()->filled('status')): ?>
                        <a href="<?php echo e(route('sra.index')); ?>" class="btn btn-sm btn-ghost">Reset</a>
                    <?php endif; ?>
                </form>
             <?php $__env->endSlot(); ?>

            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.table','data' => ['headers' => ['SRA #', 'Supplier', 'Date Received', 'Status', 'Approvals', 'Actions']]]); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['SRA #', 'Supplier', 'Date Received', 'Status', 'Approvals', 'Actions'])]); ?>
                <?php $__empty_1 = true; $__currentLoopData = $sras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="fw-bold text-body-emphasis"><?php echo e($sra->sra_number); ?></div>
                        <div class="small text-secondary">Created by <?php echo e($sra->createdBy ? $sra->createdBy->name : 'Unknown'); ?></div>
                    </td>
                    <td><?php echo e(Str::limit($sra->supplier_details, 30)); ?></td>
                    <td><?php echo e($sra->created_at->format('M d, Y')); ?></td>
                    <td>
                        <span class="badge <?php echo e($sra->status === 'approved' ? 'bg-success' : 'bg-warning'); ?>">
                            <?php echo e(ucfirst($sra->status)); ?>

                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <span class="badge <?php echo e($sra->signed_auditor ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'); ?> p-1 rounded-circle" title="Auditor Signed">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <?php if($sra->signed_auditor): ?>
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    <?php else: ?>
                                        <circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>
                                    <?php endif; ?>
                                </svg>
                            </span>
                            <span class="badge <?php echo e($sra->signed_principal ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'); ?> p-1 rounded-circle" title="Principal Signed">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <?php if($sra->signed_principal): ?>
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    <?php else: ?>
                                        <circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>
                                    <?php endif; ?>
                                </svg>
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('sra.show', $sra->id)); ?>" class="btn btn-ghost btn-icon btn-sm rounded-circle text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>
                            <?php if($sra->status === 'approved'): ?>
                            <a href="#" class="btn btn-ghost btn-icon btn-sm rounded-circle text-secondary" title="Print PDF">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-4 text-secondary">No SRA records found.</td>
                </tr>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
            <div class="mt-4">
                <?php echo e($sras->links()); ?>

            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp74\htdocs\store_management\resources\views/sra/index.blade.php ENDPATH**/ ?>
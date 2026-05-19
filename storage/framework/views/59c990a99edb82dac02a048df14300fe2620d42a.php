

<?php $__env->startSection('title', 'View SRA'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $statusType = $sra->status === 'approved' ? 'success' : 'warning';
    $supplierLines = collect(explode('|', (string) ($sra->supplier_details ?? '')))
        ->map(fn ($line) => trim($line))
        ->filter();
?>
<div class="row mb-6 g-6">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('sra.index')); ?>">SRA Receipts</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo e($sra->sra_number); ?></li>
            </ol>
        </nav>
    </div>

    <!-- SRA Header -->
    <div class="col-lg-8">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => 'Stores Received Advice Details','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Stores Received Advice Details','class' => 'border-0 shadow-sm']); ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">SRA #</label>
                        <p class="text-body-emphasis fw-bold"><?php echo e($sra->sra_number); ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Status</label>
                        <p><?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.badge','data' => ['type' => $statusType,'text' => ucfirst($sra->status)]]); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statusType),'text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(ucfirst($sra->status))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Created Date</label>
                        <p class="text-body-emphasis"><?php echo e($sra->created_at ? $sra->created_at->format('M d, Y h:i A') : '-'); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Created By</label>
                        <p class="text-body-emphasis"><?php echo e($sra->createdBy ? $sra->createdBy->name : 'Unknown'); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Supplier Details</label>
                        <?php if($supplierLines->isNotEmpty()): ?>
                            <?php $__currentLoopData = $supplierLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p class="mb-1 text-body-emphasis"><?php echo e($line); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <p class="text-secondary">No supplier details provided.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>

    <!-- Approval Status Card -->
    <div class="col-lg-4">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => 'Multi-Signature Approval','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Multi-Signature Approval','class' => 'border-0 shadow-sm']); ?>
            <div class="d-flex flex-column gap-3">
                <!-- Auditor -->
                <div class="d-flex align-items-center gap-2">
                    <span class="badge <?php echo e($sra->signed_auditor ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'); ?> p-2 rounded-circle" title="Auditor Status">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                    <div>
                        <div class="small fw-semibold">Auditor</div>
                        <div class="small text-secondary"><?php echo e($sra->signed_auditor ? 'Signed' : 'Pending review'); ?></div>
                    </div>
                </div>

                <!-- Principal -->
                <div class="d-flex align-items-center gap-2">
                    <span class="badge <?php echo e($sra->signed_principal ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'); ?> p-2 rounded-circle" title="Principal Status">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle></svg>
                    </span>
                    <div>
                        <div class="small fw-semibold">Principal</div>
                        <div class="small text-secondary"><?php echo e($sra->signed_principal ? 'Signed' : 'Pending review'); ?></div>
                    </div>
                </div>
            </div>

            <!-- Approval Action -->
            <?php if(auth()->user() && auth()->user()->hasRole('auditor') && !$sra->signed_auditor): ?>
            <div class="border-top mt-4 pt-4">
                <p class="small text-secondary mb-2">As Auditor, you must verify and sign:</p>
                <form action="<?php echo e(route('sra.approve', $sra->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary w-100 btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="display: inline;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Review & Sign as Auditor
                    </button>
                </form>
            </div>
            <?php elseif(auth()->user() && auth()->user()->hasRole('principal') && !$sra->signed_principal): ?>
            <div class="border-top mt-4 pt-4">
                <p class="small text-secondary mb-2">As Principal, you must review and approve:</p>
                <form action="<?php echo e(route('sra.approve', $sra->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success w-100 btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1" style="display: inline;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Final Approval
                    </button>
                </form>
            </div>
            <?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>

    <!-- Received Items -->
    <div class="col-12">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => 'Received Items','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Received Items','class' => 'border-0 shadow-sm']); ?>
            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.table','data' => ['headers' => ['Item', 'Category', 'Unit', 'Quantity Received']]]); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Item', 'Category', 'Unit', 'Quantity Received'])]); ?>
                <?php $__empty_1 = true; $__currentLoopData = $sra->sraItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sraItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-bold text-body-emphasis"><?php echo e($sraItem->item ? $sraItem->item->name : 'Unknown Item'); ?></td>
                        <td><?php echo e($sraItem->item ? $sraItem->item->category : 'Uncategorized'); ?></td>
                        <td><?php echo e($sraItem->item ? $sraItem->item->unit : 'N/A'); ?></td>
                        <td><?php echo e(number_format($sraItem->quantity)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-secondary">No received items for this SRA.</td>
                    </tr>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>

    <!-- Approval Timeline -->
    <div class="col-12">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => 'Approval Timeline','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Approval Timeline','class' => 'border-0 shadow-sm']); ?>
            <div class="timeline">
                <div class="timeline-item pb-4 border-bottom">
                    <div class="d-flex gap-3">
                        <div class="timeline-marker">
                            <span class="badge bg-success rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">SRA Created</h6>
                            <p class="small text-secondary"><?php echo e($sra->created_at ? $sra->created_at->format('M d, Y h:i A') : '-'); ?> by <?php echo e($sra->createdBy ? $sra->createdBy->name : 'Unknown'); ?> (Storekeeper)</p>
                        </div>
                    </div>
                </div>
                <div class="timeline-item py-4 border-bottom">
                    <div class="d-flex gap-3">
                        <div class="timeline-marker">
                            <span class="badge <?php echo e($sra->signed_auditor ? 'bg-success' : 'bg-secondary'); ?> rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Auditor Signature</h6>
                            <p class="small text-secondary"><?php echo e($sra->signed_auditor ? 'Completed' : 'Pending review'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="timeline-item pt-4">
                    <div class="d-flex gap-3">
                        <div class="timeline-marker">
                            <span class="badge <?php echo e($sra->signed_principal ? 'bg-success' : 'bg-secondary'); ?> rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Principal Signature</h6>
                            <p class="small text-secondary"><?php echo e($sra->signed_principal ? 'Completed' : 'Pending'); ?></p>
                        </div>
                    </div>
                </div>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp74\htdocs\store_management\resources\views/sra/show.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Requisitions'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-6 g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Requisitions</h2>
                <p class="mb-0 text-secondary small">Submit and track requests for store items.</p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isRequester')): ?>
            <a href="<?php echo e(route('requisitions.create')); ?>" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                New Requisition
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Requisition List -->
    <div class="col-12">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => 'Requisition History','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Requisition History','class' => 'border-0 shadow-sm']); ?>
             <?php $__env->slot('headerAction', null, []); ?> 
                <form method="GET" action="<?php echo e(route('requisitions.index')); ?>" class="d-flex gap-2">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control form-control-sm" placeholder="Search Requisition #...">
                    <select name="status" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>>Approved</option>
                        <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                        <option value="issued" <?php echo e(request('status') === 'issued' ? 'selected' : ''); ?>>Issued</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <?php if(request()->filled('search') || request()->filled('status')): ?>
                        <a href="<?php echo e(route('requisitions.index')); ?>" class="btn btn-sm btn-ghost">Reset</a>
                    <?php endif; ?>
                </form>
             <?php $__env->endSlot(); ?>

            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.table','data' => ['headers' => ['REQ #', 'Requested By', 'Department', 'Date', 'Status', 'Approvals', 'Actions']]]); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['REQ #', 'Requested By', 'Department', 'Date', 'Status', 'Approvals', 'Actions'])]); ?>
                <?php $__empty_1 = true; $__currentLoopData = $requisitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="fw-bold text-body-emphasis"><?php echo e($req->requisition_number); ?></div>
                        <div class="small text-secondary"><?php echo e($req->requisitionItems->count()); ?> items requested</div>
                    </td>
                    <td><?php echo e($req->requester ? $req->requester->name : 'Unknown'); ?></td>
                    <td><?php echo e($req->department ?? 'N/A'); ?></td>
                    <td><?php echo e($req->created_at->format('M d, Y')); ?></td>
                    <td>
                        <?php
                            $statusType = 'warning';
                            if($req->status === 'approved') $statusType = 'success';
                            if($req->status === 'rejected') $statusType = 'danger';
                            if($req->issues->count() > 0) $statusType = 'info';
                        ?>
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.badge','data' => ['type' => $statusType,'text' => ucfirst($req->status)]]); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statusType),'text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(ucfirst($req->status))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <span class="badge <?php echo e($req->approved_by ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'); ?> p-1 rounded-circle" title="<?php echo e($req->approved_by ? 'Principal Signed' : 'Waiting for Principal'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <?php if($req->approved_by): ?>
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    <?php else: ?>
                                        <circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>
                                    <?php endif; ?>
                                </svg>
                            </span>
                            <?php if($req->issues->count() > 0): ?>
                            <span class="badge bg-info-subtle text-info p-1 rounded-circle" title="Items Issued">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                            </span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('requisitions.show', $req->id)); ?>" class="btn btn-ghost btn-icon btn-sm rounded-circle text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>
                            <?php if($req->status === 'approved' && $req->issues->count() === 0 && auth()->user()->hasRole('storekeeper')): ?>
                            <a href="<?php echo e(route('issues.create', ['requisition_id' => $req->id])); ?>" class="btn btn-sm btn-outline-success">Issue</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-4 text-secondary">No requisition records found.</td>
                </tr>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
            <div class="mt-4">
                <?php echo e($requisitions->links()); ?>

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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp74\htdocs\store_management\resources\views/requisitions/index.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Categories'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-6">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Categories</h2>
                <p class="mb-0 text-secondary small">Manage item categories used in inventory forms.</p>
            </div>
            <a href="<?php echo e(route('categories.create')); ?>" class="btn btn-primary">Add Category</a>
        </div>
    </div>

    <div class="col-12">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => 'Category List','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Category List','class' => 'border-0 shadow-sm']); ?>
            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.table','data' => ['headers' => ['Name', 'Items Using Category', 'Created At', 'Actions']]]); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Name', 'Items Using Category', 'Created At', 'Actions'])]); ?>
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($category->name); ?></td>
                        <td><?php echo e($category->items_count); ?></td>
                        <td><?php echo e(optional($category->created_at)->format('M d, Y')); ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('categories.edit', $category->id)); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="<?php echo e(route('categories.destroy', $category->id)); ?>" method="POST" onsubmit="return confirm('Delete this category?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-secondary">No categories found.</td>
                    </tr>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

             <?php $__env->slot('footer', null, []); ?> 
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-secondary">
                        Showing <?php echo e($categories->firstItem() ?? 0); ?> to <?php echo e($categories->lastItem() ?? 0); ?> of <?php echo e($categories->total()); ?> categories
                    </span>
                    <?php echo e($categories->links()); ?>

                </div>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp74\htdocs\store_management\resources\views/categories/index.blade.php ENDPATH**/ ?>
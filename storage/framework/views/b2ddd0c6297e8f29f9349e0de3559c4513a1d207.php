<?php $attributes = $attributes->exceptProps(['headers' => [], 'class' => '']); ?>
<?php foreach (array_filter((['headers' => [], 'class' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="table-responsive">
    <table <?php echo e($attributes->merge(['class' => 'table table-hover align-middle ' . $class])); ?>>
        <thead class="table-light">
            <tr>
                <?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th scope="col" class="text-uppercase small fw-bold text-secondary"><?php echo e($header); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php echo e($slot); ?>

        </tbody>
    </table>
</div>
<?php /**PATH C:\xampp74\htdocs\store_management\resources\views/components/table.blade.php ENDPATH**/ ?>
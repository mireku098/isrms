<?php $attributes = $attributes->exceptProps(['type' => 'button', 'variant' => 'primary', 'size' => '']); ?>
<?php foreach (array_filter((['type' => 'button', 'variant' => 'primary', 'size' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<button type="<?php echo e($type); ?>" <?php echo e($attributes->merge(['class' => 'btn btn-' . $variant . ($size ? ' btn-' . $size : '')])); ?>>
    <?php echo e($slot); ?>

</button>
<?php /**PATH C:\xampp74\htdocs\store_management\resources\views/components/button.blade.php ENDPATH**/ ?>
<?php $attributes = $attributes->exceptProps(['type' => 'primary', 'text' => '']); ?>
<?php foreach (array_filter((['type' => 'primary', 'text' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<span <?php echo e($attributes->merge(['class' => 'badge bg-' . $type . '-subtle text-' . $type . '-emphasis'])); ?>>
    <?php echo e($text ?: $slot); ?>

</span>
<?php /**PATH C:\xampp74\htdocs\store_management\resources\views/components/badge.blade.php ENDPATH**/ ?>
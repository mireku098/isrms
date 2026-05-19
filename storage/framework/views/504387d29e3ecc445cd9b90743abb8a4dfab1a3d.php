<?php $attributes = $attributes->exceptProps(['label' => null, 'name', 'type' => 'text', 'placeholder' => '', 'value' => '', 'error' => null]); ?>
<?php foreach (array_filter((['label' => null, 'name', 'type' => 'text', 'placeholder' => '', 'value' => '', 'error' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="mb-3">
    <?php if($label): ?>
        <label for="<?php echo e($name); ?>" class="form-label fw-semibold small text-secondary"><?php echo e($label); ?></label>
    <?php endif; ?>
    <input type="<?php echo e($type); ?>" 
           name="<?php echo e($name); ?>" 
           id="<?php echo e($name); ?>" 
           class="form-control <?php if($error): ?> is-invalid <?php endif; ?>" 
           placeholder="<?php echo e($placeholder); ?>" 
           value="<?php echo e($value); ?>"
           <?php echo e($attributes); ?>>
    <?php if($error): ?>
        <div class="invalid-feedback"><?php echo e($error); ?></div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp74\htdocs\store_management\resources\views/components/input.blade.php ENDPATH**/ ?>
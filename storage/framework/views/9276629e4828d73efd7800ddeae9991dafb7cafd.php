<?php $attributes = $attributes->exceptProps(['title' => null, 'footer' => null, 'class' => '']); ?>
<?php foreach (array_filter((['title' => null, 'footer' => null, 'class' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div <?php echo e($attributes->merge(['class' => 'card ' . $class])); ?>>
    <?php if($title): ?>
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?php echo e($title); ?></h5>
            <?php if(isset($headerAction)): ?>
                <div><?php echo e($headerAction); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="card-body">
        <?php echo e($slot); ?>

    </div>
    
    <?php if($footer): ?>
        <div class="card-footer border-top bg-light">
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp74\htdocs\store_management\resources\views/components/card.blade.php ENDPATH**/ ?>
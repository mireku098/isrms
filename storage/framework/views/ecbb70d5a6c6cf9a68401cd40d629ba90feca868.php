<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta content="ISRMS" name="author">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<!-- Favicon icon-->
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('assets/images/favicon/apple-icon-180x180.png')); ?>" />
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('assets/images/favicon/favicon-32x32.png')); ?>" />
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('assets/images/favicon/favicon-16x16.png')); ?>" />

<meta name="msapplication-TileColor" content="#ffffff" />
<meta name="theme-color" content="#ffffff" />

<!-- Color modes -->
<script src="<?php echo e(asset('assets/js/vendors/color-modes.js')); ?>"></script>
<script>
  if (localStorage.getItem('sidebarExpanded') === 'false') {
    document.documentElement.classList.add('collapsed');
    document.documentElement.classList.remove('expanded');
  } else {
    document.documentElement.classList.remove('collapsed');
    document.documentElement.classList.add('expanded');
  }
</script>

<!-- Libs CSS -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" />
<?php /**PATH C:\xampp74\htdocs\store_management\resources\views/partials/head-meta.blade.php ENDPATH**/ ?>
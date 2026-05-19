<div id="miniSidebar">
  <div class="brand-logo">
    <a class="d-none d-md-flex align-items-center gap-2" href="<?php echo e(route('dashboard')); ?>">
      <img src="<?php echo e(asset('assets/images/brand/logo/logo-icon.svg')); ?>" alt="" />
      <span class="fw-bold fs-4 site-logo-text">ISRMS</span>
    </a>
  </div>
  <ul class="navbar-nav flex-column">
    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
        <span class="nav-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout-dashboard">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 4h6v8h-6z" />
            <path d="M4 16h6v4h-6z" />
            <path d="M14 12h6v8h-6z" />
            <path d="M14 4h6v4h-6z" />
          </svg>
        </span>
        <span class="text">Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <div class="nav-heading">Store Operations</div>
      <hr class="mx-5 nav-line mb-1" />
    </li>

    <!-- Item & Inventory -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isStorekeeper')): ?>
    <li class="nav-item">
      <a class="nav-link <?php echo e(request()->routeIs('items.*') ? 'active' : ''); ?>" href="<?php echo e(route('items.index')); ?>">
        <span class="nav-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-box">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
            <path d="M12 12l8 -4.5" />
            <path d="M12 12l0 9" />
            <path d="M12 12l-8 -4.5" />
            <path d="M16 5.25l-8 4.5" />
          </svg>
        </span>
        <span class="text">Inventory Management</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>" href="<?php echo e(route('categories.index')); ?>">
        <span class="nav-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 7h16" />
            <path d="M4 12h16" />
            <path d="M4 17h16" />
          </svg>
        </span>
        <span class="text">Categories</span>
      </a>
    </li>
    <?php endif; ?>

    <!-- Receiving (SRA) -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('accessSra')): ?>
    <li class="nav-item">
      <a class="nav-link <?php echo e(request()->routeIs('sra.*') ? 'active' : ''); ?>" href="<?php echo e(route('sra.index')); ?>">
        <span class="nav-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-download">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
            <path d="M7 11l5 5l5 -5" />
            <path d="M12 4l0 12" />
          </svg>
        </span>
        <span class="text">Receiving (SRA)</span>
      </a>
    </li>
    <?php endif; ?>

    <!-- Requisitions -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('accessRequisitions')): ?>
    <li class="nav-item">
      <a class="nav-link <?php echo e(request()->routeIs('requisitions.*') ? 'active' : ''); ?>" href="<?php echo e(route('requisitions.index')); ?>">
        <span class="nav-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-text">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
            <path d="M9 11l1 0" />
            <path d="M9 15l4 0" />
          </svg>
        </span>
        <span class="text">Requisitions</span>
      </a>
    </li>
    <?php endif; ?>

    <!-- Issues -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('accessIssues')): ?>
    <li class="nav-item">
      <a class="nav-link <?php echo e(request()->routeIs('issues.*') ? 'active' : ''); ?>" href="<?php echo e(route('issues.index')); ?>">
        <span class="nav-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-upload">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
            <path d="M7 9l5 -5l5 5" />
            <path d="M12 4l0 12" />
          </svg>
        </span>
        <span class="text">Issuing</span>
      </a>
    </li>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['accessLedger', 'accessReports'])): ?>
    <li class="nav-item">
      <div class="nav-heading">Reports & History</div>
      <hr class="mx-5 nav-line mb-1" />
    </li>

    <!-- Inventory Ledger -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('accessLedger')): ?>
    <li class="nav-item">
      <a class="nav-link <?php echo e(request()->routeIs('ledger.*') ? 'active' : ''); ?>" href="<?php echo e(route('ledger.index')); ?>">
        <span class="nav-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-history">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 8l0 4l2 2" />
            <path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" />
          </svg>
        </span>
        <span class="text">Inventory Ledger</span>
      </a>
    </li>
    <?php endif; ?>

    <!-- Reports -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('accessReports')): ?>
    <li class="nav-item">
      <a class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>" href="<?php echo e(route('reports.index')); ?>">
        <span class="nav-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chart-bar">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
            <path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
            <path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
            <path d="M4 20l14 0" />
          </svg>
        </span>
        <span class="text">Reports</span>
      </a>
    </li>
    <?php endif; ?>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isAdmin')): ?>
    <li class="nav-item">
      <div class="nav-heading">System</div>
      <hr class="mx-5 nav-line mb-1" />
    </li>

    <!-- User Management -->
    <li class="nav-item">
      <a class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>" href="<?php echo e(route('users.index')); ?>">
        <span class="nav-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
          </svg>
        </span>
        <span class="text">User Management</span>
      </a>
    </li>
    <?php endif; ?>
  </ul>
</div>

<div class="offcanvasNav offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <a class="d-flex align-items-center gap-2" href="<?php echo e(route('dashboard')); ?>">
      <img src="<?php echo e(asset('assets/images/brand/logo/logo-icon.svg')); ?>" alt="" />
      <span class="fw-bold fs-4 site-logo-text">ISRMS</span>
    </a>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-0">
    <!-- Re-use the same list if needed, or link to the main sidebar -->
  </div>
</div>
<?php /**PATH C:\xampp74\htdocs\store_management\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>
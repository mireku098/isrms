<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $__env->make('partials.head.head-meta', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title>Sign Up | ISRMS - Integrated Store & Requisition Management System</title>
    <?php echo $__env->make('partials.head.head-links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </head>

  <body>
    <main class="d-flex flex-column justify-content-center vh-100">
      <!--Sign up start-->
      <section>
        <div class="container py-5">
          <div class="row mb-8">
            <div class="col-xl-4 offset-xl-4 col-md-12 col-12">
              <div class="text-center">
                <a href="<?php echo e(url('/')); ?>" class="fs-2 fw-bold d-flex align-items-center gap-2 justify-content-center mb-6 text-decoration-none">
                  <img src="<?php echo e(asset('assets/images/brand/logo/logo-icon.svg')); ?>" alt="" />
                  <span class="text-dark">ISRMS</span>
                </a>
                <h1 class="mb-1">Create Account</h1>
                <p class="mb-0">Sign up now and get your account for the management system.</p>
              </div>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 col-12">
              <div class="card shadow-sm mb-4">
                <div class="card-body p-6">
                  <?php if($errors->any()): ?>
                    <div class="alert alert-danger mb-4">
                      <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </ul>
                    </div>
                  <?php endif; ?>

                  <form class="needs-validation mb-6" novalidate method="POST" action="<?php echo e(route('auth.register')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                      <label for="signupFullnameInput" class="form-label">Full Name</label>
                      <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="signupFullnameInput" value="<?php echo e(old('name')); ?>" required />
                      <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                      <label for="signupEmailInput" class="form-label">
                        Email
                        <span class="text-danger">*</span>
                      </label>
                      <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="signupEmailInput" value="<?php echo e(old('email')); ?>" required />
                      <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                      <label for="signupRoleInput" class="form-label">User Role</label>
                      <select name="role" id="signupRoleInput" class="form-select <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="" selected disabled>-- Select Role --</option>
                        <option value="storekeeper" <?php echo e(old('role') == 'storekeeper' ? 'selected' : ''); ?>>Storekeeper</option>
                        <option value="principal" <?php echo e(old('role') == 'principal' ? 'selected' : ''); ?>>Principal</option>
                        <option value="auditor" <?php echo e(old('role') == 'auditor' ? 'selected' : ''); ?>>Auditor</option>
                        <option value="requester" <?php echo e(old('role') == 'requester' ? 'selected' : ''); ?>>Requester</option>
                      </select>
                      <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="formSignUpPassword" class="form-label">Password</label>
                            <div class="password-field position-relative">
                                <input type="password" name="password" class="form-control fakePassword <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="formSignUpPassword" required />
                                <span><i class="ti ti-eye-off passwordToggler"></i></span>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="formSignUpConfirmPassword" class="form-label">Confirm Password</label>
                            <div class="password-field position-relative">
                                <input type="password" name="password_confirmation" class="form-control fakePassword" id="formSignUpConfirmPassword" required />
                                <span><i class="ti ti-eye-off passwordToggler"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                      <div class="mb-4 d-flex align-items-center justify-content-between">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="signupCheckTextCheckbox" required />
                          <label class="form-check-label ms-2" for="signupCheckTextCheckbox">
                            I agree to the <a href="#">Terms of Use</a> & <a href="#">Privacy Policy</a>
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="d-grid">
                      <button class="btn btn-primary" type="submit">Sign Up</button>
                    </div>
                  </form>

                  <span>Sign up with your social network.</span>
                  <div class="mt-3 d-flex gap-2 justify-content-between">
                    <a href="#" class="btn btn-google w-100">
                      <span class="me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="ti ti-google" viewBox="0 0 16 16">
                          <path
                            d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"
                          />
                        </svg>
                      </span>
                      Continue with Google
                    </a>

                    <a href="#" class="btn btn-facebook w-100">
                      <span class="me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="ti ti-facebook" viewBox="0 0 16 16">
                          <path
                            d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"
                          />
                        </svg>
                      </span>
                      Continue with Facebook
                    </a>
                  </div>
                </div>
              </div>
              <span>
                Already have an account?
                <a href="<?php echo e(route('auth.login')); ?>" class="text-primary">Sign in here.</a>
              </span>
            </div>
          </div>
        </div>
      </section>
      <!--Sign up end-->
      <div class="position-absolute end-0 bottom-0 m-4">
        <div class="dropdown">
          <button class="btn btn-light btn-icon rounded-circle d-flex align-items-center" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
            <i class="ti theme-icon-active lh-1"><i class="ti theme-icon ti-sun"></i></i>
            <span class="visually-hidden bs-theme-text">Toggle theme</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="true">
                <i class="ti theme-icon ti ti-sun"></i>
                <span class="ms-2">Light</span>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                <i class="ti theme-icon ti-moon-stars"></i>
                <span class="ms-2">Dark</span>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                <i class="ti theme-icon ti-circle-half-2"></i>
                <span class="ms-2">Auto</span>
              </button>
            </li>
          </ul>
        </div>
      </div>
    </main>

    <?php echo $__env->make('partials.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script src="<?php echo e(asset('assets/js/vendors/password.js')); ?>"></script>
  </body>
</html>
<?php /**PATH C:\xampp74\htdocs\store_management\resources\views/auth/register.blade.php ENDPATH**/ ?>
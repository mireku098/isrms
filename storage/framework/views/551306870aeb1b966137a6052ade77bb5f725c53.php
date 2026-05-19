

<?php $__env->startSection('title', 'Edit User'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center g-6">
    <div class="col-xl-8">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('users.index')); ?>">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                </ol>
            </nav>
            <h2 class="mb-0">Edit User</h2>
            <p class="text-secondary small">Update user account information and role assignment.</p>
        </div>

        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'border-0 shadow-sm']); ?>
            <form action="<?php echo e(route('users.update', 1)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <!-- Personal Information -->
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold mb-3">Personal Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input','data' => ['label' => 'Full Name','name' => 'name','placeholder' => 'Enter full name','value' => 'Ahmed Hassan','required' => true,'error' => ''.e($errors->first('name')).'']]); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['label' => 'Full Name','name' => 'name','placeholder' => 'Enter full name','value' => 'Ahmed Hassan','required' => true,'error' => ''.e($errors->first('name')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input','data' => ['label' => 'Email Address','name' => 'email','type' => 'email','placeholder' => 'user@example.com','value' => 'ahmed.hassan@isrms.com','required' => true,'error' => ''.e($errors->first('email')).'']]); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['label' => 'Email Address','name' => 'email','type' => 'email','placeholder' => 'user@example.com','value' => 'ahmed.hassan@isrms.com','required' => true,'error' => ''.e($errors->first('email')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Role Assignment -->
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold mb-3">Role Assignment</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label fw-semibold small text-secondary">Select Role <span class="text-danger">*</span></label>
                                <select name="role" id="role" class="form-select <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">Choose role...</option>
                                    <option value="admin">Admin</option>
                                    <option value="storekeeper" selected>Storekeeper</option>
                                    <option value="auditor">Internal Auditor</option>
                                    <option value="principal">Principal</option>
                                    <option value="requester">Requester (Department)</option>
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
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Account Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active Account
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Security (Optional) -->
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold mb-3">Change Password (Optional)</h5>
                    <p class="small text-secondary mb-3">Leave blank to keep existing password</p>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input','data' => ['label' => 'New Password','name' => 'password','type' => 'password','placeholder' => 'Leave blank to keep current password','error' => ''.e($errors->first('password')).'']]); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['label' => 'New Password','name' => 'password','type' => 'password','placeholder' => 'Leave blank to keep current password','error' => ''.e($errors->first('password')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input','data' => ['label' => 'Confirm New Password','name' => 'password_confirmation','type' => 'password','placeholder' => 'Confirm new password','error' => ''.e($errors->first('password_confirmation')).'']]); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['label' => 'Confirm New Password','name' => 'password_confirmation','type' => 'password','placeholder' => 'Confirm new password','error' => ''.e($errors->first('password_confirmation')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

                <!-- Role & Permissions -->
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold mb-3">Role Assignment</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label fw-semibold small text-secondary">Select Role</label>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="storekeeper" selected>Storekeeper</option>
                                    <option value="admin">Admin</option>
                                    <option value="auditor">Internal Auditor</option>
                                    <option value="principal">Principal</option>
                                    <option value="requester">Requester (Department)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="active" id="active" checked>
                                    <label class="form-check-label" for="active">
                                        Active Account
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Permissions</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="perms[]" value="view_inventory" id="perm1" checked>
                                    <label class="form-check-label" for="perm1">
                                        View Inventory
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="perms[]" value="create_sra" id="perm2" checked>
                                    <label class="form-check-label" for="perm2">
                                        Create SRA
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="perms[]" value="approve_requisitions" id="perm3">
                                    <label class="form-check-label" for="perm3">
                                        Approve Requisitions
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="perms[]" value="issue_items" id="perm4" checked>
                                    <label class="form-check-label" for="perm4">
                                        Issue Items
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="perms[]" value="view_reports" id="perm5" checked>
                                    <label class="form-check-label" for="perm5">
                                        View Reports
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="perms[]" value="manage_users" id="perm6">
                                    <label class="form-check-label" for="perm6">
                                        Manage Users
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Activity -->
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold mb-3">Account Activity</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Last Login</label>
                                <input type="text" class="form-control" value="Today, 2:45 PM" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-secondary">Account Created</label>
                                <input type="text" class="form-control" value="March 15, 2026" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Reset -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Change Password</h5>
                    <p class="small text-secondary mb-3">Leave these fields empty if you don't want to change the password</p>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input','data' => ['label' => 'New Password','name' => 'new_password','type' => 'password','placeholder' => 'Leave empty to keep current']]); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['label' => 'New Password','name' => 'new_password','type' => 'password','placeholder' => 'Leave empty to keep current']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.input','data' => ['label' => 'Confirm New Password','name' => 'new_password_confirmation','type' => 'password','placeholder' => 'Leave empty to keep current']]); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['label' => 'Confirm New Password','name' => 'new_password_confirmation','type' => 'password','placeholder' => 'Leave empty to keep current']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between gap-2 mt-4">
                    <button type="button" class="btn btn-outline-danger" onclick="if(confirm('Delete this user?')) { document.getElementById('deleteForm').submit(); }">Delete User</button>
                    <div>
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-ghost">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </div>
            </form>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal)): ?>
<?php $component = $__componentOriginal; ?>
<?php unset($__componentOriginal); ?>
<?php endif; ?>
    </div>
</div>

<form id="deleteForm" action="<?php echo e(route('users.destroy', 1)); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp74\htdocs\store_management\resources\views/users/edit.blade.php ENDPATH**/ ?>
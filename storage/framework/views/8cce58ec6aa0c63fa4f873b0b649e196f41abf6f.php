

<?php $__env->startSection('title', 'Internal Auditor Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-6 g-6">
    <div class="col-12">
        <div>
            <h2 class="mb-1">Welcome, Internal Auditor</h2>
            <p class="text-secondary"><?php echo e(date('l, F j, Y')); ?> - Verify and sign SRA documents</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-xl-4 col-md-6">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['class' => 'border-0 shadow-sm h-100']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'border-0 shadow-sm h-100']); ?>
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-warning-subtle text-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="1" />
                        <circle cx="12" cy="5" r="1" />
                        <circle cx="12" cy="19" r="1" />
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">Pending Verification</h6>
                    <div class="fs-4 fw-bold"><?php echo e($stats['pending_verifications']); ?></div>
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>

    <div class="col-xl-4 col-md-6">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['class' => 'border-0 shadow-sm h-100']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'border-0 shadow-sm h-100']); ?>
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-success-subtle text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">Total SRAs</h6>
                    <div class="fs-4 fw-bold"><?php echo e($stats['total_sras']); ?></div>
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>

    <div class="col-xl-4 col-md-6">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['class' => 'border-0 shadow-sm h-100']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'border-0 shadow-sm h-100']); ?>
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg rounded-circle bg-info-subtle text-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                        <path d="M12 3a9 9 0 1 0 9 9" />
                    </svg>
                </div>
                <div>
                    <h6 class="mb-0 text-secondary">Latest SRA</h6>
                    <div class="fs-6 fw-bold text-truncate" style="max-width: 150px;"><?php echo e($stats['recent_sras']->first()->sra_number ?? 'N/A'); ?></div>
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>

    <!-- Recent SRAs for Verification -->
    <div class="col-12">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => 'Recent SRAs','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Recent SRAs','class' => 'border-0 shadow-sm']); ?>
            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.alert','data' => ['type' => 'info','message' => 'ℹ️ Review and verify SRAs to maintain accurate inventory records.']]); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'info','message' => 'ℹ️ Review and verify SRAs to maintain accurate inventory records.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="auditorSraTable">
                    <thead class="table-light">
                        <tr>
                            <th>SRA #</th>
                            <th>Supplier</th>
                            <th>Date Received</th>
                            <th>Status</th>
                            <th>Auditor</th>
                            <th>Principal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $stats['recent_sras']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($sra->sra_number); ?></td>
                            <td><?php echo e(Str::limit($sra->supplier_details, 25)); ?></td>
                            <td><?php echo e($sra->created_at->format('M d, Y')); ?></td>
                            <td>
                                <span class="badge <?php echo e($sra->status === 'approved' ? 'bg-success' : 'bg-warning'); ?>">
                                    <?php echo e(ucfirst($sra->status)); ?>

                                </span>
                            </td>
                            <td><span class="badge <?php echo e($sra->signed_auditor ? 'bg-success' : 'bg-secondary'); ?>"><?php echo e($sra->signed_auditor ? '✓' : '✗'); ?></span></td>
                            <td><span class="badge <?php echo e($sra->signed_principal ? 'bg-success' : 'bg-secondary'); ?>"><?php echo e($sra->signed_principal ? '✓' : '✗'); ?></span></td>
                            <td>
                                <a href="<?php echo e(route('sra.show', $sra)); ?>" class="btn btn-sm btn-outline-primary">Review</a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-secondary">No SRAs found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>

    <!-- Requisition History -->
    <div class="col-12">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => 'Recent Requisition History','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Recent Requisition History','class' => 'border-0 shadow-sm']); ?>
            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.alert','data' => ['type' => 'info','message' => '📋 Overview of recent requisition approvals and rejections.']]); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'info','message' => '📋 Overview of recent requisition approvals and rejections.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="auditorRequisitionTable">
                    <thead class="table-light">
                        <tr>
                            <th>REQ #</th>
                            <th>Requested By</th>
                            <th>Department</th>
                            <th>Items</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Approved By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $stats['recent_requisitions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($req->requisition_number); ?></td>
                            <td><?php echo e($req->requester->name ?? 'Unknown'); ?></td>
                            <td><?php echo e($req->department ?? 'N/A'); ?></td>
                            <td><?php echo e($req->requisitionItems->count()); ?></td>
                            <td><?php echo e($req->created_at->format('M d, Y')); ?></td>
                            <td>
                                <span class="badge <?php echo e($req->status === 'approved' ? 'bg-success' : ($req->status === 'rejected' ? 'bg-danger' : 'bg-warning')); ?>">
                                    <?php echo e(ucfirst($req->status)); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($req->status === 'approved' && $req->approver): ?>
                                    <span class="text-success fw-semibold"><?php echo e($req->approver->name); ?></span>
                                <?php elseif($req->status === 'approved'): ?>
                                    <span class="text-muted">System Approved</span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('requisitions.show', $req)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-secondary">No requisition history found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>

    <!-- Verification Checklist -->
    <div class="col-lg-8">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => 'Verification Guidelines','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Verification Guidelines','class' => 'border-0 shadow-sm']); ?>
            <div class="list-group list-group-flush">
                <div class="list-group-item px-0 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check1">
                        </div>
                        <label class="form-check-label mb-0" for="check1">
                            <strong>Verify Item Quantities</strong>
                            <p class="mb-0 small text-secondary">Confirm all items match the bill and waybill</p>
                        </label>
                    </div>
                </div>
                <div class="list-group-item px-0 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check2">
                        </div>
                        <label class="form-check-label mb-0" for="check2">
                            <strong>Check Unit Prices</strong>
                            <p class="mb-0 small text-secondary">Verify pricing accuracy and calculations</p>
                        </label>
                    </div>
                </div>
                <div class="list-group-item px-0 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check3">
                        </div>
                        <label class="form-check-label mb-0" for="check3">
                            <strong>Validate Supplier Details</strong>
                            <p class="mb-0 small text-secondary">Ensure supplier information is complete and correct</p>
                        </label>
                    </div>
                </div>
                <div class="list-group-item px-0 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check4">
                        </div>
                        <label class="form-check-label mb-0" for="check4">
                            <strong>Review Total Amount</strong>
                            <p class="mb-0 small text-secondary">Confirm grand total matches invoice</p>
                        </label>
                    </div>
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>

    <div class="col-lg-4">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.card','data' => ['title' => 'Your Audit Info','class' => 'border-0 shadow-sm']]); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['title' => 'Your Audit Info','class' => 'border-0 shadow-sm']); ?>
            <div class="mb-3 pb-3 border-bottom">
                <h6 class="text-secondary small mb-1">Auditor Name</h6>
                <p class="mb-0 fw-semibold"><?php echo e(auth()->user()->name); ?></p>
            </div>
            <div class="mb-3 pb-3 border-bottom">
                <h6 class="text-secondary small mb-1">Role</h6>
                <p class="mb-0 fw-semibold text-primary">Internal Auditor</p>
            </div>
            <div>
                <h6 class="text-secondary small mb-1">Verified This Month</h6>
                <p class="mb-0"><strong class="fs-5 text-success" id="verifiedMonth"><?php echo e($stats['verified_this_month']); ?></strong> SRAs</p>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateSraTable(sras) {
            const tbody = document.querySelector('#auditorSraTable tbody');
            if (!tbody || !sras) return;

            if (sras.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-secondary">No SRAs found.</td></tr>';
                return;
            }

            let html = '';
            sras.forEach(sra => {
                const statusBadge = sra.status === 'approved' ? 'bg-success' : 'bg-warning';
                const auditorBadge = sra.signed_auditor ? 'bg-success' : 'bg-secondary';
                const auditorIcon = sra.signed_auditor ? '✓' : '✗';
                const principalBadge = sra.signed_principal ? 'bg-success' : 'bg-secondary';
                const principalIcon = sra.signed_principal ? '✓' : '✗';
                
                // Format date (simplified)
                const date = new Date(sra.created_at).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                const supplier = (sra.supplier_details || '').substring(0, 25) + ((sra.supplier_details || '').length > 25 ? '...' : '');

                html += `
                    <tr>
                        <td class="fw-semibold">${sra.sra_number}</td>
                        <td>${supplier}</td>
                        <td>${date}</td>
                        <td><span class="badge ${statusBadge}">${sra.status.charAt(0).toUpperCase() + sra.status.slice(1)}</span></td>
                        <td><span class="badge ${auditorBadge}">${auditorIcon}</span></td>
                        <td><span class="badge ${principalBadge}">${principalIcon}</span></td>
                        <td>
                            <a href="/sra/${sra.id}" class="btn btn-sm btn-outline-primary">Review</a>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        function updateRequisitionTable(reqs) {
            const tbody = document.querySelector('#auditorRequisitionTable tbody');
            if (!tbody || !reqs) return;

            if (reqs.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-secondary">No requisition history found.</td></tr>';
                return;
            }

            let html = '';
            reqs.forEach(req => {
                const date = new Date(req.created_at).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                const reqNumber = req.requisition_number || `REQ-${String(req.id).padStart(5, '0')}`;
                let statusBadge = 'bg-warning';
                if (req.status === 'approved') statusBadge = 'bg-success';
                else if (req.status === 'rejected') statusBadge = 'bg-danger';
                
                const approvedBy = req.status === 'approved' 
                    ? (req.approver ? req.approver.name : 'System Approved') 
                    : '-';

                html += `
                    <tr>
                        <td class="fw-semibold">${reqNumber}</td>
                        <td>${req.requester ? req.requester.name : 'Unknown'}</td>
                        <td>${req.department || 'N/A'}</td>
                        <td>${req.requisition_items ? req.requisition_items.length : 0}</td>
                        <td>${date}</td>
                        <td><span class="badge ${statusBadge}">${req.status.charAt(0).toUpperCase() + req.status.slice(1)}</span></td>
                        <td><span class="${req.status === 'approved' ? 'text-success fw-semibold' : 'text-muted'}">${approvedBy}</span></td>
                        <td><a href="/requisitions/${req.id}" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        // Real-time stats polling every 10 seconds
        setInterval(async function() {
            try {
                const response = await fetch('/api/requisitions/dashboard/data');
                if (!response.ok) return;
                
                const data = await response.json();
                
                if (data.stats) {
                    // Update Counter Cards
                    const pendingVerifCount = document.querySelector('.col-xl-4:nth-child(2) .fs-4');
                    if (pendingVerifCount) pendingVerifCount.textContent = data.stats.pending_verifications;

                    const totalSraCount = document.querySelector('.col-xl-4:nth-child(3) .fs-4');
                    if (totalSraCount) totalSraCount.textContent = data.stats.total_sras;

                    const latestSraNum = document.querySelector('.col-xl-4:nth-child(4) .fs-6');
                    if (latestSraNum && data.recent_sras && data.recent_sras.length > 0) {
                        latestSraNum.textContent = data.recent_sras[0].sra_number;
                    }

                    // Update Audit Info
                    const verifiedMonth = document.getElementById('verifiedMonth');
                    if (verifiedMonth) verifiedMonth.textContent = data.stats.verified_this_month;
                }

                if (data.recent_sras) {
                    updateSraTable(data.recent_sras);
                }

                if (data.recent_requisitions) {
                    updateRequisitionTable(data.recent_requisitions);
                }
            } catch (error) {
                console.error('Error fetching auditor dashboard updates:', error);
            }
        }, 10000);
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp74\htdocs\store_management\resources\views/dashboard/auditor.blade.php ENDPATH**/ ?>
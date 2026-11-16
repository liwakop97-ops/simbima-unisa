<?php $__env->startSection('title', 'Permintaan Akun · SIMBIMA UNISA'); ?>
<?php $__env->startSection('topbar-title', 'Verifikasi akun'); ?>
<?php $__env->startSection('content'); ?>
<div class="card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
        <div><div class="small text-muted mb-1">Khusus Administrator</div><h1 class="h4 fw-bold text-primary mb-1">Permintaan akun</h1><p class="small text-muted mb-0">Verifikasi identitas, tentukan role, lalu aktifkan akun.</p></div>
        <span class="badge text-bg-warning align-self-center"><?php echo e($requests->total()); ?> menunggu</span>
    </div>
    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Nama &amp; email</th><th>NIDN / NIP</th><th>Program studi</th><th>Jabatan &amp; SINTA</th><th>Tanggal</th><th>Role &amp; keputusan</th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><div class="fw-semibold"><?php echo e($user->name); ?></div><small class="text-muted"><?php echo e($user->email); ?></small></td>
                <td><?php echo e($user->nidn_nip); ?></td>
                <td><?php echo e($user->institusi_prodi); ?></td>
                <td><div><?php echo e($user->jabatan_fungsional ?: '—'); ?></div><small class="text-muted">SINTA <?php echo e($user->sinta_id ?: '—'); ?></small></td>
                <td><?php echo e($user->created_at->format('d M Y')); ?></td>
                <td>
                    <div class="d-flex flex-wrap gap-2">
                        <form method="POST" action="<?php echo e(route('admin.accounts.approve', $user)); ?>" class="d-flex gap-2">
                            <?php echo csrf_field(); ?>
                            <select name="role" class="form-select form-select-sm" aria-label="Role untuk <?php echo e($user->name); ?>">
                                <option value="dosen" <?php if($user->role === 'dosen'): echo 'selected'; endif; ?>>Dosen</option>
                                <option value="reviewer" <?php if($user->role === 'reviewer'): echo 'selected'; endif; ?>>Reviewer</option>
                                <option value="lppm" <?php if($user->role === 'lppm'): echo 'selected'; endif; ?>>LPPM</option>
                                <option value="admin" <?php if($user->role === 'admin'): echo 'selected'; endif; ?>>Admin</option>
                            </select>
                            <button class="btn btn-sm btn-success" onclick="return confirm('Aktifkan akun <?php echo e(addslashes($user->name)); ?>?')"><i class="bi bi-check-lg me-1"></i>Setujui</button>
                        </form>
                        <form method="POST" action="<?php echo e(route('admin.accounts.reject', $user)); ?>"><?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tolak permintaan akun ini?')">Tolak</button></form>
                    </div>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6"><div class="text-center py-5"><div class="display-6 text-success mb-2"><i class="bi bi-person-check"></i></div><div class="fw-semibold">Tidak ada permintaan akun yang menunggu.</div><p class="small text-muted mb-0">Permintaan baru akan muncul di daftar ini.</p></div></td></tr>
        <?php endif; ?>
    </tbody></table></div>
    <div class="mt-3"><?php echo e($requests->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIMBIMA UNISA Palembang\resources\views/admin/accounts/requests.blade.php ENDPATH**/ ?>
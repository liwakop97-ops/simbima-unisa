<?php $__env->startSection('title', 'Kelola Pengguna · SIMBIMA UNISA'); ?>
<?php $__env->startSection('topbar-title', 'Kelola pengguna'); ?>
<?php $__env->startSection('content'); ?>
<div class="card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4"><div><div class="small text-muted mb-1">Khusus Administrator</div><h1 class="h4 fw-bold text-primary mb-1">Kelola pengguna</h1><p class="small text-muted mb-0">Edit identitas, status akun, kata sandi, dan pembagian role seluruh pengguna.</p></div><a href="<?php echo e(route('admin.accounts.requests')); ?>" class="btn btn-outline-primary"><i class="bi bi-person-check me-1"></i>Permintaan akun</a></div>

    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-6"><input type="search" name="q" class="form-control" value="<?php echo e(request('q')); ?>" placeholder="Cari nama, username, email, atau NIDN"></div>
        <div class="col-md-2"><select name="role" class="form-select"><option value="">Semua role</option><?php $__currentLoopData = ['admin' => 'Admin', 'lppm' => 'LPPM', 'dosen' => 'Dosen', 'reviewer' => 'Reviewer']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if(request('role') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div class="col-md-2"><select name="status" class="form-select"><option value="">Semua status</option><?php $__currentLoopData = ['active' => 'Aktif', 'pending' => 'Menunggu', 'rejected' => 'Ditolak']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if(request('status') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
        <div class="col-md-2 d-grid"><button class="btn btn-primary"><i class="bi bi-search me-1"></i>Cari</button></div>
    </form>

    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Pengguna</th><th>NIDN / NIP</th><th>Unit kerja</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr><td><div class="fw-semibold"><?php echo e($user->name); ?></div><small class="text-muted"><?php echo e($user->username ?: 'tanpa username'); ?> · <?php echo e($user->email); ?></small></td><td><?php echo e($user->nidn_nip ?: '—'); ?></td><td><?php echo e($user->institusi_prodi ?: '—'); ?></td><td><span class="badge <?php echo e($user->role === 'admin' ? 'bg-danger' : ($user->role === 'lppm' ? 'bg-primary' : 'bg-secondary')); ?>"><?php echo e(strtoupper($user->role)); ?></span></td><td><span class="badge <?php echo e($user->account_status === 'active' ? 'bg-success' : ($user->account_status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary')); ?>"><?php echo e(strtoupper($user->account_status)); ?></span></td><td><a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square me-1"></i>Edit</a></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6"><div class="empty-state"><i class="bi bi-people"></i><strong>Pengguna tidak ditemukan</strong><p>Ubah kata pencarian atau filter yang digunakan.</p></div></td></tr>
        <?php endif; ?>
    </tbody></table></div>
    <div class="mt-3"><?php echo e($users->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIMBIMA UNISA Palembang\resources\views\admin\users\index.blade.php ENDPATH**/ ?>
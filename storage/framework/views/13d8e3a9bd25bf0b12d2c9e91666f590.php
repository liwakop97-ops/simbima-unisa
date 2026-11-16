<?php $__env->startSection('title', 'Manajemen Usulan LPPM'); ?>
<?php $__env->startSection('content'); ?>
<div class="card p-3">
    <h5 class="fw-bold mb-3">Seluruh Usulan Penelitian & Pengabdian</h5>
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr><th>Kode</th><th>Judul</th><th>Ketua</th><th>Skor Review</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><code><?php echo e($p->kode_registrasi); ?></code></td>
                <td><?php echo e(Str::limit($p->judul, 55)); ?></td>
                <td><?php echo e($p->user->name); ?></td>
                <td><strong><?php echo e($p->averageReviewScore()); ?></strong></td>
                <td><span class="badge bg-<?php echo e($p->status_badge['color']); ?>"><?php echo e($p->status_badge['label']); ?></span></td>
                <td><a href="<?php echo e(route('admin.proposals.show', $p)); ?>" class="btn btn-sm btn-primary">Kelola Tahapan</a></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php echo e($proposals->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIMBIMA UNISA Palembang\resources\views\admin\proposals\index.blade.php ENDPATH**/ ?>
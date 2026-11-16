<?php $__env->startSection('title', 'Tugas Review Proposal'); ?>
<?php $__env->startSection('content'); ?>
<div class="card p-3">
    <h5 class="fw-bold mb-3">Daftar Penugasan Review Substansi</h5>
    <table class="table table-hover">
        <thead><tr><th>Kode</th><th>Judul</th><th>Skema</th><th>Status</th><th>Nilai</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><code><?php echo e($r->proposal->kode_registrasi); ?></code></td>
                <td><?php echo e($r->proposal->judul); ?></td>
                <td><?php echo e($r->proposal->scheme->kode_skema); ?></td>
                <td><?php echo e(strtoupper($r->status_review)); ?></td>
                <td><?php echo e($r->nilai_total); ?></td>
                <td><a href="<?php echo e(route('reviewer.reviews.show', $r)); ?>" class="btn btn-sm btn-primary">Buka Lembar Penilaian</a></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php echo e($reviews->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIMBIMA UNISA Palembang\resources\views\reviewer\reviews\index.blade.php ENDPATH**/ ?>
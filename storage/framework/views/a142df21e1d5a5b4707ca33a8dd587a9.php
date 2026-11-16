<?php $__env->startSection('title', 'Kelola Skema Hibah'); ?>
<?php $__env->startSection('content'); ?>
<div class="card p-4 mb-4">
    <h5 class="fw-bold text-primary mb-3">Tambah Skema Hibah Baru</h5>
    <form method="POST" action="<?php echo e(route('admin.schemes.store')); ?>" enctype="multipart/form-data" class="row g-3">
        <?php echo csrf_field(); ?>
        <div class="col-md-2"><input type="text" name="kode_skema" class="form-control" placeholder="Kode (PDP-2026)" required></div>
        <div class="col-md-4"><input type="text" name="nama_skema" class="form-control" placeholder="Nama Skema" required></div>
        <div class="col-md-2">
            <select name="kategori" class="form-select">
                <option value="penelitian">Penelitian</option>
                <option value="pengabdian">Pengabdian</option>
                <option value="konsorsium">Konsorsium</option>
                <option value="prototipe">Prototipe</option>
            </select>
        </div>
        <div class="col-md-2"><input type="number" name="tahun_anggaran" value="<?php echo e(date('Y')); ?>" class="form-control" required></div>
        <div class="col-md-2"><input type="number" name="plafon_maksimal" placeholder="Plafon (Rp)" class="form-control" required></div>
        <div class="col-md-3"><label class="small">Tanggal Buka</label><input type="date" name="tanggal_buka" class="form-control" required></div>
        <div class="col-md-3"><label class="small">Tanggal Tutup</label><input type="date" name="tanggal_tutup" class="form-control" required></div>
        <div class="col-md-3"><label class="small">Template Proposal (DOC/DOCX)</label><input type="file" name="template_file" accept=".doc,.docx" class="form-control"></div>
        <div class="col-md-3 d-flex align-items-end"><button class="btn btn-primary w-100">Simpan Skema</button></div>
    </form>
</div>

<div class="card p-3">
    <h6 class="fw-bold mb-3">Daftar Skema Terdaftar</h6>
    <table class="table table-hover">
        <thead><tr><th>Kode</th><th>Nama Skema</th><th>Kategori</th><th>Plafon</th><th>Jumlah Usulan</th><th>Template</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php $__currentLoopData = $schemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><code><?php echo e($s->kode_skema); ?></code></td>
                <td><?php echo e($s->nama_skema); ?></td>
                <td><?php echo e(ucfirst($s->kategori)); ?></td>
                <td>Rp <?php echo e(number_format($s->plafon_maksimal, 0, ',', '.')); ?></td>
                <td><?php echo e($s->proposals_count); ?></td>
                <td style="min-width:230px">
                    <div class="small mb-2 <?php echo e($s->hasTemplate() ? 'text-success' : 'text-muted'); ?>"><i class="bi <?php echo e($s->hasTemplate() ? 'bi-check-circle' : 'bi-file-earmark-x'); ?> me-1"></i><?php echo e($s->hasTemplate() ? 'Template tersedia' : 'Belum ada template'); ?></div>
                    <form method="POST" action="<?php echo e(route('admin.schemes.template', $s)); ?>" enctype="multipart/form-data" class="d-flex gap-1">
                        <?php echo csrf_field(); ?>
                        <input type="file" name="template_file" accept=".doc,.docx" class="form-control form-control-sm" required aria-label="Template <?php echo e($s->kode_skema); ?>">
                        <button type="submit" class="btn btn-sm btn-outline-primary"><?php echo e($s->hasTemplate() ? 'Ganti' : 'Pasang'); ?></button>
                    </form>
                </td>
                <td>
                    <?php if($s->proposals_count === 0): ?>
                        <form method="POST" action="<?php echo e(route('admin.schemes.destroy', $s)); ?>" onsubmit="return confirm('Hapus skema <?php echo e(addslashes($s->nama_skema)); ?>? Tindakan ini tidak dapat dibatalkan.')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Hapus</button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Skema sudah memiliki usulan"><i class="bi bi-lock me-1"></i>Digunakan</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIMBIMA UNISA Palembang\resources\views\admin\schemes\index.blade.php ENDPATH**/ ?>
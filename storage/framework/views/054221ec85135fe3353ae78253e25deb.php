<?php $__env->startSection('title', 'Lembar Penilaian Substansi'); ?>
<?php $__env->startSection('content'); ?>
<div class="card p-4 mb-4">
    <h5 class="fw-bold"><?php echo e($review->proposal->judul); ?></h5>
    <p class="text-muted small">Ketua: <?php echo e($review->proposal->user->name); ?> | Dana Usulan: Rp <?php echo e(number_format($review->proposal->dana_usulan, 0, ',', '.')); ?></p>
    <p><?php echo e($review->proposal->abstrak); ?></p>
    <a href="<?php echo e(asset('storage/' . $review->proposal->file_proposal)); ?>" target="_blank" class="btn btn-sm btn-outline-primary" style="width: fit-content;">Unduh Naskah Proposal (PDF)</a>
</div>

<div class="card p-4">
    <h5 class="fw-bold text-primary mb-3">Borang Penilaian Substansi (Skala 1 - 100)</h5>
    <form method="POST" action="<?php echo e(route('reviewer.reviews.score', $review)); ?>">
        <?php echo csrf_field(); ?>
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label">1. Perumusan Masalah (20%)</label>
                <input type="number" name="skor_rumusan_masalah" min="1" max="100" value="<?php echo e(old('skor_rumusan_masalah', $review->skor_rumusan_masalah ?: 80)); ?>" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">2. Tinjauan Pustaka & State of the Art (15%)</label>
                <input type="number" name="skor_tinjauan_pustaka" min="1" max="100" value="<?php echo e(old('skor_tinjauan_pustaka', $review->skor_tinjauan_pustaka ?: 80)); ?>" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">3. Metode Penelitian / Pengabdian (25%)</label>
                <input type="number" name="skor_metode" min="1" max="100" value="<?php echo e(old('skor_metode', $review->skor_metode ?: 80)); ?>" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">4. Target Luaran & Kelayakan Publikasi (25%)</label>
                <input type="number" name="skor_luaran" min="1" max="100" value="<?php echo e(old('skor_luaran', $review->skor_luaran ?: 80)); ?>" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">5. Kewajaran RAB (15%)</label>
                <input type="number" name="skor_rab" min="1" max="100" value="<?php echo e(old('skor_rab', $review->skor_rab ?: 80)); ?>" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Rekomendasi Reviewer</label>
            <select name="rekomendasi" class="form-select" required>
                <option value="layak" <?php if($review->rekomendasi === 'layak'): echo 'selected'; endif; ?>>Layak Didanai</option>
                <option value="layak_dengan_revisi" <?php if($review->rekomendasi === 'layak_dengan_revisi'): echo 'selected'; endif; ?>>Layak dengan Revisi Proposal / RAB</option>
                <option value="tidak_layak" <?php if($review->rekomendasi === 'tidak_layak'): echo 'selected'; endif; ?>>Tidak Layak Didanai</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Komentar & Saran Perbaikan untuk Pengusul</label>
            <textarea name="komentar_reviewer" rows="4" class="form-control" required><?php echo e(old('komentar_reviewer', $review->komentar_reviewer)); ?></textarea>
        </div>
        <button class="btn btn-primary fw-semibold">Simpan Hasil Penilaian</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIMBIMA UNISA Palembang\resources\views\reviewer\reviews\show.blade.php ENDPATH**/ ?>
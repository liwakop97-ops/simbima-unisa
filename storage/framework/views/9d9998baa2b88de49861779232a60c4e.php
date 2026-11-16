<?php $__env->startSection('title', 'Proses Usulan LPPM'); ?>
<?php $__env->startSection('content'); ?>
<div class="card p-4 mb-4">
    <span class="badge bg-<?php echo e($proposal->status_badge['color']); ?> mb-2" style="width: fit-content;"><?php echo e($proposal->status_badge['label']); ?></span>
    <h4 class="fw-bold"><?php echo e($proposal->judul); ?></h4>
    <p class="text-muted mb-2">Pengusul: <strong><?php echo e($proposal->user->name); ?></strong> (<?php echo e($proposal->user->nidn_nip); ?>) | Skema: <?php echo e($proposal->scheme->nama_skema); ?></p>
    <p class="mb-2">Dana Usulan: <strong>Rp <?php echo e(number_format($proposal->dana_usulan, 0, ',', '.')); ?></strong> | Dana Disetujui: <strong>Rp <?php echo e(number_format($proposal->dana_disetujui ?? 0, 0, ',', '.')); ?></strong></p>
    <a href="<?php echo e(asset('storage/' . $proposal->file_proposal)); ?>" target="_blank" class="btn btn-sm btn-outline-primary" style="width: fit-content;">Unduh File Proposal (PDF)</a>
</div>


<?php if(in_array($proposal->status, ['submitted', 'under_review'])): ?>
<div class="card p-4 mb-4">
    <h5 class="fw-bold text-primary">Tahap 1: Verifikasi Administrasi & Plotting Reviewer</h5>
    <form method="POST" action="<?php echo e(route('admin.proposals.verify', $proposal)); ?>" class="row g-3">
        <?php echo csrf_field(); ?>
        <div class="col-md-4">
            <label class="form-label">Keputusan Administrasi</label>
            <select name="keputusan_admin" class="form-select">
                <option value="lolos">Lolos Administrasi & Tugaskan Reviewer</option>
                <option value="tolak">Tolak Administrasi</option>
            </select>
        </div>
        <div class="col-md-8">
            <label class="form-label">Pilih Reviewer (Bisa pilih lebih dari 1)</label>
            <select name="reviewer_ids[]" class="form-select" multiple>
                <?php $__currentLoopData = $reviewers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($rev->id); ?>"><?php echo e($rev->name); ?> (<?php echo e($rev->institusi_prodi); ?>)</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">Komentar LPPM untuk Pengusul</label>
            <textarea name="catatan_admin" rows="3" class="form-control" placeholder="Tulis hasil verifikasi atau saran perbaikan untuk pengusul..."></textarea>
        </div>
        <div class="col-12"><button class="btn btn-primary">Simpan & Tugaskan Reviewer</button></div>
    </form>
</div>
<?php endif; ?>


<div class="card p-4 mb-4">
    <h5 class="fw-bold text-primary">Tahap 2: Rekap Penilaian Reviewer & Penetapan Pendanaan</h5>
    <?php $__currentLoopData = $proposal->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="border rounded p-2 mb-2 small">
            <strong><?php echo e($rev->reviewer->name); ?></strong> — Status: <?php echo e(strtoupper($rev->status_review)); ?> | Skor: <strong><?php echo e($rev->nilai_total); ?></strong> | Rekomendasi: <strong><?php echo e($rev->rekomendasi ?? '-'); ?></strong>
            <div class="text-muted"><?php echo e($rev->komentar_reviewer); ?></div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if(in_array($proposal->status, ['under_review', 'accepted', 'accepted_revision', 'rejected'])): ?>
    <form method="POST" action="<?php echo e(route('admin.proposals.decide', $proposal)); ?>" class="row g-3 mt-2">
        <?php echo csrf_field(); ?>
        <div class="col-md-4">
            <label class="form-label">Keputusan Akhir Seleksi</label>
            <select name="keputusan_pendanaan" class="form-select">
                <option value="accepted_revision">DITERIMA (Wajib Revisi Proposal/RAB)</option>
                <option value="accepted">DITERIMA (Langsung Kontrak)</option>
                <option value="rejected">DITOLAK (Tidak Didanai)</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Dana Disetujui (Rp)</label>
            <input type="number" name="dana_disetujui" class="form-control" value="<?php echo e($proposal->dana_usulan); ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Nomor Kontrak / SK</label>
            <input type="text" name="nomor_kontrak" class="form-control" placeholder="Otomatis jika dikosongkan">
        </div>
        <div class="col-12"><label class="form-label">Komentar LPPM untuk Pengusul</label><textarea name="catatan_admin" rows="3" class="form-control" placeholder="Tulis catatan penetapan pendanaan atau saran perbaikan..."></textarea></div>
        <div class="col-12"><button class="btn btn-success fw-semibold">Tetapkan Keputusan Pendanaan</button></div>
    </form>
    <?php endif; ?>
</div>


<?php if(in_array($proposal->status, ['revision_submitted', 'accepted'])): ?>
<div class="card p-4 mb-4 border-start border-4 border-info">
    <h5 class="fw-bold">Tahap 3: Pengesahan Revisi Proposal & Mulai Pelaksanaan</h5>
    <?php if($proposal->file_revisi_proposal): ?>
        <p><a href="<?php echo e(asset('storage/' . $proposal->file_revisi_proposal)); ?>" target="_blank" class="btn btn-sm btn-outline-success">Cek File Revisi Proposal</a></p>
    <?php endif; ?>
    <form method="POST" action="<?php echo e(route('admin.proposals.approve-revision', $proposal)); ?>">
        <?php echo csrf_field(); ?>
        <button class="btn btn-primary">Sahkan Revisi & Aktifkan Kontrak Pelaksanaan</button>
    </form>
</div>
<?php endif; ?>


<?php if($proposal->finalReport): ?>
<div class="card p-4">
    <h5 class="fw-bold text-success">Tahap 4: Verifikasi Laporan Akhir & Luaran</h5>
    <p><a href="<?php echo e(asset('storage/' . $proposal->finalReport->file_laporan_akhir)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Unduh Laporan Akhir (PDF)</a></p>
    <form method="POST" action="<?php echo e(route('admin.proposals.verify-final', $proposal)); ?>" class="row g-2">
        <?php echo csrf_field(); ?>
        <div class="col-md-4">
            <select name="status_akhir" class="form-select">
                <option value="verified">Sahkan Laporan Akhir (Status: SELESAI)</option>
                <option value="needs_revision">Kembalikan untuk Perbaikan</option>
            </select>
        </div>
        <div class="col-md-6"><input type="text" name="catatan_akhir" class="form-control" placeholder="Catatan verifikasi akhir..."></div>
        <div class="col-md-2"><button class="btn btn-success w-100">Verifikasi</button></div>
    </form>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIMBIMA UNISA Palembang\resources\views\admin\proposals\show.blade.php ENDPATH**/ ?>
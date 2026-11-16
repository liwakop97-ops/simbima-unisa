<?php $__env->startSection('title', 'Detail Usulan Penelitian'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
    <div><div class="small text-muted mb-1"><i class="bi bi-house-door me-1"></i>Dashboard <i class="bi bi-chevron-right mx-1"></i>Penelitian <i class="bi bi-chevron-right mx-1"></i>Detail Usulan</div><h1 class="h4 fw-bold text-primary mb-0">Usulan Penelitian</h1></div>
    <a href="<?php echo e(route('dosen.proposals.index', ['kategori' => $proposal->scheme?->kategori ?? 'penelitian'])); ?>" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <span class="badge bg-secondary mb-1"><?php echo e($proposal->kode_registrasi); ?></span>
            <span class="badge bg-<?php echo e($proposal->status_badge['color']); ?> mb-1"><?php echo e($proposal->status_badge['label']); ?></span>
            <h4 class="fw-bold mt-1"><?php echo e($proposal->judul); ?></h4>
            <p class="text-muted mb-1">Skema: <strong><?php echo e($proposal->scheme->nama_skema); ?></strong> | Nomor Kontrak: <strong><?php echo e($proposal->nomor_kontrak ?? '-'); ?></strong></p>
        </div>
        <?php if($proposal->status === 'draft'): ?>
        <form method="POST" action="<?php echo e(route('dosen.proposals.submit', $proposal)); ?>">
            <?php echo csrf_field(); ?>
            <button class="btn btn-success">Ajukan Sekarang</button>
        </form>
        <?php endif; ?>
    </div>
    <hr>
    <div class="row g-4"><div class="col-lg-6"><dl class="row mb-0 small"><dt class="col-sm-5 text-muted">Ketua pengusul</dt><dd class="col-sm-7 fw-semibold"><?php echo e($proposal->user->name); ?><?php echo e($proposal->user->nidn_nip ? ' (' . $proposal->user->nidn_nip . ')' : ''); ?></dd><dt class="col-sm-5 text-muted">Institusi / Prodi</dt><dd class="col-sm-7"><?php echo e($proposal->user->institusi_prodi ?: '-'); ?></dd><dt class="col-sm-5 text-muted">Tahun pelaksanaan</dt><dd class="col-sm-7"><?php echo e($proposal->scheme?->tahun_anggaran ?? $proposal->created_at->year); ?></dd><dt class="col-sm-5 text-muted">Lama kegiatan</dt><dd class="col-sm-7"><?php echo e($proposal->lama_kegiatan_tahun); ?> Tahun</dd></dl></div><div class="col-lg-6"><dl class="row mb-0 small"><dt class="col-sm-5 text-muted">Dana usulan</dt><dd class="col-sm-7 fw-semibold">Rp <?php echo e(number_format($proposal->dana_usulan, 0, ',', '.')); ?></dd><dt class="col-sm-5 text-muted">Dana disetujui</dt><dd class="col-sm-7"><?php echo e($proposal->dana_disetujui !== null ? 'Rp ' . number_format($proposal->dana_disetujui, 0, ',', '.') : '-'); ?></dd><dt class="col-sm-5 text-muted">Kata kunci</dt><dd class="col-sm-7"><?php echo e($proposal->kata_kunci); ?></dd><dt class="col-sm-5 text-muted">Diajukan pada</dt><dd class="col-sm-7"><?php echo e(($proposal->submitted_at ?? $proposal->created_at)->translatedFormat('d F Y')); ?></dd></dl></div></div>
    <div class="mt-4"><h2 class="h6 fw-bold">Abstrak</h2><p class="mb-0 text-break"><?php echo nl2br(e($proposal->abstrak)); ?></p></div>
    <div class="d-flex flex-wrap gap-2 mt-4">
        <a href="<?php echo e(asset('storage/' . $proposal->file_proposal)); ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-pdf me-1"></i>Unduh Proposal Awal</a>
        <?php if($proposal->file_revisi_proposal): ?>
            <a href="<?php echo e(asset('storage/' . $proposal->file_revisi_proposal)); ?>" target="_blank" class="btn btn-sm btn-outline-success">Unduh Revisi Proposal (PDF)</a>
        <?php endif; ?>
    </div>
    <?php if($proposal->catatan_admin): ?>
        <div class="alert alert-info mt-3 mb-0"><strong>Catatan LPPM:</strong> <?php echo e($proposal->catatan_admin); ?></div>
    <?php endif; ?>
</div>

<div class="card p-4 mb-4">
    <h2 class="h5 fw-bold text-primary mb-3"><i class="bi bi-people me-2"></i>Identitas Anggota Tim</h2>
    <div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>No.</th><th>NIDN / NIM</th><th>Nama</th><th>Peran</th><th>Tugas</th></tr></thead><tbody>
        <tr><td>1</td><td><?php echo e($proposal->user->nidn_nip ?: '-'); ?></td><td class="fw-semibold"><?php echo e($proposal->user->name); ?></td><td>Ketua</td><td>Ketua pengusul</td></tr>
        <?php $__empty_1 = true; $__currentLoopData = $proposal->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr><td><?php echo e($loop->iteration + 1); ?></td><td><?php echo e($member->nidn_nim ?: '-'); ?></td><td><?php echo e($member->nama_anggota); ?></td><td><?php echo e(ucwords(str_replace('_', ' ', $member->peran))); ?></td><td><?php echo e($member->tugas ?: '-'); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <?php endif; ?>
    </tbody></table></div>
</div>

<div class="card p-4 mb-4">
    <h2 class="h5 fw-bold text-primary mb-1"><i class="bi bi-wallet2 me-2"></i>Rancangan Anggaran Biaya (RAB)</h2>
    <p class="small text-muted">Total anggaran yang diajukan: <strong>Rp <?php echo e(number_format($proposal->dana_usulan, 0, ',', '.')); ?></strong></p>
    <div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Kelompok</th><th>Item</th><th>Satuan</th><th>Harga Satuan</th><th>Volume</th><th>Total</th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $proposal->budgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr><td><?php echo e(ucwords(str_replace('_', ' ', $budget->kelompok_rab))); ?></td><td><?php echo e($budget->item_belanja); ?></td><td><?php echo e($budget->satuan); ?></td><td>Rp <?php echo e(number_format($budget->harga_satuan, 0, ',', '.')); ?></td><td><?php echo e($budget->volume); ?></td><td class="fw-semibold">Rp <?php echo e(number_format($budget->subtotal, 0, ',', '.')); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center py-4 text-muted">Rincian RAB belum diisi pada usulan ini.</td></tr>
        <?php endif; ?>
    </tbody></table></div>
</div>


<?php if($proposal->reviews->where('status_review', 'completed')->count() > 0): ?>
<div class="card p-4 mb-4">
    <h5 class="fw-bold text-primary">Hasil Penilaian Substansi Reviewer (Rata-rata: <?php echo e($proposal->averageReviewScore()); ?>)</h5>
    <?php $__currentLoopData = $proposal->reviews->where('status_review', 'completed'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $rev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="border rounded p-3 mb-2">
            <strong>Reviewer #<?php echo e($idx + 1); ?></strong> — Skor Total: <span class="badge bg-primary"><?php echo e($rev->nilai_total); ?></span>
            | Rekomendasi: <span class="badge bg-info text-dark"><?php echo e(strtoupper(str_replace('_', ' ', $rev->rekomendasi))); ?></span>
            <p class="mb-0 mt-2 text-muted"><?php echo e($rev->komentar_reviewer); ?></p>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>


<?php if(in_array($proposal->status, ['accepted', 'accepted_revision'])): ?>
<div class="card p-4 mb-4 border-start border-4 border-warning">
    <h5 class="fw-bold">Unggah Revisi Proposal & Penyesuaian RAB</h5>
    <p class="small text-muted">Dana disetujui LPPM sebesar <strong>Rp <?php echo e(number_format($proposal->dana_disetujui, 0, ',', '.')); ?></strong>. Silakan unggah proposal revisi untuk pengesahan kontrak.</p>
    <form method="POST" action="<?php echo e(route('dosen.proposals.revision', $proposal)); ?>" enctype="multipart/form-data" class="row g-2">
        <?php echo csrf_field(); ?>
        <div class="col-md-8"><input type="file" name="file_revisi_proposal" accept="application/pdf" class="form-control" required></div>
        <div class="col-md-4"><button class="btn btn-warning w-100 fw-semibold">Unggah Proposal Revisi</button></div>
    </form>
</div>
<?php endif; ?>


<?php if(in_array($proposal->status, ['in_progress', 'progress_submitted', 'progress_revision', 'progress_approved', 'final_submitted', 'completed'])): ?>
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card p-4 h-100">
            <h5 class="fw-bold text-primary">Catatan Harian (Logbook)</h5>
            <form method="POST" action="<?php echo e(route('dosen.proposals.logbooks.store', $proposal)); ?>" enctype="multipart/form-data" class="mb-3">
                <?php echo csrf_field(); ?>
                <div class="row g-2 mb-2">
                    <div class="col-6"><input type="date" name="tanggal_kegiatan" class="form-control form-control-sm" required></div>
                    <div class="col-6"><input type="number" name="persentase_capaian" class="form-control form-control-sm" placeholder="Capaian (%)" min="1" max="100" required></div>
                </div>
                <textarea name="uraian_kegiatan" rows="2" class="form-control form-control-sm mb-2" placeholder="Uraian kegiatan..." required></textarea>
                <button class="btn btn-sm btn-primary">Tambah Logbook</button>
            </form>
            <ul class="list-group list-group-flush small">
                <?php $__currentLoopData = $proposal->logbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="list-group-item px-0">
                        <strong><?php echo e($log->tanggal_kegiatan->format('d/m/Y')); ?></strong> (<?php echo e($log->persentase_capaian); ?>%): <?php echo e($log->uraian_kegiatan); ?>

                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-4 h-100">
            <h5 class="fw-bold text-primary">Laporan Kemajuan & Revisi Kemajuan</h5>
            <?php if($proposal->progressReport): ?>
                <div class="alert alert-secondary small">
                    Status Monev: <strong><?php echo e(strtoupper($proposal->progressReport->status_monev)); ?></strong>
                    <?php if($proposal->progressReport->catatan_monev): ?>
                        <br>Catatan Reviewer Monev: <em><?php echo e($proposal->progressReport->catatan_monev); ?></em>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if(in_array($proposal->status, ['in_progress', 'progress_revision'])): ?>
            <form method="POST" action="<?php echo e(route('dosen.proposals.progress.store', $proposal)); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="mb-2">
                    <label class="form-label small">File Laporan Kemajuan (PDF) *</label>
                    <input type="file" name="file_laporan_kemajuan" accept="application/pdf" class="form-control form-control-sm" required>
                </div>
                <div class="mb-2">
                    <label class="form-label small">Persentase Kemajuan (%) *</label>
                    <input type="number" name="persentase_kemajuan" value="75" min="50" max="100" class="form-control form-control-sm" required>
                </div>
                <div class="mb-2">
                    <textarea name="ringkasan_kemajuan" rows="2" class="form-control form-control-sm" placeholder="Ringkasan kemajuan..." required></textarea>
                </div>
                <button class="btn btn-sm btn-success">Unggah Laporan Kemajuan</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>


<?php if(in_array($proposal->status, ['progress_approved', 'final_submitted', 'completed'])): ?>
<div class="card p-4">
    <h5 class="fw-bold text-success">Laporan Akhir & Capaian Luaran Wajib/Tambahan</h5>
    <?php if($proposal->status === 'progress_approved'): ?>
    <form method="POST" action="<?php echo e(route('dosen.proposals.final.store', $proposal)); ?>" enctype="multipart/form-data" class="row g-3 mt-1">
        <?php echo csrf_field(); ?>
        <div class="col-md-6">
            <label class="form-label small">File Laporan Akhir (PDF) *</label>
            <input type="file" name="file_laporan_akhir" accept="application/pdf" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label small">Jenis Luaran *</label>
            <select name="jenis_luaran" class="form-select">
                <option value="jurnal_nasional">Jurnal Nasional Terakreditasi (SINTA)</option>
                <option value="jurnal_internasional">Jurnal Internasional Bereputasi</option>
                <option value="prosiding">Prosiding Seminar</option>
                <option value="hki_paten">HKI / Paten</option>
                <option value="buku">Buku Ajar / Referensi</option>
                <option value="teknologi_tepat_guna">Teknologi Tepat Guna</option>
            </select>
        </div>
        <div class="col-md-8">
            <input type="text" name="judul_luaran" class="form-control" placeholder="Judul Artikel / HKI / Produk Luaran" required>
        </div>
        <div class="col-md-4">
            <select name="status_capaian" class="form-select">
                <option value="submitted">Submitted</option>
                <option value="accepted">Accepted / LoA</option>
                <option value="published">Published</option>
                <option value="granted">Granted (HKI)</option>
            </select>
        </div>
        <div class="col-12">
            <textarea name="ringkasan_hasil" rows="3" class="form-control" placeholder="Ringkasan hasil akhir penelitian/pengabdian..." required></textarea>
        </div>
        <div class="col-12">
            <button class="btn btn-success fw-semibold">Unggah Laporan Akhir & Selesaikan Hibah</button>
        </div>
    </form>
    <?php endif; ?>

    <?php if($proposal->outputs->count() > 0): ?>
        <hr>
        <h6 class="fw-bold">Daftar Luaran Tercatat:</h6>
        <ul class="mb-0">
            <?php $__currentLoopData = $proposal->outputs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $out): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>[<?php echo e(strtoupper($out->jenis_luaran)); ?>] <strong><?php echo e($out->judul_luaran); ?></strong> — Status: <span class="badge bg-success"><?php echo e(strtoupper($out->status_capaian)); ?></span></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIMBIMA UNISA Palembang\resources\views\dosen\proposals\show.blade.php ENDPATH**/ ?>
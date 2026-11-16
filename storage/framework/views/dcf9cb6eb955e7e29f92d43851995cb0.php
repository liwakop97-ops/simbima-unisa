<?php $__env->startSection('title', ($category === 'penelitian' ? 'Penelitian' : ($category === 'pengabdian' ? ($program ?? 'Pengabdian') : ($category === 'konsorsium' ? ($program ?? 'Konsorsium') : 'Usulan Saya'))) . ' · SIMBIMA UNISA'); ?>
<?php $__env->startSection('content'); ?>
<?php if(in_array($category, ['pengabdian', 'konsorsium'], true)): ?>
<?php
    $isConsortium = $category === 'konsorsium';
    $serviceTitle = $program ?: ($isConsortium ? 'Riset Konsorsium Unggulan' : 'Pengabdian kepada Masyarakat');
    $serviceTabs = $isConsortium
        ? [
            'usulan' => ['label' => 'Usulan', 'icon' => 'bi-file-earmark-text'],
            'perbaikan-usulan' => ['label' => 'Perbaikan Usulan', 'icon' => 'bi-pencil-square'],
            'laporan-kemajuan' => ['label' => 'Laporan Kemajuan', 'icon' => 'bi-graph-up-arrow'],
            'catatan-harian' => ['label' => 'Catatan Harian', 'icon' => 'bi-journal-text'],
            'laporan-akhir' => ['label' => 'Laporan Akhir', 'icon' => 'bi-file-earmark-check'],
            'capaian-luaran' => ['label' => 'Pengkinian Capaian Luaran', 'icon' => 'bi-award'],
        ]
        : [
            'usulan' => ['label' => 'Usulan', 'icon' => 'bi-file-earmark-text'],
            'bimtek' => ['label' => 'Bimbingan Teknis', 'icon' => 'bi-mortarboard'],
            'perbaikan-usulan' => ['label' => 'Perbaikan Usulan', 'icon' => 'bi-pencil-square'],
            'catatan-anggaran' => ['label' => 'Catatan Harian Penggunaan Anggaran', 'icon' => 'bi-journal-text'],
            'laporan-kemajuan' => ['label' => 'Laporan Kemajuan', 'icon' => 'bi-graph-up-arrow'],
            'laporan-akhir' => ['label' => 'Laporan Akhir', 'icon' => 'bi-file-earmark-check'],
            'capaian-luaran' => ['label' => 'Pengkinian Capaian Luaran', 'icon' => 'bi-award'],
        ];
    $tabUrl = fn (string $name) => route('dosen.proposals.index', ['kategori' => $category, 'program' => $program, 'tab' => $name]);
?>
<div class="card p-3 p-md-4">
    <div class="mb-4"><div class="small text-muted mb-1"><i class="bi bi-house-door me-1"></i>Dashboard <i class="bi bi-chevron-right mx-1"></i><?php echo e($isConsortium ? 'Konsorsium' : 'Pengabdian'); ?></div><h1 class="h4 fw-bold text-primary mb-1"><?php echo e($serviceTitle); ?></h1><p class="small text-muted mb-0"><?php echo e($isConsortium ? 'Kelola seluruh tahapan usulan riset konsorsium.' : 'Kelola seluruh tahapan usulan pengabdian kepada masyarakat.'); ?></p></div>
    <div class="nav nav-pills gap-2 border-bottom pb-3 mb-4 overflow-auto flex-nowrap" role="tablist">
        <?php $__currentLoopData = $serviceTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><a href="<?php echo e($tabUrl($key)); ?>" class="nav-link btn btn-sm text-nowrap <?php echo e($tab === $key ? 'active' : 'text-secondary'); ?>"><i class="bi <?php echo e($item['icon']); ?> me-1"></i><?php echo e($item['label']); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php if($tab === 'usulan'): ?>
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3"><div><h2 class="h5 fw-bold mb-1"><?php echo e($isConsortium ? 'Usulan ' . $serviceTitle . ($program === 'Riset Konsorsium Unggulan' ? ' Berdampak (RIKUB)' : '') : 'Usulan Pengabdian'); ?></h2><p class="small text-muted mb-0">Daftar usulan <?php echo e($isConsortium ? 'riset konsorsium' : strtolower($serviceTitle)); ?> yang telah Anda buat.</p></div><a href="<?php echo e(route('dosen.proposals.create', ['kategori' => $category])); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat usulan</a></div>
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3"><button type="button" class="btn btn-info text-white"><i class="bi bi-info-circle me-1"></i>Info Eligibility</button><div><label class="form-label small mb-1">Tahun Pelaksanaan</label><div class="input-group"><span class="input-group-text bg-primary text-white"><i class="bi bi-funnel"></i></span><input type="text" class="form-control" value="<?php echo e(now()->year); ?>" readonly></div></div></div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>No.</th><th>Judul</th><th>Skema</th><th>Tahun Usulan</th><th>Tahun Pelaksanaan</th><th>Peran</th><th>Status Usulan</th><th>Status Validasi Luaran</th><th>Komentar</th><th>Aksi</th></tr></thead><tbody>
            <?php $__empty_1 = true; $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $completedReviews = $proposal->reviews->where('status_review', 'completed');
                ?>
                <tr><td><?php echo e($proposals->firstItem() + $loop->index); ?></td><td><div class="fw-semibold"><?php echo e($proposal->judul); ?></div><small class="text-muted"><?php echo e($proposal->kode_registrasi); ?></small></td><td><?php echo e($proposal->scheme?->nama_skema ?? '-'); ?></td><td><?php echo e($proposal->created_at->year); ?></td><td><?php echo e($proposal->scheme?->tahun_anggaran ?? $proposal->created_at->year); ?></td><td>Ketua</td><td><span class="badge bg-<?php echo e($proposal->status_badge['color']); ?>"><?php echo e($proposal->status_badge['label']); ?></span></td><td><?php echo e($proposal->outputs->isNotEmpty() ? 'Sudah diisi' : 'Belum diisi'); ?></td><td><?php if($proposal->catatan_admin || $completedReviews->isNotEmpty()): ?><a href="<?php echo e(route('dosen.proposals.show', $proposal)); ?>#komentar" class="btn btn-sm btn-primary"><i class="bi bi-chat-left-text me-1"></i>Komentar</a><?php else: ?><span class="text-muted small">-</span><?php endif; ?></td><td><a href="<?php echo e(route('dosen.proposals.show', $proposal)); ?>" class="btn btn-sm btn-outline-primary" title="Lihat detail"><i class="bi bi-eye"></i></a></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="10" class="text-center py-4 text-muted">Data tidak tersedia!</td></tr>
            <?php endif; ?>
        </tbody></table></div>
    <?php elseif($tab === 'bimtek'): ?>
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Bimbingan Teknis</h2><p class="small text-muted">Informasi bimbingan teknis <?php echo e(strtolower($serviceTitle)); ?> akan ditampilkan di sini.</p></div><div class="text-center py-5 border rounded bg-light"><i class="bi bi-mortarboard display-6 text-secondary"></i><div class="fw-semibold mt-3">Belum ada jadwal bimbingan teknis</div></div>
    <?php elseif($tab === 'perbaikan-usulan'): ?>
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Perbaikan Usulan</h2><p class="small text-muted">Daftar perbaikan usulan <?php echo e(strtolower($serviceTitle)); ?>.</p></div><div class="text-center py-5 border rounded bg-light text-muted">Data tidak tersedia!</div>
    <?php elseif($tab === 'catatan-anggaran'): ?>
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Catatan Harian Penggunaan Anggaran</h2><p class="small text-muted">Catatan penggunaan anggaran untuk <?php echo e(strtolower($serviceTitle)); ?>.</p></div><div class="text-center py-5 border rounded bg-light text-muted">Data tidak tersedia!</div>
    <?php elseif($tab === 'catatan-harian'): ?>
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Catatan Harian</h2><p class="small text-muted">Catatan perkembangan kegiatan <?php echo e(strtolower($serviceTitle)); ?>.</p></div><div class="text-center py-5 border rounded bg-light text-muted">Data tidak tersedia!</div>
    <?php elseif($tab === 'laporan-kemajuan'): ?>
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Laporan Kemajuan</h2><p class="small text-muted">Laporan kemajuan <?php echo e(strtolower($serviceTitle)); ?>.</p></div><div class="text-center py-5 border rounded bg-light text-muted">Data tidak tersedia!</div>
    <?php elseif($tab === 'laporan-akhir'): ?>
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Laporan Akhir</h2><p class="small text-muted">Laporan akhir <?php echo e(strtolower($serviceTitle)); ?>.</p></div><div class="text-center py-5 border rounded bg-light text-muted">Data tidak tersedia!</div>
    <?php else: ?>
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Pengkinian Capaian Luaran</h2><p class="small text-muted">Pengkinian capaian luaran <?php echo e(strtolower($serviceTitle)); ?>.</p></div><div class="text-center py-5 border rounded bg-light text-muted">Data tidak tersedia!</div>
    <?php endif; ?>
    <div class="mt-3"><?php echo e($proposals->links()); ?></div>
</div>
<?php elseif($category === 'penelitian'): ?>
<?php
    $researchTabs = [
        'usulan' => ['label' => 'Usulan', 'icon' => 'bi-file-earmark-text'],
        'bimtek' => ['label' => 'Bimbingan Teknis', 'icon' => 'bi-mortarboard'],
        'perbaikan-usulan' => ['label' => 'Perbaikan Usulan', 'icon' => 'bi-pencil-square'],
        'catatan-harian' => ['label' => 'Catatan Harian', 'icon' => 'bi-journal-text'],
        'laporan-kemajuan' => ['label' => 'Laporan Kemajuan', 'icon' => 'bi-graph-up-arrow'],
        'laporan-akhir' => ['label' => 'Laporan Akhir', 'icon' => 'bi-file-earmark-check'],
        'capaian-luaran' => ['label' => 'Pengkinian Capaian Luaran', 'icon' => 'bi-award'],
    ];
    $tabUrl = fn (string $name) => route('dosen.proposals.index', ['kategori' => 'penelitian', 'tab' => $name]);
?>
<div class="card p-3 p-md-4">
    <div class="mb-4">
        <div class="small text-muted mb-1"><i class="bi bi-house-door me-1"></i>Dashboard <i class="bi bi-chevron-right mx-1"></i>Penelitian</div>
        <h1 class="h4 fw-bold text-primary mb-1">Penelitian</h1>
        <p class="small text-muted mb-0">Kelola seluruh tahapan usulan dan pelaksanaan penelitian Anda.</p>
    </div>

    <div class="nav nav-pills gap-2 border-bottom pb-3 mb-4 overflow-auto flex-nowrap" role="tablist">
        <?php $__currentLoopData = $researchTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($tabUrl($key)); ?>" class="nav-link btn btn-sm text-nowrap <?php echo e($tab === $key ? 'active' : 'text-secondary'); ?>">
                <i class="bi <?php echo e($item['icon']); ?> me-1"></i><?php echo e($item['label']); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if($tab === 'usulan'): ?>
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
            <div><h2 class="h5 fw-bold mb-1">Usulan Penelitian</h2><p class="small text-muted mb-0">Daftar usulan penelitian yang telah Anda buat.</p></div>
            <a href="<?php echo e(route('dosen.proposals.create', ['kategori' => 'penelitian'])); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat usulan</a>
        </div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>No.</th><th>Ketua</th><th>Judul</th><th>Skema</th><th>Tahun Pelaksanaan</th><th>Peran</th><th>Status Usulan</th><th>Komentar LPPM</th><th>Komentar Reviewer</th><th>Aksi</th></tr></thead><tbody>
            <?php $__empty_1 = true; $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $completedReviews = $proposal->reviews->where('status_review', 'completed');
                ?>
                <tr>
                    <td><?php echo e($proposals->firstItem() + $loop->index); ?></td>
                    <td><div class="fw-semibold"><?php echo e($proposal->user->name); ?></div><small class="text-muted"><?php echo e($proposal->user->nidn_nip ?: '-'); ?></small></td>
                    <td><div class="fw-semibold"><?php echo e($proposal->judul); ?></div><small class="text-muted"><?php echo e($proposal->kode_registrasi); ?></small></td>
                    <td><?php echo e($proposal->scheme?->nama_skema ?? '-'); ?></td>
                    <td><?php echo e($proposal->scheme?->tahun_anggaran ?? $proposal->created_at->year); ?></td>
                    <td>Ketua</td>
                    <td><span class="badge bg-<?php echo e($proposal->status_badge['color']); ?>"><?php echo e($proposal->status_badge['label']); ?></span></td>
                    <td>
                        <?php if($proposal->catatan_admin): ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#lppmComment<?php echo e($proposal->id); ?>"><i class="bi bi-chat-left-text me-1"></i>Komentar</button>
                        <?php else: ?>
                            <span class="text-muted small">Belum ada</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($completedReviews->isNotEmpty()): ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reviewerComment<?php echo e($proposal->id); ?>"><i class="bi bi-chat-left-text me-1"></i>Komentar</button>
                        <?php else: ?>
                            <span class="text-muted small">Belum ada</span>
                        <?php endif; ?>
                    </td>
                    <td><a href="<?php echo e(route('dosen.proposals.show', $proposal)); ?>" class="btn btn-sm btn-outline-primary" title="Lihat detail usulan" aria-label="Lihat detail <?php echo e($proposal->judul); ?>"><i class="bi bi-eye"></i></a></td>
                </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="10"><div class="text-center py-5"><div class="display-6 text-secondary mb-2"><i class="bi bi-inbox"></i></div><div class="fw-semibold">Belum ada usulan penelitian</div><p class="small text-muted">Mulai dengan membuat usulan penelitian baru.</p><a href="<?php echo e(route('dosen.proposals.create', ['kategori' => 'penelitian'])); ?>" class="btn btn-primary btn-sm">Buat usulan</a></div></td></tr>
            <?php endif; ?>
        </tbody></table></div>
        <?php $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $completedReviews = $proposal->reviews->where('status_review', 'completed');
            ?>
            <?php if($proposal->catatan_admin): ?>
                <div class="modal fade" id="lppmComment<?php echo e($proposal->id); ?>" tabindex="-1" aria-labelledby="lppmCommentTitle<?php echo e($proposal->id); ?>" aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content"><div class="modal-header"><h2 class="modal-title fs-5 text-primary" id="lppmCommentTitle<?php echo e($proposal->id); ?>">Komentar LPPM</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div><div class="modal-body"><div class="small text-muted mb-3">Usulan: <strong><?php echo e($proposal->judul); ?></strong></div><div class="border rounded p-3 bg-light"><div class="fw-semibold mb-2">Catatan LPPM</div><div class="text-break"><?php echo nl2br(e($proposal->catatan_admin)); ?></div></div><?php if($proposal->decided_at): ?><div class="small text-muted mt-3">Diperbarui: <?php echo e($proposal->decided_at->translatedFormat('d F Y, H:i')); ?></div><?php endif; ?></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button></div></div></div></div>
            <?php endif; ?>
            <?php if($completedReviews->isNotEmpty()): ?>
                <div class="modal fade" id="reviewerComment<?php echo e($proposal->id); ?>" tabindex="-1" aria-labelledby="reviewerCommentTitle<?php echo e($proposal->id); ?>" aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content"><div class="modal-header"><h2 class="modal-title fs-5 text-primary" id="reviewerCommentTitle<?php echo e($proposal->id); ?>">Komentar Reviewer</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div><div class="modal-body"><div class="small text-muted mb-3">Usulan: <strong><?php echo e($proposal->judul); ?></strong></div><?php $__currentLoopData = $completedReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="border rounded p-3 mb-3"><div class="d-flex flex-wrap justify-content-between gap-2 mb-2"><strong><?php echo e($review->reviewer->name); ?></strong><span class="badge bg-primary">Skor <?php echo e($review->nilai_total); ?></span></div><div class="small mb-2">Rekomendasi: <strong><?php echo e(ucwords(str_replace('_', ' ', $review->rekomendasi))); ?></strong></div><div class="text-break"><?php echo nl2br(e($review->komentar_reviewer)); ?></div><?php if($review->reviewed_at): ?><div class="small text-muted mt-2">Ditinjau: <?php echo e($review->reviewed_at->translatedFormat('d F Y, H:i')); ?></div><?php endif; ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button></div></div></div></div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php elseif($tab === 'bimtek'): ?>
        <div><h2 class="h5 fw-bold mb-1">Bimbingan Teknis</h2><p class="small text-muted">Informasi bimbingan teknis penelitian dari LPPM akan ditampilkan pada halaman ini.</p></div>
        <div class="text-center py-5 border rounded bg-light"><i class="bi bi-mortarboard display-6 text-secondary"></i><div class="fw-semibold mt-3">Belum ada jadwal bimbingan teknis</div><p class="small text-muted mb-0">Silakan pantau halaman ini untuk pengumuman dari LPPM.</p></div>
    <?php elseif($tab === 'perbaikan-usulan'): ?>
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Perbaikan Usulan</h2><p class="small text-muted mb-0">Unggah dan pantau perbaikan usulan berdasarkan hasil seleksi atau catatan LPPM.</p></div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Usulan</th><th>Catatan LPPM</th><th>Status Perbaikan</th><th>Aksi</th></tr></thead><tbody>
            <?php $__empty_1 = true; $__currentLoopData = $proposals->whereIn('status', ['accepted', 'accepted_revision', 'revision_submitted']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr><td><div class="fw-semibold"><?php echo e($proposal->judul); ?></div><small class="text-muted"><?php echo e($proposal->kode_registrasi); ?></small></td><td><?php echo e($proposal->catatan_admin ?: '-'); ?></td><td><span class="badge bg-<?php echo e($proposal->status_badge['color']); ?>"><?php echo e($proposal->status_badge['label']); ?></span></td><td><a href="<?php echo e(route('dosen.proposals.show', $proposal)); ?>" class="btn btn-sm btn-outline-primary">Buka</a></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada usulan yang memerlukan perbaikan.</td></tr>
            <?php endif; ?>
        </tbody></table></div>
    <?php elseif($tab === 'catatan-harian'): ?>
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Catatan Harian</h2><p class="small text-muted mb-0">Catat perkembangan kegiatan penelitian selama masa pelaksanaan.</p></div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Usulan</th><th>Status</th><th>Jumlah Catatan</th><th>Aksi</th></tr></thead><tbody>
            <?php $__empty_1 = true; $__currentLoopData = $proposals->whereIn('status', ['in_progress', 'progress_submitted', 'progress_revision', 'progress_approved', 'final_submitted', 'completed']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr><td class="fw-semibold"><?php echo e($proposal->judul); ?></td><td><span class="badge bg-<?php echo e($proposal->status_badge['color']); ?>"><?php echo e($proposal->status_badge['label']); ?></span></td><td><?php echo e($proposal->logbooks_count); ?> catatan</td><td><a href="<?php echo e(route('dosen.proposals.show', $proposal)); ?>" class="btn btn-sm btn-outline-primary">Kelola catatan</a></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada penelitian pada tahap pelaksanaan.</td></tr>
            <?php endif; ?>
        </tbody></table></div>
    <?php elseif($tab === 'laporan-kemajuan'): ?>
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Laporan Kemajuan</h2><p class="small text-muted mb-0">Pantau pengunggahan dan evaluasi laporan kemajuan penelitian.</p></div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Usulan</th><th>Kemajuan</th><th>Status Monev</th><th>Aksi</th></tr></thead><tbody>
            <?php $__empty_1 = true; $__currentLoopData = $proposals->whereIn('status', ['in_progress', 'progress_submitted', 'progress_revision', 'progress_approved', 'final_submitted', 'completed']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr><td class="fw-semibold"><?php echo e($proposal->judul); ?></td><td><?php echo e($proposal->progressReport?->persentase_kemajuan ? $proposal->progressReport->persentase_kemajuan . '%' : '-'); ?></td><td><?php echo e($proposal->progressReport?->status_monev ? strtoupper(str_replace('_', ' ', $proposal->progressReport->status_monev)) : 'Belum diunggah'); ?></td><td><a href="<?php echo e(route('dosen.proposals.show', $proposal)); ?>" class="btn btn-sm btn-outline-primary">Buka</a></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada laporan kemajuan yang tersedia.</td></tr>
            <?php endif; ?>
        </tbody></table></div>
    <?php elseif($tab === 'laporan-akhir'): ?>
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Laporan Akhir</h2><p class="small text-muted mb-0">Kelola laporan akhir untuk penelitian yang telah menyelesaikan tahap kemajuan.</p></div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Usulan</th><th>Laporan Akhir</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
            <?php $__empty_1 = true; $__currentLoopData = $proposals->whereIn('status', ['progress_approved', 'final_submitted', 'completed']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr><td class="fw-semibold"><?php echo e($proposal->judul); ?></td><td><?php echo e($proposal->finalReport ? 'Sudah diunggah' : 'Belum diunggah'); ?></td><td><span class="badge bg-<?php echo e($proposal->status_badge['color']); ?>"><?php echo e($proposal->status_badge['label']); ?></span></td><td><a href="<?php echo e(route('dosen.proposals.show', $proposal)); ?>" class="btn btn-sm btn-outline-primary">Buka</a></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada laporan akhir yang tersedia.</td></tr>
            <?php endif; ?>
        </tbody></table></div>
    <?php else: ?>
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Pengkinian Capaian Luaran</h2><p class="small text-muted mb-0">Pantau luaran penelitian yang telah Anda catat pada laporan akhir.</p></div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Usulan</th><th>Jumlah Luaran</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
            <?php $__empty_1 = true; $__currentLoopData = $proposals->whereIn('status', ['progress_approved', 'final_submitted', 'completed']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr><td class="fw-semibold"><?php echo e($proposal->judul); ?></td><td><?php echo e($proposal->outputs->count()); ?> luaran</td><td><span class="badge bg-<?php echo e($proposal->status_badge['color']); ?>"><?php echo e($proposal->status_badge['label']); ?></span></td><td><a href="<?php echo e(route('dosen.proposals.show', $proposal)); ?>" class="btn btn-sm btn-outline-primary">Lihat luaran</a></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data capaian luaran.</td></tr>
            <?php endif; ?>
        </tbody></table></div>
    <?php endif; ?>

    <div class="mt-3"><?php echo e($proposals->links()); ?></div>
</div>
<?php else: ?>
<div class="card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
        <div><div class="small text-muted mb-1"><i class="bi bi-house-door me-1"></i>Dashboard <i class="bi bi-chevron-right mx-1"></i>Usulan</div><h1 class="h4 fw-bold text-primary mb-1"><?php echo e($category ? 'Usulan '.ucfirst($category) : 'Usulan Penelitian & Pengabdian'); ?></h1><p class="small text-muted mb-0">Daftar pengajuan dan status proses di LPPM UNISA Palembang.</p></div>
        <a href="<?php echo e(route('dosen.proposals.create', $category ? ['kategori' => $category] : [])); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat usulan</a>
    </div>
    <div class="d-flex gap-2 border-bottom mb-3 pb-2 flex-wrap">
        <a class="btn btn-sm <?php echo e(!$category ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('dosen.proposals.index')); ?>">Semua</a>
        <a class="btn btn-sm <?php echo e($category === 'penelitian' ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('dosen.proposals.index', ['kategori'=>'penelitian'])); ?>">Penelitian</a>
        <a class="btn btn-sm <?php echo e($category === 'pengabdian' ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('dosen.proposals.index', ['kategori'=>'pengabdian'])); ?>">Pengabdian</a>
        <a class="btn btn-sm <?php echo e($category === 'konsorsium' ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('dosen.proposals.index', ['kategori'=>'konsorsium'])); ?>">Konsorsium</a>
        <a class="btn btn-sm <?php echo e($category === 'prototipe' ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('dosen.proposals.index', ['kategori'=>'prototipe'])); ?>">Prototipe</a>
    </div>
    <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>No.</th><th>Usulan</th><th>Skema</th><th>Tahun</th><th>Dana usulan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr><td><?php echo e($proposals->firstItem() + $loop->index); ?></td><td><div class="fw-semibold"><?php echo e($proposal->judul); ?></div><small class="text-muted"><?php echo e($proposal->kode_registrasi); ?></small></td><td><?php echo e($proposal->scheme?->nama_skema ?? '-'); ?></td><td><?php echo e($proposal->scheme?->tahun_anggaran ?? $proposal->created_at->year); ?></td><td>Rp <?php echo e(number_format($proposal->dana_usulan, 0, ',', '.')); ?></td><td><span class="badge bg-<?php echo e($proposal->status_badge['color']); ?>"><?php echo e($proposal->status_badge['label']); ?></span></td><td><a href="<?php echo e(route('dosen.proposals.show', $proposal)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i>Detail</a></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="7"><div class="text-center py-5"><div class="display-6 text-secondary mb-2"><i class="bi bi-inbox"></i></div><div class="fw-semibold">Belum ada usulan <?php echo e($category ?: 'penelitian atau pengabdian'); ?></div><p class="small text-muted">Usulan yang Anda simpan akan tampil di halaman ini.</p><a href="<?php echo e(route('dosen.proposals.create', $category ? ['kategori' => $category] : [])); ?>" class="btn btn-primary btn-sm">Mulai buat usulan</a></div></td></tr>
        <?php endif; ?>
    </tbody></table></div>
    <div class="mt-3"><?php echo e($proposals->links()); ?></div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIMBIMA UNISA Palembang\resources\views/dosen/proposals/index.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Dashboard Reviewer · SIMBIMA UNISA'); ?>
<?php $__env->startSection('topbar-title', 'Dashboard reviewer'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $user = auth()->user();
    $pendingReviews = $reviews->where('status_review', 'pending')->count();
    $completedReviews = $reviews->where('status_review', 'completed')->count();
    $metricCards = [
        ['label' => 'Total Penugasan', 'value' => $reviews->count(), 'copy' => 'Seluruh usulan ditugaskan', 'icon' => 'bi-clipboard2-check', 'color' => '#ef476f'],
        ['label' => 'Perlu Dinilai', 'value' => $pendingReviews, 'copy' => 'Menunggu penilaian Anda', 'icon' => 'bi-hourglass-split', 'color' => '#0797ad'],
        ['label' => 'Selesai Dinilai', 'value' => $completedReviews, 'copy' => 'Penilaian telah dikirim', 'icon' => 'bi-check2-circle', 'color' => '#f4ad20'],
        ['label' => 'Monev Menunggu', 'value' => $monevReports->count(), 'copy' => 'Laporan perlu diperiksa', 'icon' => 'bi-graph-up-arrow', 'color' => '#08ad65'],
    ];
?>

<div class="row g-4 align-items-start">
    <div class="col-xl-8">
        <section class="card border-0 text-white p-4 p-md-5 mb-4 position-relative overflow-hidden" style="min-height:210px;background:linear-gradient(135deg,#26396f,#1f3268)">
            <div class="position-relative" style="z-index:2">
                <span class="badge px-3 py-2 mb-4" style="background:#ffffff35"><i class="bi bi-calendar3 me-1"></i><?php echo e(now()->translatedFormat('d F Y')); ?></span>
                <h1 class="h3 fw-bold mb-2">Selamat Datang, <?php echo e($user->name); ?>!</h1>
                <p class="fs-5 mb-0 text-white-50">Telaah usulan dan selesaikan monitoring serta evaluasi secara terukur.</p>
            </div>
            <span class="position-absolute rounded-circle" style="width:210px;height:210px;right:-55px;top:-95px;background:#ffffff10"></span>
            <span class="position-absolute rounded-circle" style="width:125px;height:125px;right:85px;bottom:-75px;border:2px solid #ffffff18"></span>
            <i class="bi bi-patch-check position-absolute" style="right:42px;bottom:34px;font-size:42px;color:#ffd34f"></i>
        </section>

        <div class="row g-3 mb-4">
            <?php $__currentLoopData = $metricCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6">
                    <a href="<?php echo e(route('reviewer.reviews.index')); ?>" class="card border-0 text-white text-decoration-none p-4 h-100 position-relative overflow-hidden" style="background:<?php echo e($metric['color']); ?>">
                        <div class="d-flex align-items-center gap-3 position-relative" style="z-index:2">
                            <strong class="display-6 fw-bold mb-0"><?php echo e($metric['value']); ?></strong>
                            <span><strong class="d-block fs-6"><?php echo e($metric['label']); ?></strong><small class="text-white-75"><?php echo e($metric['copy']); ?></small></span>
                        </div>
                        <span class="position-absolute rounded-circle d-grid" style="width:64px;height:64px;right:16px;top:50%;transform:translateY(-50%);place-items:center;background:#ffffff28;font-size:25px"><i class="bi <?php echo e($metric['icon']); ?>"></i></span>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <section class="card p-3 p-md-4">
            <div class="section-header">
                <div><h2 class="dashboard-section-title">Penugasan penilaian</h2><p class="dashboard-section-copy">Daftar usulan yang perlu Anda telaah.</p></div>
                <a href="<?php echo e(route('reviewer.reviews.index')); ?>" class="btn btn-primary">Semua penugasan</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Usulan</th><th>Pengusul</th><th>Status</th><th>Nilai</th><th></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $reviews->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><div class="fw-semibold"><?php echo e(Str::limit($review->proposal?->judul ?? '-', 48)); ?></div><small class="text-muted"><?php echo e($review->proposal?->kode_registrasi ?? '-'); ?></small></td>
                                <td><?php echo e($review->proposal?->user?->name ?? '-'); ?></td>
                                <td><span class="badge bg-<?php echo e($review->status_review === 'completed' ? 'success' : 'warning'); ?>"><?php echo e($review->status_review === 'completed' ? 'Selesai' : 'Menunggu'); ?></span></td>
                                <td><?php echo e($review->nilai_total ?: '—'); ?></td>
                                <td><a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('reviewer.reviews.show', $review)); ?>" aria-label="Buka penilaian"><i class="bi bi-arrow-up-right"></i></a></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="5"><div class="empty-state"><i class="bi bi-clipboard-x"></i><strong>Belum ada penugasan</strong><p>Usulan yang ditugaskan oleh LPPM akan muncul di sini.</p></div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="col-xl-4">
        <section class="card overflow-hidden mb-4">
            <div class="d-flex justify-content-between align-items-center px-4 py-3 text-white" style="background:#26396f"><h2 class="h6 fw-bold mb-0">Profil Saya</h2><a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-sm text-white border-0" style="background:#ffffff32" aria-label="Edit profil"><i class="bi bi-pencil-fill"></i></a></div>
            <div class="p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="rounded-circle d-grid flex-shrink-0" style="width:68px;height:68px;place-items:center;background:#eef1f2;color:#a5afb3;font-size:34px;border:2px solid #e2e7e9"><i class="bi bi-person-fill"></i></span>
                    <div><div class="h5 fw-bold mb-1"><?php echo e($user->name); ?></div><div class="small text-muted"><?php echo e($user->institusi_prodi ?: 'Bidang keahlian belum diisi'); ?></div><div class="small">Universitas Aisyiyah Palembang</div><span class="badge bg-success mt-1">REVIEWER</span></div>
                </div>
                <div class="row g-0 text-center">
                    <div class="col-4 px-2"><div class="small text-muted lh-sm">Total<br>Penugasan</div><div class="h5 fw-bold mb-0 mt-2" style="color:#26396f"><?php echo e($reviews->count()); ?></div></div>
                    <div class="col-4 px-2 border-start border-end"><div class="small text-muted lh-sm">ID<br>SINTA</div><div class="fw-bold mt-2 text-break" style="color:#26396f"><?php echo e($user->sinta_id ?: '—'); ?></div></div>
                    <div class="col-4 px-2"><div class="small text-muted lh-sm">Jabatan<br>Akademik</div><div class="fw-bold mt-2" style="color:#26396f"><?php echo e($user->jabatan_fungsional ?: '—'); ?></div></div>
                </div>
            </div>
        </section>

        <section class="card overflow-hidden">
            <div class="px-4 py-3 text-white" style="background:#26396f"><h2 class="h6 fw-bold mb-0">Aktivitas Reviewer</h2></div>
            <div class="p-4">
                <div class="d-flex justify-content-between border-bottom pb-3 mb-3"><span class="small fw-semibold">Perlu dinilai</span><strong style="color:#26396f"><?php echo e($pendingReviews); ?></strong></div>
                <div class="d-flex justify-content-between border-bottom pb-3 mb-3"><span class="small fw-semibold">Selesai dinilai</span><strong style="color:#26396f"><?php echo e($completedReviews); ?></strong></div>
                <div class="d-flex justify-content-between pb-3"><span class="small fw-semibold">Monev menunggu</span><strong style="color:#26396f"><?php echo e($monevReports->count()); ?></strong></div>

                <?php if($monevReports->isNotEmpty()): ?>
                    <div class="pt-3 border-top"><div class="small fw-bold mb-2" style="color:#26396f">Laporan Monev Terbaru</div>
                        <?php $__currentLoopData = $monevReports->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="<?php echo e(!$loop->last ? 'border-bottom pb-3 mb-3' : ''); ?>"><div class="small fw-semibold"><?php echo e(Str::limit($report->proposal?->judul ?? '-', 38)); ?></div><div class="text-muted mt-1" style="font-size:10px"><?php echo e($report->proposal?->user?->name ?? '-'); ?> · Capaian <?php echo e($report->persentase_kemajuan); ?>%</div></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state py-3"><i class="bi bi-activity"></i><strong>Tidak ada Monev</strong><p>Laporan kemajuan yang masuk akan tampil di sini.</p></div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIMBIMA UNISA Palembang\resources\views/reviewer/dashboard.blade.php ENDPATH**/ ?>
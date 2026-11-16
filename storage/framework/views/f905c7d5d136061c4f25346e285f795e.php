<?php $__env->startSection('title', 'Buat Usulan · SIMBIMA UNISA'); ?>
<?php $__env->startSection('content'); ?>
<div class="card p-4">
    <div class="small text-muted mb-1"><a href="<?php echo e(route('dashboard')); ?>" class="text-decoration-none">Dashboard</a> <i class="bi bi-chevron-right mx-1"></i>Usulan baru</div>
    <h1 class="h4 fw-bold text-primary mb-1">Formulir usulan <?php echo e($category ? ucfirst($category) : 'penelitian / pengabdian'); ?></h1>
    <p class="small text-muted mb-4">Lengkapi data usulan dan unggah berkas proposal untuk diproses oleh LPPM UNISA Palembang.</p>
    <?php if($schemes->isEmpty()): ?>
        <div class="alert alert-info"><i class="bi bi-info-circle me-1"></i>Belum ada skema <?php echo e($category ?: 'hibah'); ?> aktif untuk periode ini. Silakan hubungi LPPM.</div>
    <?php endif; ?>
    <form method="POST" action="<?php echo e(route('dosen.proposals.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Pilih skema hibah *</label>
                <select name="grant_scheme_id" id="grant-scheme-select" class="form-select" required>
                    <option value="">Pilih skema</option>
                    <?php $__currentLoopData = $schemes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>" data-template-url="<?php echo e($s->hasTemplate() ? route('dosen.schemes.template', $s) : ''); ?>" <?php if(old('grant_scheme_id') == $s->id): echo 'selected'; endif; ?>>[<?php echo e(strtoupper($s->kategori)); ?>] <?php echo e($s->nama_skema); ?> (Maks: Rp <?php echo e(number_format($s->plafon_maksimal, 0, ',', '.')); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Lama Kegiatan (Tahun) *</label>
                <select name="lama_kegiatan_tahun" class="form-select">
                    <option value="1">1 Tahun</option>
                    <option value="2">2 Tahun</option>
                    <option value="3">3 Tahun</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Total dana usulan (Rp) *</label>
                <input type="number" name="dana_usulan" class="form-control" value="<?php echo e(old('dana_usulan', 25000000)); ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Judul Penelitian / Pengabdian *</label>
            <input type="text" name="judul" class="form-control" value="<?php echo e(old('judul')); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Abstrak *</label>
            <textarea name="abstrak" rows="4" class="form-control" required><?php echo e(old('abstrak')); ?></textarea>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Kata Kunci (pisahkan dengan koma) *</label>
                <input type="text" name="kata_kunci" class="form-control" placeholder="contoh: machine learning, smart farming, iot" required>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                    <label class="form-label fw-semibold mb-0">Unggah Dokumen Proposal Lengkap (PDF, Maks 10MB) *</label>
                    <a id="proposal-template-link" href="#" class="btn btn-sm btn-outline-primary d-none"><i class="bi bi-download me-1"></i>Unduh template</a>
                </div>
                <input type="file" name="file_proposal" accept="application/pdf" class="form-control" required>
                <small id="proposal-template-message" class="text-muted d-block mt-1">Pilih skema untuk melihat template proposal.</small>
            </div>
        </div>

        <hr>
        <h6 class="fw-bold">Anggota Tim (Opsional)</h6>
        <div class="row g-2 mb-3">
            <div class="col-md-4"><input type="text" name="members[0][nama_anggota]" class="form-control" placeholder="Nama Anggota 1"></div>
            <div class="col-md-3"><input type="text" name="members[0][nidn_nim]" class="form-control" placeholder="NIDN / NIM"></div>
            <div class="col-md-2">
                <select name="members[0][peran]" class="form-select">
                    <option value="anggota_dosen">Anggota Dosen</option>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="mitra">Mitra</option>
                </select>
            </div>
            <div class="col-md-3"><input type="text" name="members[0][tugas]" class="form-control" placeholder="Tugas dalam tim"></div>
        </div>

        <h6 class="fw-bold">Rincian Anggaran Biaya / RAB (Contoh Item)</h6>
        <div class="row g-2 mb-4">
            <div class="col-md-3">
                <select name="budgets[0][kelompok_rab]" class="form-select">
                    <option value="bahan">Bahan Habis Pakai</option>
                    <option value="pengumpulan_data">Pengumpulan Data</option>
                    <option value="analisis_data">Analisis Data</option>
                    <option value="sewa_peralatan">Sewa Peralatan</option>
                    <option value="pelaporan_luaran">Pelaporan & Luaran</option>
                </select>
            </div>
            <div class="col-md-3"><input type="text" name="budgets[0][item_belanja]" class="form-control" placeholder="Nama Item Belanja"></div>
            <div class="col-md-2"><input type="number" name="budgets[0][volume]" class="form-control" value="1" placeholder="Vol"></div>
            <div class="col-md-2"><input type="text" name="budgets[0][satuan]" class="form-control" value="Paket" placeholder="Satuan"></div>
            <div class="col-md-2"><input type="number" name="budgets[0][harga_satuan]" class="form-control" value="5000000" placeholder="Harga"></div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" name="action" value="draft" class="btn btn-outline-secondary" <?php if($schemes->isEmpty()): echo 'disabled'; endif; ?>><i class="bi bi-save me-1"></i>Simpan draf</button>
            <button type="submit" name="action" value="submit" class="btn btn-primary" <?php if($schemes->isEmpty()): echo 'disabled'; endif; ?>><i class="bi bi-send me-1"></i>Kirim ke LPPM</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const schemeSelect = document.getElementById('grant-scheme-select');
    const templateLink = document.getElementById('proposal-template-link');
    const templateMessage = document.getElementById('proposal-template-message');

    const updateTemplateLink = () => {
        const selectedOption = schemeSelect.options[schemeSelect.selectedIndex];
        const templateUrl = selectedOption?.dataset.templateUrl || '';

        if (templateUrl) {
            templateLink.href = templateUrl;
            templateLink.classList.remove('d-none');
            templateMessage.textContent = 'Gunakan template yang sesuai dengan skema pilihan Anda.';
            return;
        }

        templateLink.href = '#';
        templateLink.classList.add('d-none');
        templateMessage.textContent = schemeSelect.value
            ? 'Template untuk skema ini belum tersedia. Silakan hubungi LPPM.'
            : 'Pilih skema untuk melihat template proposal.';
    };

    schemeSelect.addEventListener('change', updateTemplateLink);
    updateTemplateLink();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIMBIMA UNISA Palembang\resources\views/dosen/proposals/create.blade.php ENDPATH**/ ?>
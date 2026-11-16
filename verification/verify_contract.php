<?php
/**
 * Modul Verifikasi Keaslian Nomor Kontrak / SK Hibah Penelitian & Pengabdian
 * Diakses melalui /verification/verify_contract.php?kode=BIMA-2026-0001
 */
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\Proposal;

$kode = htmlspecialchars($_GET['kode'] ?? '', ENT_QUOTES, 'UTF-8');
$proposal = $kode ? Proposal::with(['user', 'scheme'])->where('kode_registrasi', $kode)->orWhere('nomor_kontrak', $kode)->first() : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Dokumen Kontrak Hibah - SIMBIMA UNISA Palembang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container max-w-md" style="max-width: 680px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-bold">
            Verifikasi Keaslian Dokumen Hibah (SIMBIMA UNISA Palembang)
        </div>
        <div class="card-body">
            <form method="GET" class="mb-4 d-flex gap-2">
                <input type="text" name="kode" class="form-control" placeholder="Masukkan Kode Registrasi atau Nomor Kontrak..." value="<?= $kode ?>">
                <button type="submit" class="btn btn-primary">Cek</button>
            </form>
            <?php if ($kode && $proposal): ?>
                <div class="alert alert-success"><strong>DOKUMEN VALID & TERDAFTAR</strong></div>
                <table class="table table-bordered table-sm">
                    <tr><th width="35%">Kode Registrasi</th><td><?= e($proposal->kode_registrasi) ?></td></tr>
                    <tr><th>Nomor Kontrak</th><td><?= e($proposal->nomor_kontrak ?? 'Belum Diterbitkan') ?></td></tr>
                    <tr><th>Judul</th><td><?= e($proposal->judul) ?></td></tr>
                    <tr><th>Ketua Pengusul</th><td><?= e($proposal->user->name) ?> (NIDN: <?= e($proposal->user->nidn_nip) ?>)</td></tr>
                    <tr><th>Skema</th><td><?= e($proposal->scheme->nama_skema) ?> (<?= e($proposal->scheme->tahun_anggaran) ?>)</td></tr>
                    <tr><th>Dana Disetujui</th><td>Rp <?= number_format($proposal->dana_disetujui ?? 0, 0, ',', '.') ?></td></tr>
                    <tr><th>Status Terkini</th><td><span class="badge bg-info text-dark"><?= e(strtoupper($proposal->status)) ?></span></td></tr>
                </table>
            <?php elseif ($kode): ?>
                <div class="alert alert-danger">Data proposal atau nomor kontrak <strong><?= $kode ?></strong> tidak ditemukan.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>

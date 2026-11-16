@extends('layouts.app')
@section('title', 'Detail Usulan Penelitian')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
    <div><div class="small text-muted mb-1"><i class="bi bi-house-door me-1"></i>Dashboard <i class="bi bi-chevron-right mx-1"></i>Penelitian <i class="bi bi-chevron-right mx-1"></i>Detail Usulan</div><h1 class="h4 fw-bold text-primary mb-0">Usulan Penelitian</h1></div>
    <a href="{{ route('dosen.proposals.index', ['kategori' => $proposal->scheme?->kategori ?? 'penelitian']) }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
<div class="card p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <span class="badge bg-secondary mb-1">{{ $proposal->kode_registrasi }}</span>
            <span class="badge bg-{{ $proposal->status_badge['color'] }} mb-1">{{ $proposal->status_badge['label'] }}</span>
            <h4 class="fw-bold mt-1">{{ $proposal->judul }}</h4>
            <p class="text-muted mb-1">Skema: <strong>{{ $proposal->scheme->nama_skema }}</strong> | Nomor Kontrak: <strong>{{ $proposal->nomor_kontrak ?? '-' }}</strong></p>
        </div>
        @if($proposal->status === 'draft')
        <form method="POST" action="{{ route('dosen.proposals.submit', $proposal) }}">
            @csrf
            <button class="btn btn-success">Ajukan Sekarang</button>
        </form>
        @endif
    </div>
    <hr>
    <div class="row g-4"><div class="col-lg-6"><dl class="row mb-0 small"><dt class="col-sm-5 text-muted">Ketua pengusul</dt><dd class="col-sm-7 fw-semibold">{{ $proposal->user->name }}{{ $proposal->user->nidn_nip ? ' (' . $proposal->user->nidn_nip . ')' : '' }}</dd><dt class="col-sm-5 text-muted">Institusi / Prodi</dt><dd class="col-sm-7">{{ $proposal->user->institusi_prodi ?: '-' }}</dd><dt class="col-sm-5 text-muted">Tahun pelaksanaan</dt><dd class="col-sm-7">{{ $proposal->scheme?->tahun_anggaran ?? $proposal->created_at->year }}</dd><dt class="col-sm-5 text-muted">Lama kegiatan</dt><dd class="col-sm-7">{{ $proposal->lama_kegiatan_tahun }} Tahun</dd></dl></div><div class="col-lg-6"><dl class="row mb-0 small"><dt class="col-sm-5 text-muted">Dana usulan</dt><dd class="col-sm-7 fw-semibold">Rp {{ number_format($proposal->dana_usulan, 0, ',', '.') }}</dd><dt class="col-sm-5 text-muted">Dana disetujui</dt><dd class="col-sm-7">{{ $proposal->dana_disetujui !== null ? 'Rp ' . number_format($proposal->dana_disetujui, 0, ',', '.') : '-' }}</dd><dt class="col-sm-5 text-muted">Kata kunci</dt><dd class="col-sm-7">{{ $proposal->kata_kunci }}</dd><dt class="col-sm-5 text-muted">Diajukan pada</dt><dd class="col-sm-7">{{ ($proposal->submitted_at ?? $proposal->created_at)->translatedFormat('d F Y') }}</dd></dl></div></div>
    <div class="mt-4"><h2 class="h6 fw-bold">Abstrak</h2><p class="mb-0 text-break">{!! nl2br(e($proposal->abstrak)) !!}</p></div>
    <div class="d-flex flex-wrap gap-2 mt-4">
        <a href="{{ asset('storage/' . $proposal->file_proposal) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-pdf me-1"></i>Unduh Proposal Awal</a>
        @if($proposal->file_revisi_proposal)
            <a href="{{ asset('storage/' . $proposal->file_revisi_proposal) }}" target="_blank" class="btn btn-sm btn-outline-success">Unduh Revisi Proposal (PDF)</a>
        @endif
    </div>
    @if($proposal->catatan_admin)
        <div class="alert alert-info mt-3 mb-0"><strong>Catatan LPPM:</strong> {{ $proposal->catatan_admin }}</div>
    @endif
</div>

<div class="card p-4 mb-4">
    <h2 class="h5 fw-bold text-primary mb-3"><i class="bi bi-people me-2"></i>Identitas Anggota Tim</h2>
    <div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>No.</th><th>NIDN / NIM</th><th>Nama</th><th>Peran</th><th>Tugas</th></tr></thead><tbody>
        <tr><td>1</td><td>{{ $proposal->user->nidn_nip ?: '-' }}</td><td class="fw-semibold">{{ $proposal->user->name }}</td><td>Ketua</td><td>Ketua pengusul</td></tr>
        @forelse($proposal->members as $member)
            <tr><td>{{ $loop->iteration + 1 }}</td><td>{{ $member->nidn_nim ?: '-' }}</td><td>{{ $member->nama_anggota }}</td><td>{{ ucwords(str_replace('_', ' ', $member->peran)) }}</td><td>{{ $member->tugas ?: '-' }}</td></tr>
        @empty
        @endforelse
    </tbody></table></div>
</div>

<div class="card p-4 mb-4">
    <h2 class="h5 fw-bold text-primary mb-1"><i class="bi bi-wallet2 me-2"></i>Rancangan Anggaran Biaya (RAB)</h2>
    <p class="small text-muted">Total anggaran yang diajukan: <strong>Rp {{ number_format($proposal->dana_usulan, 0, ',', '.') }}</strong></p>
    <div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Kelompok</th><th>Item</th><th>Satuan</th><th>Harga Satuan</th><th>Volume</th><th>Total</th></tr></thead><tbody>
        @forelse($proposal->budgets as $budget)
            <tr><td>{{ ucwords(str_replace('_', ' ', $budget->kelompok_rab)) }}</td><td>{{ $budget->item_belanja }}</td><td>{{ $budget->satuan }}</td><td>Rp {{ number_format($budget->harga_satuan, 0, ',', '.') }}</td><td>{{ $budget->volume }}</td><td class="fw-semibold">Rp {{ number_format($budget->subtotal, 0, ',', '.') }}</td></tr>
        @empty
            <tr><td colspan="6" class="text-center py-4 text-muted">Rincian RAB belum diisi pada usulan ini.</td></tr>
        @endforelse
    </tbody></table></div>
</div>

{{-- Hasil Penilaian Reviewer --}}
@if($proposal->reviews->where('status_review', 'completed')->count() > 0)
<div class="card p-4 mb-4">
    <h5 class="fw-bold text-primary">Hasil Penilaian Substansi Reviewer (Rata-rata: {{ $proposal->averageReviewScore() }})</h5>
    @foreach($proposal->reviews->where('status_review', 'completed') as $idx => $rev)
        <div class="border rounded p-3 mb-2">
            <strong>Reviewer #{{ $idx + 1 }}</strong> — Skor Total: <span class="badge bg-primary">{{ $rev->nilai_total }}</span>
            | Rekomendasi: <span class="badge bg-info text-dark">{{ strtoupper(str_replace('_', ' ', $rev->rekomendasi)) }}</span>
            <p class="mb-0 mt-2 text-muted">{{ $rev->komentar_reviewer }}</p>
        </div>
    @endforeach
</div>
@endif

{{-- Tahap Revisi Proposal setelah Diterima --}}
@if(in_array($proposal->status, ['accepted', 'accepted_revision']))
<div class="card p-4 mb-4 border-start border-4 border-warning">
    <h5 class="fw-bold">Unggah Revisi Proposal & Penyesuaian RAB</h5>
    <p class="small text-muted">Dana disetujui LPPM sebesar <strong>Rp {{ number_format($proposal->dana_disetujui, 0, ',', '.') }}</strong>. Silakan unggah proposal revisi untuk pengesahan kontrak.</p>
    <form method="POST" action="{{ route('dosen.proposals.revision', $proposal) }}" enctype="multipart/form-data" class="row g-2">
        @csrf
        <div class="col-md-8"><input type="file" name="file_revisi_proposal" accept="application/pdf" class="form-control" required></div>
        <div class="col-md-4"><button class="btn btn-warning w-100 fw-semibold">Unggah Proposal Revisi</button></div>
    </form>
</div>
@endif

{{-- Tahap Pelaksanaan: Logbook & Laporan Kemajuan --}}
@if(in_array($proposal->status, ['in_progress', 'progress_submitted', 'progress_revision', 'progress_approved', 'final_submitted', 'completed']))
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card p-4 h-100">
            <h5 class="fw-bold text-primary">Catatan Harian (Logbook)</h5>
            <form method="POST" action="{{ route('dosen.proposals.logbooks.store', $proposal) }}" enctype="multipart/form-data" class="mb-3">
                @csrf
                <div class="row g-2 mb-2">
                    <div class="col-6"><input type="date" name="tanggal_kegiatan" class="form-control form-control-sm" required></div>
                    <div class="col-6"><input type="number" name="persentase_capaian" class="form-control form-control-sm" placeholder="Capaian (%)" min="1" max="100" required></div>
                </div>
                <textarea name="uraian_kegiatan" rows="2" class="form-control form-control-sm mb-2" placeholder="Uraian kegiatan..." required></textarea>
                <button class="btn btn-sm btn-primary">Tambah Logbook</button>
            </form>
            <ul class="list-group list-group-flush small">
                @foreach($proposal->logbooks as $log)
                    <li class="list-group-item px-0">
                        <strong>{{ $log->tanggal_kegiatan->format('d/m/Y') }}</strong> ({{ $log->persentase_capaian }}%): {{ $log->uraian_kegiatan }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-4 h-100">
            <h5 class="fw-bold text-primary">Laporan Kemajuan & Revisi Kemajuan</h5>
            @if($proposal->progressReport)
                <div class="alert alert-secondary small">
                    Status Monev: <strong>{{ strtoupper($proposal->progressReport->status_monev) }}</strong>
                    @if($proposal->progressReport->catatan_monev)
                        <br>Catatan Reviewer Monev: <em>{{ $proposal->progressReport->catatan_monev }}</em>
                    @endif
                </div>
            @endif
            @if(in_array($proposal->status, ['in_progress', 'progress_revision']))
            <form method="POST" action="{{ route('dosen.proposals.progress.store', $proposal) }}" enctype="multipart/form-data">
                @csrf
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
            @endif
        </div>
    </div>
</div>
@endif

{{-- Tahap Laporan Akhir & Luaran --}}
@if(in_array($proposal->status, ['progress_approved', 'final_submitted', 'completed']))
<div class="card p-4">
    <h5 class="fw-bold text-success">Laporan Akhir & Capaian Luaran Wajib/Tambahan</h5>
    @if($proposal->status === 'progress_approved')
    <form method="POST" action="{{ route('dosen.proposals.final.store', $proposal) }}" enctype="multipart/form-data" class="row g-3 mt-1">
        @csrf
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
    @endif

    @if($proposal->outputs->count() > 0)
        <hr>
        <h6 class="fw-bold">Daftar Luaran Tercatat:</h6>
        <ul class="mb-0">
            @foreach($proposal->outputs as $out)
                <li>[{{ strtoupper($out->jenis_luaran) }}] <strong>{{ $out->judul_luaran }}</strong> — Status: <span class="badge bg-success">{{ strtoupper($out->status_capaian) }}</span></li>
            @endforeach
        </ul>
    @endif
</div>
@endif
@endsection

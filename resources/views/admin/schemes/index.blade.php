@extends('layouts.app')
@section('title', 'Kelola Skema Hibah')
@section('content')
<div class="card p-4 mb-4">
    <h5 class="fw-bold text-primary mb-3">Tambah Skema Hibah Baru</h5>
    <form method="POST" action="{{ route('admin.schemes.store') }}" enctype="multipart/form-data" class="row g-3">
        @csrf
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
        <div class="col-md-2"><input type="number" name="tahun_anggaran" value="{{ date('Y') }}" class="form-control" required></div>
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
            @foreach($schemes as $s)
            <tr>
                <td><code>{{ $s->kode_skema }}</code></td>
                <td>{{ $s->nama_skema }}</td>
                <td>{{ ucfirst($s->kategori) }}</td>
                <td>Rp {{ number_format($s->plafon_maksimal, 0, ',', '.') }}</td>
                <td>{{ $s->proposals_count }}</td>
                <td style="min-width:230px">
                    <div class="small mb-2 {{ $s->hasTemplate() ? 'text-success' : 'text-muted' }}"><i class="bi {{ $s->hasTemplate() ? 'bi-check-circle' : 'bi-file-earmark-x' }} me-1"></i>{{ $s->hasTemplate() ? 'Template tersedia' : 'Belum ada template' }}</div>
                    <form method="POST" action="{{ route('admin.schemes.template', $s) }}" enctype="multipart/form-data" class="d-flex gap-1">
                        @csrf
                        <input type="file" name="template_file" accept=".doc,.docx" class="form-control form-control-sm" required aria-label="Template {{ $s->kode_skema }}">
                        <button type="submit" class="btn btn-sm btn-outline-primary">{{ $s->hasTemplate() ? 'Ganti' : 'Pasang' }}</button>
                    </form>
                </td>
                <td>
                    @if($s->proposals_count === 0)
                        <form method="POST" action="{{ route('admin.schemes.destroy', $s) }}" onsubmit="return confirm('Hapus skema {{ addslashes($s->nama_skema) }}? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Hapus</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Skema sudah memiliki usulan"><i class="bi bi-lock me-1"></i>Digunakan</button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

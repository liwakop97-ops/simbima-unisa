@extends('layouts.app')
@section('title', 'Lembar Penilaian Substansi')
@section('content')
<div class="card p-4 mb-4">
    <h5 class="fw-bold">{{ $review->proposal->judul }}</h5>
    <p class="text-muted small">Ketua: {{ $review->proposal->user->name }} | Dana Usulan: Rp {{ number_format($review->proposal->dana_usulan, 0, ',', '.') }}</p>
    <p>{{ $review->proposal->abstrak }}</p>
    <a href="{{ asset('storage/' . $review->proposal->file_proposal) }}" target="_blank" class="btn btn-sm btn-outline-primary" style="width: fit-content;">Unduh Naskah Proposal (PDF)</a>
</div>

<div class="card p-4">
    <h5 class="fw-bold text-primary mb-3">Borang Penilaian Substansi (Skala 1 - 100)</h5>
    <form method="POST" action="{{ route('reviewer.reviews.score', $review) }}">
        @csrf
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label">1. Perumusan Masalah (20%)</label>
                <input type="number" name="skor_rumusan_masalah" min="1" max="100" value="{{ old('skor_rumusan_masalah', $review->skor_rumusan_masalah ?: 80) }}" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">2. Tinjauan Pustaka & State of the Art (15%)</label>
                <input type="number" name="skor_tinjauan_pustaka" min="1" max="100" value="{{ old('skor_tinjauan_pustaka', $review->skor_tinjauan_pustaka ?: 80) }}" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">3. Metode Penelitian / Pengabdian (25%)</label>
                <input type="number" name="skor_metode" min="1" max="100" value="{{ old('skor_metode', $review->skor_metode ?: 80) }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">4. Target Luaran & Kelayakan Publikasi (25%)</label>
                <input type="number" name="skor_luaran" min="1" max="100" value="{{ old('skor_luaran', $review->skor_luaran ?: 80) }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">5. Kewajaran RAB (15%)</label>
                <input type="number" name="skor_rab" min="1" max="100" value="{{ old('skor_rab', $review->skor_rab ?: 80) }}" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Rekomendasi Reviewer</label>
            <select name="rekomendasi" class="form-select" required>
                <option value="layak" @selected($review->rekomendasi === 'layak')>Layak Didanai</option>
                <option value="layak_dengan_revisi" @selected($review->rekomendasi === 'layak_dengan_revisi')>Layak dengan Revisi Proposal / RAB</option>
                <option value="tidak_layak" @selected($review->rekomendasi === 'tidak_layak')>Tidak Layak Didanai</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Komentar & Saran Perbaikan untuk Pengusul</label>
            <textarea name="komentar_reviewer" rows="4" class="form-control" required>{{ old('komentar_reviewer', $review->komentar_reviewer) }}</textarea>
        </div>
        <button class="btn btn-primary fw-semibold">Simpan Hasil Penilaian</button>
    </form>
</div>
@endsection

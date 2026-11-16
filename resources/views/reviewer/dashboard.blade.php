@extends('layouts.app')
@section('title', 'Dashboard Reviewer · SIMBIMA UNISA')
@section('topbar-title', 'Dashboard reviewer')
@section('content')
@php
    $user = auth()->user();
    $pendingReviews = $reviews->where('status_review', 'pending')->count();
    $completedReviews = $reviews->where('status_review', 'completed')->count();
    $metricCards = [
        ['label' => 'Total Penugasan', 'value' => $reviews->count(), 'copy' => 'Seluruh usulan ditugaskan', 'icon' => 'bi-clipboard2-check', 'color' => '#ef476f'],
        ['label' => 'Perlu Dinilai', 'value' => $pendingReviews, 'copy' => 'Menunggu penilaian Anda', 'icon' => 'bi-hourglass-split', 'color' => '#0797ad'],
        ['label' => 'Selesai Dinilai', 'value' => $completedReviews, 'copy' => 'Penilaian telah dikirim', 'icon' => 'bi-check2-circle', 'color' => '#f4ad20'],
        ['label' => 'Monev Menunggu', 'value' => $monevReports->count(), 'copy' => 'Laporan perlu diperiksa', 'icon' => 'bi-graph-up-arrow', 'color' => '#08ad65'],
    ];
@endphp

<div class="row g-4 align-items-start">
    <div class="col-xl-8">
        <section class="card border-0 text-white p-4 p-md-5 mb-4 position-relative overflow-hidden" style="min-height:210px;background:linear-gradient(135deg,#26396f,#1f3268)">
            <div class="position-relative" style="z-index:2">
                <span class="badge px-3 py-2 mb-4" style="background:#ffffff35"><i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d F Y') }}</span>
                <h1 class="h3 fw-bold mb-2">Selamat Datang, {{ $user->name }}!</h1>
                <p class="fs-5 mb-0 text-white-50">Telaah usulan dan selesaikan monitoring serta evaluasi secara terukur.</p>
            </div>
            <span class="position-absolute rounded-circle" style="width:210px;height:210px;right:-55px;top:-95px;background:#ffffff10"></span>
            <span class="position-absolute rounded-circle" style="width:125px;height:125px;right:85px;bottom:-75px;border:2px solid #ffffff18"></span>
            <i class="bi bi-patch-check position-absolute" style="right:42px;bottom:34px;font-size:42px;color:#ffd34f"></i>
        </section>

        <div class="row g-3 mb-4">
            @foreach($metricCards as $metric)
                <div class="col-md-6">
                    <a href="{{ route('reviewer.reviews.index') }}" class="card border-0 text-white text-decoration-none p-4 h-100 position-relative overflow-hidden" style="background:{{ $metric['color'] }}">
                        <div class="d-flex align-items-center gap-3 position-relative" style="z-index:2">
                            <strong class="display-6 fw-bold mb-0">{{ $metric['value'] }}</strong>
                            <span><strong class="d-block fs-6">{{ $metric['label'] }}</strong><small class="text-white-75">{{ $metric['copy'] }}</small></span>
                        </div>
                        <span class="position-absolute rounded-circle d-grid" style="width:64px;height:64px;right:16px;top:50%;transform:translateY(-50%);place-items:center;background:#ffffff28;font-size:25px"><i class="bi {{ $metric['icon'] }}"></i></span>
                    </a>
                </div>
            @endforeach
        </div>

        <section class="card p-3 p-md-4">
            <div class="section-header">
                <div><h2 class="dashboard-section-title">Penugasan penilaian</h2><p class="dashboard-section-copy">Daftar usulan yang perlu Anda telaah.</p></div>
                <a href="{{ route('reviewer.reviews.index') }}" class="btn btn-primary">Semua penugasan</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Usulan</th><th>Pengusul</th><th>Status</th><th>Nilai</th><th></th></tr></thead>
                    <tbody>
                        @forelse($reviews->take(8) as $review)
                            <tr>
                                <td><div class="fw-semibold">{{ Str::limit($review->proposal?->judul ?? '-', 48) }}</div><small class="text-muted">{{ $review->proposal?->kode_registrasi ?? '-' }}</small></td>
                                <td>{{ $review->proposal?->user?->name ?? '-' }}</td>
                                <td><span class="badge bg-{{ $review->status_review === 'completed' ? 'success' : 'warning' }}">{{ $review->status_review === 'completed' ? 'Selesai' : 'Menunggu' }}</span></td>
                                <td>{{ $review->nilai_total ?: '—' }}</td>
                                <td><a class="btn btn-sm btn-outline-primary" href="{{ route('reviewer.reviews.show', $review) }}" aria-label="Buka penilaian"><i class="bi bi-arrow-up-right"></i></a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><div class="empty-state"><i class="bi bi-clipboard-x"></i><strong>Belum ada penugasan</strong><p>Usulan yang ditugaskan oleh LPPM akan muncul di sini.</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="col-xl-4">
        <section class="card overflow-hidden mb-4">
            <div class="d-flex justify-content-between align-items-center px-4 py-3 text-white" style="background:#26396f"><h2 class="h6 fw-bold mb-0">Profil Saya</h2><a href="{{ route('profile.edit') }}" class="btn btn-sm text-white border-0" style="background:#ffffff32" aria-label="Edit profil"><i class="bi bi-pencil-fill"></i></a></div>
            <div class="p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="rounded-circle d-grid flex-shrink-0" style="width:68px;height:68px;place-items:center;background:#eef1f2;color:#a5afb3;font-size:34px;border:2px solid #e2e7e9"><i class="bi bi-person-fill"></i></span>
                    <div><div class="h5 fw-bold mb-1">{{ $user->name }}</div><div class="small text-muted">{{ $user->institusi_prodi ?: 'Bidang keahlian belum diisi' }}</div><div class="small">Universitas Aisyiyah Palembang</div><span class="badge bg-success mt-1">REVIEWER</span></div>
                </div>
                <div class="row g-0 text-center">
                    <div class="col-4 px-2"><div class="small text-muted lh-sm">Total<br>Penugasan</div><div class="h5 fw-bold mb-0 mt-2" style="color:#26396f">{{ $reviews->count() }}</div></div>
                    <div class="col-4 px-2 border-start border-end"><div class="small text-muted lh-sm">ID<br>SINTA</div><div class="fw-bold mt-2 text-break" style="color:#26396f">{{ $user->sinta_id ?: '—' }}</div></div>
                    <div class="col-4 px-2"><div class="small text-muted lh-sm">Jabatan<br>Akademik</div><div class="fw-bold mt-2" style="color:#26396f">{{ $user->jabatan_fungsional ?: '—' }}</div></div>
                </div>
            </div>
        </section>

        <section class="card overflow-hidden">
            <div class="px-4 py-3 text-white" style="background:#26396f"><h2 class="h6 fw-bold mb-0">Aktivitas Reviewer</h2></div>
            <div class="p-4">
                <div class="d-flex justify-content-between border-bottom pb-3 mb-3"><span class="small fw-semibold">Perlu dinilai</span><strong style="color:#26396f">{{ $pendingReviews }}</strong></div>
                <div class="d-flex justify-content-between border-bottom pb-3 mb-3"><span class="small fw-semibold">Selesai dinilai</span><strong style="color:#26396f">{{ $completedReviews }}</strong></div>
                <div class="d-flex justify-content-between pb-3"><span class="small fw-semibold">Monev menunggu</span><strong style="color:#26396f">{{ $monevReports->count() }}</strong></div>

                @if($monevReports->isNotEmpty())
                    <div class="pt-3 border-top"><div class="small fw-bold mb-2" style="color:#26396f">Laporan Monev Terbaru</div>
                        @foreach($monevReports->take(3) as $report)
                            <div class="{{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}"><div class="small fw-semibold">{{ Str::limit($report->proposal?->judul ?? '-', 38) }}</div><div class="text-muted mt-1" style="font-size:10px">{{ $report->proposal?->user?->name ?? '-' }} · Capaian {{ $report->persentase_kemajuan }}%</div></div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state py-3"><i class="bi bi-activity"></i><strong>Tidak ada Monev</strong><p>Laporan kemajuan yang masuk akan tampil di sini.</p></div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', (auth()->user()->isAdmin() ? 'Dashboard Admin' : 'Dashboard LPPM') . ' · SIMBIMA UNISA')
@section('topbar-title', auth()->user()->isAdmin() ? 'Dashboard administrator' : 'Dashboard LPPM')
@section('content')
@php
    $user = auth()->user();
    $isAdmin = $user->isAdmin();
    $metricCards = [
        ['label' => 'Total Usulan', 'value' => $stats['total_proposals'], 'copy' => 'Seluruh usulan terdaftar', 'icon' => 'bi-inboxes', 'color' => '#ef476f', 'url' => route('admin.proposals.index')],
        ['label' => 'Sedang Direview', 'value' => $stats['under_review'], 'copy' => 'Usulan dalam penilaian', 'icon' => 'bi-hourglass-split', 'color' => '#0797ad', 'url' => route('admin.proposals.index', ['status' => 'under_review'])],
        ['label' => 'Usulan Didanai', 'value' => $stats['accepted'], 'copy' => 'Usulan diterima dan berjalan', 'icon' => 'bi-check2-circle', 'color' => '#f4ad20', 'url' => route('admin.proposals.index')],
        $isAdmin
            ? ['label' => 'Akun Menunggu', 'value' => $stats['pending_accounts'], 'copy' => 'Perlu verifikasi Admin', 'icon' => 'bi-person-check', 'color' => '#08ad65', 'url' => route('admin.accounts.requests')]
            : ['label' => 'Kegiatan Selesai', 'value' => $stats['completed'], 'copy' => 'Usulan telah diselesaikan', 'icon' => 'bi-flag', 'color' => '#08ad65', 'url' => route('admin.proposals.index', ['status' => 'completed'])],
    ];
    $activeSchemes = $schemes->where('is_active', true)->take(4);
@endphp

<div class="row g-4 align-items-start">
    <div class="col-xl-8">
        <section class="card border-0 text-white p-4 p-md-5 mb-4 position-relative overflow-hidden" style="min-height:210px;background:linear-gradient(135deg,#26396f,#1f3268)">
            <div class="position-relative" style="z-index:2">
                <span class="badge px-3 py-2 mb-4" style="background:#ffffff35"><i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d F Y') }}</span>
                <h1 class="h3 fw-bold mb-2">Selamat Datang, {{ $user->name }}!</h1>
                <p class="fs-5 mb-0 text-white-50">{{ $isAdmin ? 'Kelola pengguna, hak akses, dan operasional sistem dari satu tempat.' : 'Pantau usulan, penilaian, pendanaan, dan laporan kegiatan LPPM.' }}</p>
            </div>
            <span class="position-absolute rounded-circle" style="width:210px;height:210px;right:-55px;top:-95px;background:#ffffff10"></span>
            <span class="position-absolute rounded-circle" style="width:125px;height:125px;right:85px;bottom:-75px;border:2px solid #ffffff18"></span>
            <i class="bi {{ $isAdmin ? 'bi-shield-check' : 'bi-stars' }} position-absolute" style="right:42px;bottom:34px;font-size:42px;color:#ffd34f"></i>
        </section>

        <div class="row g-3 mb-4">
            @foreach($metricCards as $metric)
                <div class="col-md-6">
                    <a href="{{ $metric['url'] }}" class="card border-0 text-white text-decoration-none p-4 h-100 position-relative overflow-hidden" style="background:{{ $metric['color'] }}">
                        <div class="d-flex align-items-center gap-3 position-relative" style="z-index:2">
                            <strong class="display-6 fw-bold mb-0">{{ $metric['value'] }}</strong>
                            <span><strong class="d-block fs-6">{{ $metric['label'] }}</strong><small class="text-white-75">{{ $metric['copy'] }}</small></span>
                        </div>
                        <span class="position-absolute rounded-circle d-grid" style="width:64px;height:64px;right:16px;top:50%;transform:translateY(-50%);place-items:center;background:#ffffff28;font-size:25px"><i class="bi {{ $metric['icon'] }}"></i></span>
                    </a>
                </div>
            @endforeach
        </div>

        <section class="card p-3 p-md-4 mb-4">
            <div class="section-header">
                <div><h2 class="dashboard-section-title">Usulan terbaru</h2><p class="dashboard-section-copy">Pantau dan lanjutkan proses administrasi usulan.</p></div>
                <a href="{{ route('admin.proposals.index') }}" class="btn btn-primary">Kelola usulan</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Usulan</th><th>Ketua</th><th>Skema</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse($recentProposals as $proposal)
                            <tr>
                                <td><div class="fw-semibold">{{ Str::limit($proposal->judul, 48) }}</div><small class="text-muted">{{ $proposal->kode_registrasi }}</small></td>
                                <td>{{ $proposal->user?->name ?? '-' }}</td>
                                <td>{{ $proposal->scheme?->kode_skema ?? '-' }}</td>
                                <td><span class="badge bg-{{ $proposal->status_badge['color'] }}">{{ $proposal->status_badge['label'] }}</span></td>
                                <td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.proposals.show', $proposal) }}" aria-label="Lihat usulan"><i class="bi bi-arrow-up-right"></i></a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><div class="empty-state"><i class="bi bi-inbox"></i><strong>Belum ada usulan</strong><p>Usulan yang masuk akan ditampilkan di sini.</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card p-3 p-md-4">
            <div class="section-header"><div><h2 class="dashboard-section-title">Skema aktif</h2><p class="dashboard-section-copy">Skema hibah yang sedang tersedia.</p></div><a href="{{ route('admin.schemes.index') }}" class="small fw-semibold text-decoration-none">Kelola skema <i class="bi bi-arrow-right"></i></a></div>
            @forelse($activeSchemes as $scheme)
                <div class="d-flex justify-content-between align-items-center border-bottom py-3 gap-2"><div><div class="fw-semibold small">{{ $scheme->nama_skema }}</div><div class="text-muted" style="font-size:10px">{{ ucfirst($scheme->kategori) }} · {{ $scheme->proposals_count }} usulan</div></div><span class="badge text-bg-light border">Rp {{ number_format($scheme->plafon_maksimal / 1000000, 0, ',', '.') }} jt</span></div>
            @empty
                <div class="empty-state"><i class="bi bi-calendar-x"></i><strong>Belum ada skema aktif</strong><p>Skema aktif akan ditampilkan di sini.</p></div>
            @endforelse
        </section>
    </div>

    <div class="col-xl-4">
        <section class="card overflow-hidden mb-4">
            <div class="d-flex justify-content-between align-items-center px-4 py-3 text-white" style="background:#26396f"><h2 class="h6 fw-bold mb-0">Profil Saya</h2><a href="{{ route('profile.edit') }}" class="btn btn-sm text-white border-0" style="background:#ffffff32" aria-label="Edit profil"><i class="bi bi-pencil-fill"></i></a></div>
            <div class="p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="rounded-circle d-grid flex-shrink-0" style="width:68px;height:68px;place-items:center;background:#eef1f2;color:#a5afb3;font-size:34px;border:2px solid #e2e7e9"><i class="bi {{ $isAdmin ? 'bi-shield-lock-fill' : 'bi-person-fill' }}"></i></span>
                    <div><div class="h5 fw-bold mb-1">{{ $user->name }}</div><div class="small text-muted">{{ $isAdmin ? 'Administrator Sistem' : 'Operator LPPM' }}</div><div class="small">Universitas Aisyiyah Palembang</div><span class="badge bg-success mt-1">{{ $isAdmin ? 'ADMIN' : 'LPPM' }}</span></div>
                </div>
                <div class="row g-0 text-center">
                    @if($isAdmin)
                        <div class="col-4 px-2"><div class="small text-muted lh-sm">Total<br>Pengguna</div><div class="h5 fw-bold mb-0 mt-2" style="color:#26396f">{{ $stats['total_users'] }}</div></div>
                        <div class="col-4 px-2 border-start border-end"><div class="small text-muted lh-sm">Total<br>Usulan</div><div class="h5 fw-bold mb-0 mt-2" style="color:#26396f">{{ $stats['total_proposals'] }}</div></div>
                        <div class="col-4 px-2"><div class="small text-muted lh-sm">Akun<br>Menunggu</div><div class="h5 fw-bold mb-0 mt-2" style="color:#26396f">{{ $stats['pending_accounts'] }}</div></div>
                    @else
                        <div class="col-4 px-2"><div class="small text-muted lh-sm">Total<br>Usulan</div><div class="h5 fw-bold mb-0 mt-2" style="color:#26396f">{{ $stats['total_proposals'] }}</div></div>
                        <div class="col-4 px-2 border-start border-end"><div class="small text-muted lh-sm">Usulan<br>Didanai</div><div class="h5 fw-bold mb-0 mt-2" style="color:#26396f">{{ $stats['accepted'] }}</div></div>
                        <div class="col-4 px-2"><div class="small text-muted lh-sm">Kegiatan<br>Selesai</div><div class="h5 fw-bold mb-0 mt-2" style="color:#26396f">{{ $stats['completed'] }}</div></div>
                    @endif
                </div>
            </div>
        </section>

        <section class="card overflow-hidden">
            <div class="px-4 py-3 text-white" style="background:#26396f"><h2 class="h6 fw-bold mb-0">{{ $isAdmin ? 'Kontrol Administrator' : 'Ringkasan Operasional' }}</h2></div>
            <div class="p-4">
                @if($isAdmin)
                    <a href="{{ route('admin.accounts.requests') }}" class="d-flex align-items-center gap-3 border-bottom pb-3 mb-3 text-decoration-none"><i class="bi bi-person-check fs-4 text-success"></i><span><strong class="d-block small" style="color:#26396f">Verifikasi akun</strong><small class="text-muted">{{ $stats['pending_accounts'] }} permintaan menunggu</small></span><i class="bi bi-chevron-right ms-auto text-muted"></i></a>
                    <a href="{{ route('admin.users.index') }}" class="d-flex align-items-center gap-3 border-bottom pb-3 mb-3 text-decoration-none"><i class="bi bi-people fs-4 text-primary"></i><span><strong class="d-block small" style="color:#26396f">Kelola pengguna</strong><small class="text-muted">Edit akun dan pembagian role</small></span><i class="bi bi-chevron-right ms-auto text-muted"></i></a>
                    <a href="{{ route('admin.schemes.index') }}" class="d-flex align-items-center gap-3 text-decoration-none"><i class="bi bi-journal-plus fs-4 text-warning"></i><span><strong class="d-block small" style="color:#26396f">Kelola skema</strong><small class="text-muted">Atur periode dan skema hibah</small></span><i class="bi bi-chevron-right ms-auto text-muted"></i></a>
                @else
                    <div class="d-flex justify-content-between border-bottom pb-3 mb-3"><span class="small fw-semibold">Menunggu verifikasi</span><strong style="color:#26396f">{{ $stats['submitted'] }}</strong></div>
                    <div class="d-flex justify-content-between border-bottom pb-3 mb-3"><span class="small fw-semibold">Sedang direview</span><strong style="color:#26396f">{{ $stats['under_review'] }}</strong></div>
                    <div class="d-flex justify-content-between border-bottom pb-3 mb-3"><span class="small fw-semibold">Ditolak</span><strong style="color:#26396f">{{ $stats['rejected'] }}</strong></div>
                    <a href="{{ route('admin.proposals.index') }}" class="btn btn-outline-primary w-100 mb-2">Kelola usulan</a>
                    <a href="{{ route('admin.schemes.index') }}" class="btn btn-outline-primary w-100">Kelola skema</a>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection

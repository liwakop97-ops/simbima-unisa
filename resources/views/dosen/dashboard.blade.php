@extends('layouts.app')
@section('title', 'Dashboard Dosen · SIMBIMA UNISA')
@section('topbar-title', 'Dashboard dosen')
@section('content')
@php
    $user = auth()->user();
    $researchCount = $proposals->filter(fn ($proposal) => $proposal->scheme?->kategori === 'penelitian')->count();
    $serviceCount = $proposals->filter(fn ($proposal) => $proposal->scheme?->kategori === 'pengabdian')->count();
    $consortiumCount = $proposals->filter(fn ($proposal) => $proposal->scheme?->kategori === 'konsorsium')->count();
    $prototypeCount = $proposals->filter(fn ($proposal) => $proposal->scheme?->kategori === 'prototipe')->count();
    $sintaScore = null;
    $proposalCategories = [
        'penelitian' => 'Penelitian',
        'pengabdian' => 'Pengabdian kepada Masyarakat',
        'konsorsium' => 'Konsorsium',
        'prototipe' => 'Prototipe',
    ];
    $metricCards = [
        ['category' => 'penelitian', 'label' => 'Penelitian', 'count' => $researchCount, 'icon' => 'bi-search', 'color' => '#ef476f'],
        ['category' => 'pengabdian', 'label' => 'Pengabdian', 'count' => $serviceCount, 'icon' => 'bi-file-earmark-search', 'color' => '#0797ad'],
        ['category' => 'konsorsium', 'label' => 'Konsorsium', 'count' => $consortiumCount, 'icon' => 'bi-diagram-3', 'color' => '#f4ad20'],
        ['category' => 'prototipe', 'label' => 'Prototipe', 'count' => $prototypeCount, 'icon' => 'bi-box-seam', 'color' => '#08ad65'],
    ];
@endphp

<div class="row g-4 align-items-start">
    <div class="col-xl-8">
        <section class="card border-0 text-white p-4 p-md-5 mb-4 position-relative overflow-hidden" style="min-height:210px;background:linear-gradient(135deg,#26396f,#1f3268)">
            <div class="position-relative" style="z-index:2">
                <span class="badge px-3 py-2 mb-4" style="background:#ffffff35"><i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d F Y') }}</span>
                <h1 class="h3 fw-bold mb-2">Selamat Datang, {{ $user->name }}!</h1>
                <p class="fs-5 mb-0 text-white-50">Semoga harimu menyenangkan dan produktif.</p>
            </div>
            <span class="position-absolute rounded-circle" style="width:210px;height:210px;right:-55px;top:-95px;background:#ffffff10"></span>
            <span class="position-absolute rounded-circle" style="width:125px;height:125px;right:85px;bottom:-75px;border:2px solid #ffffff18"></span>
            <i class="bi bi-stars position-absolute" style="right:42px;bottom:34px;font-size:42px;color:#ffd34f"></i>
        </section>

        <div class="row g-3 mb-4">
            @foreach($metricCards as $metric)
                <div class="col-md-6">
                    <a href="{{ route('dosen.proposals.index', ['kategori' => $metric['category']]) }}" class="card border-0 text-white text-decoration-none p-4 h-100 position-relative overflow-hidden" style="background:{{ $metric['color'] }}">
                        <div class="d-flex align-items-center gap-3 position-relative" style="z-index:2">
                            <strong class="display-6 fw-bold mb-0">{{ $metric['count'] }}</strong>
                            <span><strong class="d-block fs-6">{{ $metric['label'] }}</strong><small class="text-white-75">Total usulan Anda</small></span>
                        </div>
                        <span class="position-absolute rounded-circle d-grid" style="width:64px;height:64px;right:16px;top:50%;transform:translateY(-50%);place-items:center;background:#ffffff28;font-size:25px"><i class="bi {{ $metric['icon'] }}"></i></span>
                    </a>
                </div>
            @endforeach
        </div>

        <section class="card p-3 p-md-4 mb-4">
            <div class="section-header">
                <div><h2 class="dashboard-section-title">Usulan terbaru</h2><p class="dashboard-section-copy">Pantau status setiap proposal yang Anda ajukan.</p></div>
                <a href="{{ route('dosen.proposals.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat usulan</a>
            </div>
            <div class="table-responsive"><table class="table"><thead><tr><th>Usulan</th><th>Skema</th><th>Status</th><th></th></tr></thead><tbody>
                @forelse($proposals->take(5) as $proposal)
                    <tr><td><div class="fw-semibold">{{ Str::limit($proposal->judul, 56) }}</div><small class="text-muted">{{ $proposal->kode_registrasi }}</small></td><td>{{ $proposal->scheme?->nama_skema ?? '-' }}</td><td><span class="badge bg-{{ $proposal->status_badge['color'] }}">{{ $proposal->status_badge['label'] }}</span></td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('dosen.proposals.show', $proposal) }}"><i class="bi bi-arrow-up-right"></i></a></td></tr>
                @empty
                    <tr><td colspan="4"><div class="empty-state"><i class="bi bi-folder2-open"></i><strong>Belum ada usulan</strong><p>Mulai dengan memilih skema yang tersedia.</p></div></td></tr>
                @endforelse
            </tbody></table></div>
            <div class="mt-3"><a class="small fw-semibold text-decoration-none" href="{{ route('dosen.proposals.index') }}">Lihat semua usulan <i class="bi bi-arrow-right"></i></a></div>
        </section>

        <section class="card p-3 p-md-4">
            <div class="section-header"><div><h2 class="dashboard-section-title">Skema aktif</h2><p class="dashboard-section-copy">Peluang yang masih terbuka.</p></div><i class="bi bi-calendar2-check text-success fs-5"></i></div>
            @forelse($activeSchemes->take(4) as $scheme)
                <div class="d-flex justify-content-between align-items-center border-bottom py-3 gap-2"><div><div class="fw-semibold small">{{ $scheme->nama_skema }}</div><div class="text-muted" style="font-size:10px">{{ ucfirst($scheme->kategori) }} · Tutup {{ $scheme->tanggal_tutup?->format('d M Y') }}</div></div><span class="badge text-bg-light border">Rp {{ number_format($scheme->plafon_maksimal / 1000000, 0, ',', '.') }} jt</span></div>
            @empty
                <div class="empty-state"><i class="bi bi-calendar-x"></i><strong>Belum ada skema aktif</strong><p>Informasi akan muncul setelah LPPM membuka periode.</p></div>
            @endforelse
        </section>
    </div>

    <div class="col-xl-4">
        <section class="card overflow-hidden mb-4">
            <div class="d-flex justify-content-between align-items-center px-4 py-3 text-white" style="background:#26396f"><h2 class="h6 fw-bold mb-0">Profil Saya</h2><a href="{{ route('profile.edit') }}" class="btn btn-sm text-white border-0" style="background:#ffffff32" aria-label="Edit profil"><i class="bi bi-pencil-fill"></i></a></div>
            <div class="p-4">
                <div class="d-flex align-items-center gap-3 mb-4"><span class="rounded-circle d-grid flex-shrink-0" style="width:68px;height:68px;place-items:center;background:#eef1f2;color:#a5afb3;font-size:34px;border:2px solid #e2e7e9"><i class="bi bi-person-fill"></i></span><div><div class="h5 fw-bold mb-1">{{ $user->name }}</div><div class="small text-muted">{{ $user->institusi_prodi ?: 'Program studi belum diisi' }}</div><div class="small">Universitas Aisyiyah Palembang</div>@if($user->nidn_nip)<span class="badge bg-success mt-1">NIDN/NIP terisi</span>@endif</div></div>
                <div class="row g-0 text-center"><div class="col-4 px-2"><div class="small text-muted lh-sm">SINTA Score<br>Overall</div><div class="h5 fw-bold mb-0 mt-2" style="color:#26396f">{{ $sintaScore ?: '—' }}</div></div><div class="col-4 px-2 border-start border-end"><div class="small text-muted lh-sm">ID<br>SINTA</div><div class="fw-bold mt-2 text-break" style="color:#26396f">{{ $user->sinta_id ?: '—' }}</div></div><div class="col-4 px-2"><div class="small text-muted lh-sm">Jabatan<br>Akademik</div><div class="fw-bold mt-2" style="color:#26396f">{{ $user->jabatan_fungsional ?: '—' }}</div></div></div>
                @if($user->sinta_id)<div class="text-center mt-4"><a href="https://sinta.kemdiktisaintek.go.id/authors/profile/{{ rawurlencode($user->sinta_id) }}" class="small fw-semibold text-decoration-none" target="_blank" rel="noopener">Buka profil resmi SINTA <i class="bi bi-box-arrow-up-right ms-1"></i></a></div>@endif
            </div>
        </section>

        <section class="card overflow-hidden">
            <div class="px-4 py-3 text-white" style="background:#26396f"><h2 class="h6 fw-bold mb-0">Riwayat Usulan</h2></div>
            <div class="p-4">
                @foreach($proposalCategories as $category => $label)
                    @php
                        $categoryProposals = $proposals->filter(fn ($proposal) => $proposal->scheme?->kategori === $category);
                    @endphp
                    <div class="{{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}"><div class="d-flex justify-content-between align-items-center gap-2"><strong class="small" style="color:#26396f">{{ $label }}</strong><a class="small text-decoration-none" href="{{ route('dosen.proposals.index', ['kategori' => $category]) }}">Lihat</a></div>@if($categoryProposals->isNotEmpty())<div class="small text-muted mt-2"><i class="bi bi-file-earmark-text me-1"></i>{{ $categoryProposals->count() }} usulan · {{ Str::limit($categoryProposals->first()->judul, 35) }}</div>@else<div class="small text-muted mt-2"><i class="bi bi-circle me-2" style="font-size:5px"></i>Tidak ada</div>@endif</div>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection

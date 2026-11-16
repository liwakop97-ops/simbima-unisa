@extends('layouts.app')
@section('title', ($category === 'penelitian' ? 'Penelitian' : ($category === 'pengabdian' ? ($program ?? 'Pengabdian') : ($category === 'konsorsium' ? ($program ?? 'Konsorsium') : 'Usulan Saya'))) . ' · SIMBIMA UNISA')
@section('content')
@if(in_array($category, ['pengabdian', 'konsorsium'], true))
@php
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
@endphp
<div class="card p-3 p-md-4">
    <div class="mb-4"><div class="small text-muted mb-1"><i class="bi bi-house-door me-1"></i>Dashboard <i class="bi bi-chevron-right mx-1"></i>{{ $isConsortium ? 'Konsorsium' : 'Pengabdian' }}</div><h1 class="h4 fw-bold text-primary mb-1">{{ $serviceTitle }}</h1><p class="small text-muted mb-0">{{ $isConsortium ? 'Kelola seluruh tahapan usulan riset konsorsium.' : 'Kelola seluruh tahapan usulan pengabdian kepada masyarakat.' }}</p></div>
    <div class="nav nav-pills gap-2 border-bottom pb-3 mb-4 overflow-auto flex-nowrap" role="tablist">
        @foreach($serviceTabs as $key => $item)<a href="{{ $tabUrl($key) }}" class="nav-link btn btn-sm text-nowrap {{ $tab === $key ? 'active' : 'text-secondary' }}"><i class="bi {{ $item['icon'] }} me-1"></i>{{ $item['label'] }}</a>@endforeach
    </div>
    @if($tab === 'usulan')
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3"><div><h2 class="h5 fw-bold mb-1">{{ $isConsortium ? 'Usulan ' . $serviceTitle . ($program === 'Riset Konsorsium Unggulan' ? ' Berdampak (RIKUB)' : '') : 'Usulan Pengabdian' }}</h2><p class="small text-muted mb-0">Daftar usulan {{ $isConsortium ? 'riset konsorsium' : strtolower($serviceTitle) }} yang telah Anda buat.</p></div><a href="{{ route('dosen.proposals.create', ['kategori' => $category]) }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat usulan</a></div>
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3"><button type="button" class="btn btn-info text-white"><i class="bi bi-info-circle me-1"></i>Info Eligibility</button><div><label class="form-label small mb-1">Tahun Pelaksanaan</label><div class="input-group"><span class="input-group-text bg-primary text-white"><i class="bi bi-funnel"></i></span><input type="text" class="form-control" value="{{ now()->year }}" readonly></div></div></div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>No.</th><th>Judul</th><th>Skema</th><th>Tahun Usulan</th><th>Tahun Pelaksanaan</th><th>Peran</th><th>Status Usulan</th><th>Status Validasi Luaran</th><th>Komentar</th><th>Aksi</th></tr></thead><tbody>
            @forelse($proposals as $proposal)
                @php
                    $completedReviews = $proposal->reviews->where('status_review', 'completed');
                @endphp
                <tr><td>{{ $proposals->firstItem() + $loop->index }}</td><td><div class="fw-semibold">{{ $proposal->judul }}</div><small class="text-muted">{{ $proposal->kode_registrasi }}</small></td><td>{{ $proposal->scheme?->nama_skema ?? '-' }}</td><td>{{ $proposal->created_at->year }}</td><td>{{ $proposal->scheme?->tahun_anggaran ?? $proposal->created_at->year }}</td><td>Ketua</td><td><span class="badge bg-{{ $proposal->status_badge['color'] }}">{{ $proposal->status_badge['label'] }}</span></td><td>{{ $proposal->outputs->isNotEmpty() ? 'Sudah diisi' : 'Belum diisi' }}</td><td>@if($proposal->catatan_admin || $completedReviews->isNotEmpty())<a href="{{ route('dosen.proposals.show', $proposal) }}#komentar" class="btn btn-sm btn-primary"><i class="bi bi-chat-left-text me-1"></i>Komentar</a>@else<span class="text-muted small">-</span>@endif</td><td><a href="{{ route('dosen.proposals.show', $proposal) }}" class="btn btn-sm btn-outline-primary" title="Lihat detail"><i class="bi bi-eye"></i></a></td></tr>
            @empty
                <tr><td colspan="10" class="text-center py-4 text-muted">Data tidak tersedia!</td></tr>
            @endforelse
        </tbody></table></div>
    @elseif($tab === 'bimtek')
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Bimbingan Teknis</h2><p class="small text-muted">Informasi bimbingan teknis {{ strtolower($serviceTitle) }} akan ditampilkan di sini.</p></div><div class="text-center py-5 border rounded bg-light"><i class="bi bi-mortarboard display-6 text-secondary"></i><div class="fw-semibold mt-3">Belum ada jadwal bimbingan teknis</div></div>
    @elseif($tab === 'perbaikan-usulan')
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Perbaikan Usulan</h2><p class="small text-muted">Daftar perbaikan usulan {{ strtolower($serviceTitle) }}.</p></div><div class="text-center py-5 border rounded bg-light text-muted">Data tidak tersedia!</div>
    @elseif($tab === 'catatan-anggaran')
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Catatan Harian Penggunaan Anggaran</h2><p class="small text-muted">Catatan penggunaan anggaran untuk {{ strtolower($serviceTitle) }}.</p></div><div class="text-center py-5 border rounded bg-light text-muted">Data tidak tersedia!</div>
    @elseif($tab === 'catatan-harian')
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Catatan Harian</h2><p class="small text-muted">Catatan perkembangan kegiatan {{ strtolower($serviceTitle) }}.</p></div><div class="text-center py-5 border rounded bg-light text-muted">Data tidak tersedia!</div>
    @elseif($tab === 'laporan-kemajuan')
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Laporan Kemajuan</h2><p class="small text-muted">Laporan kemajuan {{ strtolower($serviceTitle) }}.</p></div><div class="text-center py-5 border rounded bg-light text-muted">Data tidak tersedia!</div>
    @elseif($tab === 'laporan-akhir')
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Laporan Akhir</h2><p class="small text-muted">Laporan akhir {{ strtolower($serviceTitle) }}.</p></div><div class="text-center py-5 border rounded bg-light text-muted">Data tidak tersedia!</div>
    @else
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Pengkinian Capaian Luaran</h2><p class="small text-muted">Pengkinian capaian luaran {{ strtolower($serviceTitle) }}.</p></div><div class="text-center py-5 border rounded bg-light text-muted">Data tidak tersedia!</div>
    @endif
    <div class="mt-3">{{ $proposals->links() }}</div>
</div>
@elseif($category === 'penelitian')
@php
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
@endphp
<div class="card p-3 p-md-4">
    <div class="mb-4">
        <div class="small text-muted mb-1"><i class="bi bi-house-door me-1"></i>Dashboard <i class="bi bi-chevron-right mx-1"></i>Penelitian</div>
        <h1 class="h4 fw-bold text-primary mb-1">Penelitian</h1>
        <p class="small text-muted mb-0">Kelola seluruh tahapan usulan dan pelaksanaan penelitian Anda.</p>
    </div>

    <div class="nav nav-pills gap-2 border-bottom pb-3 mb-4 overflow-auto flex-nowrap" role="tablist">
        @foreach($researchTabs as $key => $item)
            <a href="{{ $tabUrl($key) }}" class="nav-link btn btn-sm text-nowrap {{ $tab === $key ? 'active' : 'text-secondary' }}">
                <i class="bi {{ $item['icon'] }} me-1"></i>{{ $item['label'] }}
            </a>
        @endforeach
    </div>

    @if($tab === 'usulan')
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
            <div><h2 class="h5 fw-bold mb-1">Usulan Penelitian</h2><p class="small text-muted mb-0">Daftar usulan penelitian yang telah Anda buat.</p></div>
            <a href="{{ route('dosen.proposals.create', ['kategori' => 'penelitian']) }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat usulan</a>
        </div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>No.</th><th>Ketua</th><th>Judul</th><th>Skema</th><th>Tahun Pelaksanaan</th><th>Peran</th><th>Status Usulan</th><th>Komentar LPPM</th><th>Komentar Reviewer</th><th>Aksi</th></tr></thead><tbody>
            @forelse($proposals as $proposal)
                @php
                    $completedReviews = $proposal->reviews->where('status_review', 'completed');
                @endphp
                <tr>
                    <td>{{ $proposals->firstItem() + $loop->index }}</td>
                    <td><div class="fw-semibold">{{ $proposal->user->name }}</div><small class="text-muted">{{ $proposal->user->nidn_nip ?: '-' }}</small></td>
                    <td><div class="fw-semibold">{{ $proposal->judul }}</div><small class="text-muted">{{ $proposal->kode_registrasi }}</small></td>
                    <td>{{ $proposal->scheme?->nama_skema ?? '-' }}</td>
                    <td>{{ $proposal->scheme?->tahun_anggaran ?? $proposal->created_at->year }}</td>
                    <td>Ketua</td>
                    <td><span class="badge bg-{{ $proposal->status_badge['color'] }}">{{ $proposal->status_badge['label'] }}</span></td>
                    <td>
                        @if($proposal->catatan_admin)
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#lppmComment{{ $proposal->id }}"><i class="bi bi-chat-left-text me-1"></i>Komentar</button>
                        @else
                            <span class="text-muted small">Belum ada</span>
                        @endif
                    </td>
                    <td>
                        @if($completedReviews->isNotEmpty())
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reviewerComment{{ $proposal->id }}"><i class="bi bi-chat-left-text me-1"></i>Komentar</button>
                        @else
                            <span class="text-muted small">Belum ada</span>
                        @endif
                    </td>
                    <td><a href="{{ route('dosen.proposals.show', $proposal) }}" class="btn btn-sm btn-outline-primary" title="Lihat detail usulan" aria-label="Lihat detail {{ $proposal->judul }}"><i class="bi bi-eye"></i></a></td>
                </tr>

            @empty
                <tr><td colspan="10"><div class="text-center py-5"><div class="display-6 text-secondary mb-2"><i class="bi bi-inbox"></i></div><div class="fw-semibold">Belum ada usulan penelitian</div><p class="small text-muted">Mulai dengan membuat usulan penelitian baru.</p><a href="{{ route('dosen.proposals.create', ['kategori' => 'penelitian']) }}" class="btn btn-primary btn-sm">Buat usulan</a></div></td></tr>
            @endforelse
        </tbody></table></div>
        @foreach($proposals as $proposal)
            @php
                $completedReviews = $proposal->reviews->where('status_review', 'completed');
            @endphp
            @if($proposal->catatan_admin)
                <div class="modal fade" id="lppmComment{{ $proposal->id }}" tabindex="-1" aria-labelledby="lppmCommentTitle{{ $proposal->id }}" aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content"><div class="modal-header"><h2 class="modal-title fs-5 text-primary" id="lppmCommentTitle{{ $proposal->id }}">Komentar LPPM</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div><div class="modal-body"><div class="small text-muted mb-3">Usulan: <strong>{{ $proposal->judul }}</strong></div><div class="border rounded p-3 bg-light"><div class="fw-semibold mb-2">Catatan LPPM</div><div class="text-break">{!! nl2br(e($proposal->catatan_admin)) !!}</div></div>@if($proposal->decided_at)<div class="small text-muted mt-3">Diperbarui: {{ $proposal->decided_at->translatedFormat('d F Y, H:i') }}</div>@endif</div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button></div></div></div></div>
            @endif
            @if($completedReviews->isNotEmpty())
                <div class="modal fade" id="reviewerComment{{ $proposal->id }}" tabindex="-1" aria-labelledby="reviewerCommentTitle{{ $proposal->id }}" aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content"><div class="modal-header"><h2 class="modal-title fs-5 text-primary" id="reviewerCommentTitle{{ $proposal->id }}">Komentar Reviewer</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div><div class="modal-body"><div class="small text-muted mb-3">Usulan: <strong>{{ $proposal->judul }}</strong></div>@foreach($completedReviews as $review)<div class="border rounded p-3 mb-3"><div class="d-flex flex-wrap justify-content-between gap-2 mb-2"><strong>{{ $review->reviewer->name }}</strong><span class="badge bg-primary">Skor {{ $review->nilai_total }}</span></div><div class="small mb-2">Rekomendasi: <strong>{{ ucwords(str_replace('_', ' ', $review->rekomendasi)) }}</strong></div><div class="text-break">{!! nl2br(e($review->komentar_reviewer)) !!}</div>@if($review->reviewed_at)<div class="small text-muted mt-2">Ditinjau: {{ $review->reviewed_at->translatedFormat('d F Y, H:i') }}</div>@endif</div>@endforeach</div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button></div></div></div></div>
            @endif
        @endforeach
    @elseif($tab === 'bimtek')
        <div><h2 class="h5 fw-bold mb-1">Bimbingan Teknis</h2><p class="small text-muted">Informasi bimbingan teknis penelitian dari LPPM akan ditampilkan pada halaman ini.</p></div>
        <div class="text-center py-5 border rounded bg-light"><i class="bi bi-mortarboard display-6 text-secondary"></i><div class="fw-semibold mt-3">Belum ada jadwal bimbingan teknis</div><p class="small text-muted mb-0">Silakan pantau halaman ini untuk pengumuman dari LPPM.</p></div>
    @elseif($tab === 'perbaikan-usulan')
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Perbaikan Usulan</h2><p class="small text-muted mb-0">Unggah dan pantau perbaikan usulan berdasarkan hasil seleksi atau catatan LPPM.</p></div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Usulan</th><th>Catatan LPPM</th><th>Status Perbaikan</th><th>Aksi</th></tr></thead><tbody>
            @forelse($proposals->whereIn('status', ['accepted', 'accepted_revision', 'revision_submitted']) as $proposal)
                <tr><td><div class="fw-semibold">{{ $proposal->judul }}</div><small class="text-muted">{{ $proposal->kode_registrasi }}</small></td><td>{{ $proposal->catatan_admin ?: '-' }}</td><td><span class="badge bg-{{ $proposal->status_badge['color'] }}">{{ $proposal->status_badge['label'] }}</span></td><td><a href="{{ route('dosen.proposals.show', $proposal) }}" class="btn btn-sm btn-outline-primary">Buka</a></td></tr>
            @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada usulan yang memerlukan perbaikan.</td></tr>
            @endforelse
        </tbody></table></div>
    @elseif($tab === 'catatan-harian')
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Catatan Harian</h2><p class="small text-muted mb-0">Catat perkembangan kegiatan penelitian selama masa pelaksanaan.</p></div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Usulan</th><th>Status</th><th>Jumlah Catatan</th><th>Aksi</th></tr></thead><tbody>
            @forelse($proposals->whereIn('status', ['in_progress', 'progress_submitted', 'progress_revision', 'progress_approved', 'final_submitted', 'completed']) as $proposal)
                <tr><td class="fw-semibold">{{ $proposal->judul }}</td><td><span class="badge bg-{{ $proposal->status_badge['color'] }}">{{ $proposal->status_badge['label'] }}</span></td><td>{{ $proposal->logbooks_count }} catatan</td><td><a href="{{ route('dosen.proposals.show', $proposal) }}" class="btn btn-sm btn-outline-primary">Kelola catatan</a></td></tr>
            @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada penelitian pada tahap pelaksanaan.</td></tr>
            @endforelse
        </tbody></table></div>
    @elseif($tab === 'laporan-kemajuan')
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Laporan Kemajuan</h2><p class="small text-muted mb-0">Pantau pengunggahan dan evaluasi laporan kemajuan penelitian.</p></div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Usulan</th><th>Kemajuan</th><th>Status Monev</th><th>Aksi</th></tr></thead><tbody>
            @forelse($proposals->whereIn('status', ['in_progress', 'progress_submitted', 'progress_revision', 'progress_approved', 'final_submitted', 'completed']) as $proposal)
                <tr><td class="fw-semibold">{{ $proposal->judul }}</td><td>{{ $proposal->progressReport?->persentase_kemajuan ? $proposal->progressReport->persentase_kemajuan . '%' : '-' }}</td><td>{{ $proposal->progressReport?->status_monev ? strtoupper(str_replace('_', ' ', $proposal->progressReport->status_monev)) : 'Belum diunggah' }}</td><td><a href="{{ route('dosen.proposals.show', $proposal) }}" class="btn btn-sm btn-outline-primary">Buka</a></td></tr>
            @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada laporan kemajuan yang tersedia.</td></tr>
            @endforelse
        </tbody></table></div>
    @elseif($tab === 'laporan-akhir')
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Laporan Akhir</h2><p class="small text-muted mb-0">Kelola laporan akhir untuk penelitian yang telah menyelesaikan tahap kemajuan.</p></div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Usulan</th><th>Laporan Akhir</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
            @forelse($proposals->whereIn('status', ['progress_approved', 'final_submitted', 'completed']) as $proposal)
                <tr><td class="fw-semibold">{{ $proposal->judul }}</td><td>{{ $proposal->finalReport ? 'Sudah diunggah' : 'Belum diunggah' }}</td><td><span class="badge bg-{{ $proposal->status_badge['color'] }}">{{ $proposal->status_badge['label'] }}</span></td><td><a href="{{ route('dosen.proposals.show', $proposal) }}" class="btn btn-sm btn-outline-primary">Buka</a></td></tr>
            @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada laporan akhir yang tersedia.</td></tr>
            @endforelse
        </tbody></table></div>
    @else
        <div class="mb-3"><h2 class="h5 fw-bold mb-1">Pengkinian Capaian Luaran</h2><p class="small text-muted mb-0">Pantau luaran penelitian yang telah Anda catat pada laporan akhir.</p></div>
        <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Usulan</th><th>Jumlah Luaran</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
            @forelse($proposals->whereIn('status', ['progress_approved', 'final_submitted', 'completed']) as $proposal)
                <tr><td class="fw-semibold">{{ $proposal->judul }}</td><td>{{ $proposal->outputs->count() }} luaran</td><td><span class="badge bg-{{ $proposal->status_badge['color'] }}">{{ $proposal->status_badge['label'] }}</span></td><td><a href="{{ route('dosen.proposals.show', $proposal) }}" class="btn btn-sm btn-outline-primary">Lihat luaran</a></td></tr>
            @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data capaian luaran.</td></tr>
            @endforelse
        </tbody></table></div>
    @endif

    <div class="mt-3">{{ $proposals->links() }}</div>
</div>
@else
<div class="card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
        <div><div class="small text-muted mb-1"><i class="bi bi-house-door me-1"></i>Dashboard <i class="bi bi-chevron-right mx-1"></i>Usulan</div><h1 class="h4 fw-bold text-primary mb-1">{{ $category ? 'Usulan '.ucfirst($category) : 'Usulan Penelitian & Pengabdian' }}</h1><p class="small text-muted mb-0">Daftar pengajuan dan status proses di LPPM UNISA Palembang.</p></div>
        <a href="{{ route('dosen.proposals.create', $category ? ['kategori' => $category] : []) }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Buat usulan</a>
    </div>
    <div class="d-flex gap-2 border-bottom mb-3 pb-2 flex-wrap">
        <a class="btn btn-sm {{ !$category ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('dosen.proposals.index') }}">Semua</a>
        <a class="btn btn-sm {{ $category === 'penelitian' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('dosen.proposals.index', ['kategori'=>'penelitian']) }}">Penelitian</a>
        <a class="btn btn-sm {{ $category === 'pengabdian' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('dosen.proposals.index', ['kategori'=>'pengabdian']) }}">Pengabdian</a>
        <a class="btn btn-sm {{ $category === 'konsorsium' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('dosen.proposals.index', ['kategori'=>'konsorsium']) }}">Konsorsium</a>
        <a class="btn btn-sm {{ $category === 'prototipe' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('dosen.proposals.index', ['kategori'=>'prototipe']) }}">Prototipe</a>
    </div>
    <div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>No.</th><th>Usulan</th><th>Skema</th><th>Tahun</th><th>Dana usulan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
        @forelse($proposals as $proposal)
            <tr><td>{{ $proposals->firstItem() + $loop->index }}</td><td><div class="fw-semibold">{{ $proposal->judul }}</div><small class="text-muted">{{ $proposal->kode_registrasi }}</small></td><td>{{ $proposal->scheme?->nama_skema ?? '-' }}</td><td>{{ $proposal->scheme?->tahun_anggaran ?? $proposal->created_at->year }}</td><td>Rp {{ number_format($proposal->dana_usulan, 0, ',', '.') }}</td><td><span class="badge bg-{{ $proposal->status_badge['color'] }}">{{ $proposal->status_badge['label'] }}</span></td><td><a href="{{ route('dosen.proposals.show', $proposal) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i>Detail</a></td></tr>
        @empty
            <tr><td colspan="7"><div class="text-center py-5"><div class="display-6 text-secondary mb-2"><i class="bi bi-inbox"></i></div><div class="fw-semibold">Belum ada usulan {{ $category ?: 'penelitian atau pengabdian' }}</div><p class="small text-muted">Usulan yang Anda simpan akan tampil di halaman ini.</p><a href="{{ route('dosen.proposals.create', $category ? ['kategori' => $category] : []) }}" class="btn btn-primary btn-sm">Mulai buat usulan</a></div></td></tr>
        @endforelse
    </tbody></table></div>
    <div class="mt-3">{{ $proposals->links() }}</div>
</div>
@endif
@endsection

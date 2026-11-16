@extends('layouts.app')
@section('title', 'Manajemen Usulan LPPM')
@section('content')
<div class="card p-3">
    <h5 class="fw-bold mb-3">Seluruh Usulan Penelitian & Pengabdian</h5>
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr><th>Kode</th><th>Judul</th><th>Ketua</th><th>Skor Review</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($proposals as $p)
            <tr>
                <td><code>{{ $p->kode_registrasi }}</code></td>
                <td>{{ Str::limit($p->judul, 55) }}</td>
                <td>{{ $p->user->name }}</td>
                <td><strong>{{ $p->averageReviewScore() }}</strong></td>
                <td><span class="badge bg-{{ $p->status_badge['color'] }}">{{ $p->status_badge['label'] }}</span></td>
                <td><a href="{{ route('admin.proposals.show', $p) }}" class="btn btn-sm btn-primary">Kelola Tahapan</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $proposals->links() }}
</div>
@endsection

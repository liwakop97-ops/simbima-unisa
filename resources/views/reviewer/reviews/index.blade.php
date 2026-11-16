@extends('layouts.app')
@section('title', 'Tugas Review Proposal')
@section('content')
<div class="card p-3">
    <h5 class="fw-bold mb-3">Daftar Penugasan Review Substansi</h5>
    <table class="table table-hover">
        <thead><tr><th>Kode</th><th>Judul</th><th>Skema</th><th>Status</th><th>Nilai</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($reviews as $r)
            <tr>
                <td><code>{{ $r->proposal->kode_registrasi }}</code></td>
                <td>{{ $r->proposal->judul }}</td>
                <td>{{ $r->proposal->scheme->kode_skema }}</td>
                <td>{{ strtoupper($r->status_review) }}</td>
                <td>{{ $r->nilai_total }}</td>
                <td><a href="{{ route('reviewer.reviews.show', $r) }}" class="btn btn-sm btn-primary">Buka Lembar Penilaian</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $reviews->links() }}
</div>
@endsection

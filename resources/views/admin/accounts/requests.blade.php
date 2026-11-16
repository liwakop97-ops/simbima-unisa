@extends('layouts.app')
@section('title', 'Permintaan Akun · SIMBIMA UNISA')
@section('topbar-title', 'Verifikasi akun')
@section('content')
<div class="card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
        <div><div class="small text-muted mb-1">Khusus Administrator</div><h1 class="h4 fw-bold text-primary mb-1">Permintaan akun</h1><p class="small text-muted mb-0">Verifikasi identitas, tentukan role, lalu aktifkan akun.</p></div>
        <span class="badge text-bg-warning align-self-center">{{ $requests->total() }} menunggu</span>
    </div>
    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Nama &amp; email</th><th>NIDN / NIP</th><th>Program studi</th><th>Jabatan &amp; SINTA</th><th>Tanggal</th><th>Role &amp; keputusan</th></tr></thead><tbody>
        @forelse($requests as $user)
            <tr>
                <td><div class="fw-semibold">{{ $user->name }}</div><small class="text-muted">{{ $user->email }}</small></td>
                <td>{{ $user->nidn_nip }}</td>
                <td>{{ $user->institusi_prodi }}</td>
                <td><div>{{ $user->jabatan_fungsional ?: '—' }}</div><small class="text-muted">SINTA {{ $user->sinta_id ?: '—' }}</small></td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <div class="d-flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('admin.accounts.approve', $user) }}" class="d-flex gap-2">
                            @csrf
                            <select name="role" class="form-select form-select-sm" aria-label="Role untuk {{ $user->name }}">
                                <option value="dosen" @selected($user->role === 'dosen')>Dosen</option>
                                <option value="reviewer" @selected($user->role === 'reviewer')>Reviewer</option>
                                <option value="lppm" @selected($user->role === 'lppm')>LPPM</option>
                                <option value="admin" @selected($user->role === 'admin')>Admin</option>
                            </select>
                            <button class="btn btn-sm btn-success" onclick="return confirm('Aktifkan akun {{ addslashes($user->name) }}?')"><i class="bi bi-check-lg me-1"></i>Setujui</button>
                        </form>
                        <form method="POST" action="{{ route('admin.accounts.reject', $user) }}">@csrf<button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tolak permintaan akun ini?')">Tolak</button></form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6"><div class="text-center py-5"><div class="display-6 text-success mb-2"><i class="bi bi-person-check"></i></div><div class="fw-semibold">Tidak ada permintaan akun yang menunggu.</div><p class="small text-muted mb-0">Permintaan baru akan muncul di daftar ini.</p></div></td></tr>
        @endforelse
    </tbody></table></div>
    <div class="mt-3">{{ $requests->links() }}</div>
</div>
@endsection

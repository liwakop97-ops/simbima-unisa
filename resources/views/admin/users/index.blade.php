@extends('layouts.app')
@section('title', 'Kelola Pengguna · SIMBIMA UNISA')
@section('topbar-title', 'Kelola pengguna')
@section('content')
<div class="card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4"><div><div class="small text-muted mb-1">Khusus Administrator</div><h1 class="h4 fw-bold text-primary mb-1">Kelola pengguna</h1><p class="small text-muted mb-0">Edit identitas, status akun, kata sandi, dan pembagian role seluruh pengguna.</p></div><a href="{{ route('admin.accounts.requests') }}" class="btn btn-outline-primary"><i class="bi bi-person-check me-1"></i>Permintaan akun</a></div>

    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-6"><input type="search" name="q" class="form-control" value="{{ request('q') }}" placeholder="Cari nama, username, email, atau NIDN"></div>
        <div class="col-md-2"><select name="role" class="form-select"><option value="">Semua role</option>@foreach(['admin' => 'Admin', 'lppm' => 'LPPM', 'dosen' => 'Dosen', 'reviewer' => 'Reviewer'] as $value => $label)<option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>@endforeach</select></div>
        <div class="col-md-2"><select name="status" class="form-select"><option value="">Semua status</option>@foreach(['active' => 'Aktif', 'pending' => 'Menunggu', 'rejected' => 'Ditolak'] as $value => $label)<option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>@endforeach</select></div>
        <div class="col-md-2 d-grid"><button class="btn btn-primary"><i class="bi bi-search me-1"></i>Cari</button></div>
    </form>

    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Pengguna</th><th>NIDN / NIP</th><th>Unit kerja</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
        @forelse($users as $user)
            <tr><td><div class="fw-semibold">{{ $user->name }}</div><small class="text-muted">{{ $user->username ?: 'tanpa username' }} · {{ $user->email }}</small></td><td>{{ $user->nidn_nip ?: '—' }}</td><td>{{ $user->institusi_prodi ?: '—' }}</td><td><span class="badge {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'lppm' ? 'bg-primary' : 'bg-secondary') }}">{{ strtoupper($user->role) }}</span></td><td><span class="badge {{ $user->account_status === 'active' ? 'bg-success' : ($user->account_status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ strtoupper($user->account_status) }}</span></td><td><a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square me-1"></i>Edit</a></td></tr>
        @empty
            <tr><td colspan="6"><div class="empty-state"><i class="bi bi-people"></i><strong>Pengguna tidak ditemukan</strong><p>Ubah kata pencarian atau filter yang digunakan.</p></div></td></tr>
        @endforelse
    </tbody></table></div>
    <div class="mt-3">{{ $users->links() }}</div>
</div>
@endsection

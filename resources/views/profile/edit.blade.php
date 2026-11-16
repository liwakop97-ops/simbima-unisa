@extends('layouts.app')
@section('title', 'Edit Profil · SIMBIMA UNISA')
@section('topbar-title', 'Edit profil')
@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <section class="card p-4 h-100">
            <div class="small text-uppercase fw-bold text-success" style="letter-spacing:.12em;font-size:10px">Identitas akun</div>
            <div class="text-center py-4">
                <div class="mx-auto mb-3" style="width:124px;height:124px;border-radius:28px;overflow:hidden;background:#d7f2e5;display:grid;place-items:center;color:#0a5f4b;font-size:45px;font-weight:800">
                    @if($user->avatarUrl())<img src="{{ $user->avatarUrl() }}" alt="Foto profil {{ $user->name }}" style="width:100%;height:100%;object-fit:cover">@else{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}@endif
                </div>
                <h1 class="h5 fw-bold mb-1">{{ $user->name }}</h1>
                <div class="small text-muted mb-2">{{ $user->email }}</div>
                <span class="badge text-bg-success px-3 py-2">{{ strtoupper($user->role) }}</span>
            </div>
            <div class="rounded-3 p-3" style="background:#f6faf8">
                <div class="small fw-semibold mb-2"><i class="bi bi-shield-check text-success me-1"></i>Status akun</div>
                <div class="small text-muted">Akun Anda aktif dan dapat menggunakan fitur sesuai peran yang diberikan LPPM.</div>
            </div>
        </section>
    </div>
    <div class="col-lg-8">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="card">
            @csrf @method('PUT')
            <div class="card-header p-4"><h2 class="h5 mb-1">Informasi profil</h2><p class="small text-muted mb-0 fw-normal">Perbarui informasi kontak, identitas akademik, foto, dan kata sandi Anda.</p></div>
            <div class="card-body p-4">
                <div class="mb-4"><label class="form-label" for="avatar">Foto profil</label><input class="form-control" id="avatar" type="file" name="avatar" accept="image/png,image/jpeg,image/webp"><div class="form-text">JPG, PNG, atau WebP. Maksimal 2 MB.</div></div>
                <div class="row g-3 mb-4"><div class="col-md-7"><label class="form-label" for="name">Nama lengkap dan gelar *</label><input class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required></div><div class="col-md-5"><label class="form-label" for="username">Username</label><input class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" placeholder="Contoh: dosen_unisa"><div class="form-text">Huruf, angka, garis bawah, atau tanda hubung.</div></div></div>
                <div class="row g-3 mb-4"><div class="col-md-6"><label class="form-label" for="email">Email *</label><input class="form-control" id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required></div><div class="col-md-6"><label class="form-label" for="phone">Nomor WhatsApp / telepon</label><input class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 0812xxxx"></div></div>
                <div class="border-top pt-4 mb-4"><div class="small text-uppercase fw-bold text-success mb-3" style="letter-spacing:.11em;font-size:10px">Data akademik dan institusi</div><div class="row g-3"><div class="col-md-6"><label class="form-label" for="nidn_nip">NIDN / NIP</label><input class="form-control" id="nidn_nip" name="nidn_nip" value="{{ old('nidn_nip', $user->nidn_nip) }}"></div><div class="col-md-6"><label class="form-label" for="sinta_id">ID SINTA</label><input class="form-control" id="sinta_id" name="sinta_id" value="{{ old('sinta_id', $user->sinta_id) }}"></div><div class="col-md-7"><label class="form-label" for="institusi_prodi">Program studi / unit kerja</label><input class="form-control" id="institusi_prodi" name="institusi_prodi" value="{{ old('institusi_prodi', $user->institusi_prodi) }}"></div><div class="col-md-5"><label class="form-label" for="jabatan_fungsional">Jabatan akademik</label><input class="form-control" id="jabatan_fungsional" name="jabatan_fungsional" value="{{ old('jabatan_fungsional', $user->jabatan_fungsional) }}"></div><div class="col-12"><label class="form-label" for="address">Alamat</label><input class="form-control" id="address" name="address" value="{{ old('address', $user->address) }}"></div><div class="col-12"><label class="form-label" for="bio">Tentang saya</label><textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Bidang keahlian atau informasi singkat">{{ old('bio', $user->bio) }}</textarea></div></div></div>
                <div class="border-top pt-4"><div class="small text-uppercase fw-bold text-success mb-3" style="letter-spacing:.11em;font-size:10px">Ganti kata sandi</div><p class="small text-muted">Kosongkan kedua kolom jika tidak ingin mengubah kata sandi.</p><div class="row g-3"><div class="col-md-6"><label class="form-label" for="password">Kata sandi baru</label><input class="form-control" id="password" type="password" name="password" autocomplete="new-password"></div><div class="col-md-6"><label class="form-label" for="password_confirmation">Ulangi kata sandi baru</label><input class="form-control" id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"></div></div></div>
            </div>
            <div class="card-footer bg-white border-0 p-4 pt-0 d-flex justify-content-end gap-2"><a href="{{ route('dashboard') }}" class="btn btn-light">Batal</a><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan perubahan</button></div>
        </form>
    </div>
</div>
@endsection

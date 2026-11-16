@extends('layouts.guest')
@section('title', 'Permintaan Akun · SIMBIMA UNISA Palembang')
@section('auth-content')
    <h2 class="auth-title">Ajukan akun dosen</h2>
    <p class="auth-subtitle">Isi data sesuai identitas institusi. Admin LPPM akan memeriksa permintaan Anda.</p>
    <form method="POST" action="{{ route('register.store') }}">
        @csrf
        <div class="mb-3"><label class="form-label" for="name">Nama lengkap dan gelar</label><input id="name" name="name" class="form-control" value="{{ old('name') }}" autocomplete="name" required></div>
        <div class="mb-3"><label class="form-label" for="email">Email institusi</label><input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nama@unisa-palembang.ac.id" autocomplete="email" required></div>
        <div class="row g-3"><div class="col-sm-6"><label class="form-label" for="nidn_nip">NIDN / NIP</label><input id="nidn_nip" name="nidn_nip" class="form-control" value="{{ old('nidn_nip') }}" required></div><div class="col-sm-6"><label class="form-label" for="institusi_prodi">Program studi</label><input id="institusi_prodi" name="institusi_prodi" class="form-control" value="{{ old('institusi_prodi') }}" placeholder="Contoh: Keperawatan" required></div></div>
        <div class="row g-3 mt-0"><div class="col-sm-6"><label class="form-label" for="jabatan_fungsional">Jabatan akademik <span class="fw-normal text-muted">(opsional)</span></label><input id="jabatan_fungsional" name="jabatan_fungsional" class="form-control" value="{{ old('jabatan_fungsional') }}" placeholder="Contoh: Lektor"></div><div class="col-sm-6"><label class="form-label" for="sinta_id">ID SINTA <span class="fw-normal text-muted">(opsional)</span></label><input id="sinta_id" name="sinta_id" class="form-control" value="{{ old('sinta_id') }}"></div></div>
        <div class="row g-3 mt-0"><div class="col-sm-6"><label class="form-label" for="password">Kata sandi</label><input id="password" type="password" name="password" class="form-control" autocomplete="new-password" required></div><div class="col-sm-6"><label class="form-label" for="password_confirmation">Ulangi kata sandi</label><input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required></div></div>
        <label class="form-check mt-3 mb-3"><input class="form-check-input" type="checkbox" name="agreement" value="1" required><span class="form-check-label small text-secondary">Saya menyatakan data yang diisi benar dan merupakan sivitas akademika Universitas Aisyiyah Palembang.</span></label>
        <button class="btn btn-auth w-100" type="submit">Kirim permintaan akun <i class="bi bi-send ms-1"></i></button>
    </form>
    <div class="auth-links text-center mt-4">Sudah memiliki akun? <a href="{{ route('login') }}">Kembali masuk</a></div>
@endsection

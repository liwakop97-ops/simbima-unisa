@extends('layouts.guest')
@section('title', 'Masuk · SIMBIMA UNISA Palembang')
@section('auth-content')
    <h2 class="auth-title">Masuk ke akun Anda</h2>
    <p class="auth-subtitle">Gunakan email institusi yang terdaftar di UNISA Palembang.</p>
    <form method="POST" action="{{ route('login') }}" autocomplete="off">
        @csrf
        <div class="mb-3"><label class="form-label" for="login">Username atau email</label><input id="login" type="text" name="login" class="form-control" value="{{ old('login') }}" placeholder="Contoh: dosen" autocomplete="off" required autofocus></div>
        <div class="mb-3"><label class="form-label" for="password">Kata sandi</label><div class="input-group"><input id="password" type="password" name="password" class="form-control" placeholder="Masukkan kata sandi" autocomplete="new-password" required><button class="btn" type="button" data-password-toggle="password" aria-label="Tampilkan kata sandi"><i class="bi bi-eye"></i></button></div></div>
        <div class="d-flex justify-content-between align-items-center mb-4"><label class="form-check d-flex align-items-center gap-2 mb-0"><input class="form-check-input mt-0" type="checkbox" name="remember" value="1"><span class="form-check-label small text-secondary">Ingat saya</span></label><a class="auth-links" href="mailto:lppm@unisa-palembang.ac.id?subject=Bantuan%20Akun%20SIMBIMA">Butuh bantuan?</a></div>
        <button class="btn btn-auth w-100" type="submit">Masuk <i class="bi bi-arrow-right ms-1"></i></button>
    </form>
    <div class="auth-links text-center mt-4">Belum memiliki akun? <a href="{{ route('register') }}">Ajukan pembuatan akun</a></div>
    <section class="demo-accounts" aria-label="Tes akun">
        <div class="demo-accounts-title"><i class="bi bi-info-circle"></i> Tes akun</div>
        <p>Gunakan salah satu akun berikut untuk mencoba setiap peran.</p>
        <div class="demo-account"><span class="demo-role">Admin</span><code>admin</code><span class="demo-password">123456</span></div>
        <div class="demo-account"><span class="demo-role">LPPM</span><code>lppm</code><span class="demo-password">123456</span></div>
        <div class="demo-account"><span class="demo-role">Dosen</span><code>dosen</code><span class="demo-password">123456</span></div>
        <div class="demo-account"><span class="demo-role">Reviewer</span><code>review</code><span class="demo-password">123456</span></div>
    </section>
@endsection

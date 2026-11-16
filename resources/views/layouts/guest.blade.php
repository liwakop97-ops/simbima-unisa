<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMBIMA UNISA Palembang')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root{--navy:#1c3264;--green:#07875d;--gold:#dcb84d;--ink:#26324a;--muted:#758198;--line:#e7ebf1}
        *{box-sizing:border-box} body{margin:0;min-height:100vh;background:#f4f6f9;color:var(--ink);font-family:Inter,"Segoe UI",Arial,sans-serif;font-size:14px}
        .guest-top{height:74px;background:white;border-bottom:1px solid var(--line);display:flex;align-items:center;padding:0 max(24px,calc((100vw - 1200px)/2))}
        .guest-top img{width:168px;height:52px;object-fit:contain;object-position:left center}.guest-top span{border-left:1px solid var(--line);padding-left:14px;margin-left:14px;font-size:11px;line-height:1.4;color:#6b778e}
        .guest-stage{min-height:calc(100vh - 124px);display:flex;align-items:center;justify-content:center;padding:42px 18px}
        .auth-card{width:min(100%,540px);background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 18px 55px #1b2b4c14;border:1px solid #e9edf2}
        .auth-banner{background:linear-gradient(115deg,#e6edf9 0%,#eff5fa 100%);min-height:136px;padding:28px 30px 26px;position:relative}
        .auth-banner:after{content:"";position:absolute;right:28px;top:20px;width:82px;height:82px;border:1px solid #d1deef;border-radius:50%;box-shadow:0 0 0 12px #ffffff50,0 0 0 24px #ffffff28}
        .auth-banner h1{font-size:19px;font-weight:750;color:var(--navy);margin:0 0 7px;position:relative;z-index:1}
        .auth-banner p{max-width:350px;color:#596984;font-size:13px;line-height:1.55;margin:0;position:relative;z-index:1}
        .auth-body{padding:0 30px 28px}.auth-logo{width:72px;height:72px;background:white;border:5px solid #f5f7fb;border-radius:50%;object-fit:cover;object-position:left center;padding:0;margin-top:-30px;position:relative;z-index:2;box-shadow:0 4px 15px #20376216}
        .auth-content{padding-top:10px}.auth-title{font-size:17px;font-weight:750;margin:0 0 4px;color:var(--ink)}.auth-subtitle{font-size:12px;color:var(--muted);margin-bottom:22px}
        .form-label{font-size:12px;color:#47536a;font-weight:650;margin-bottom:6px}.form-control,.form-select{font-size:13px;border-radius:8px;border-color:#d9e0e9;padding:.72rem .82rem}.form-control:focus,.form-select:focus{border-color:#62ac8c;box-shadow:0 0 0 .2rem #07875d19}
        .input-group .form-control{border-radius:8px 0 0 8px}.input-group .btn{border:1px solid #d9e0e9;border-left:0;border-radius:0 8px 8px 0;color:#657087;background:#f8f9fb}
        .btn-auth{background:var(--navy);border-color:var(--navy);color:white;border-radius:8px;padding:.8rem;font-size:13px;font-weight:700}.btn-auth:hover{background:#14264f;color:white}
        .auth-links{font-size:12px;color:var(--muted)}.auth-links a{color:var(--navy);font-weight:650;text-decoration:none}.auth-links a:hover{text-decoration:underline}
        .demo-accounts{margin-top:24px;padding:13px 14px;background:#f7faf9;border:1px solid #dcece5;border-radius:9px}.demo-accounts-title{color:var(--green);font-size:12px;font-weight:750}.demo-accounts-title i{margin-right:5px}.demo-accounts p{margin:4px 0 9px;color:var(--muted);font-size:10px}.demo-account{display:grid;grid-template-columns:70px minmax(0,1fr) 82px;align-items:center;gap:7px;padding:7px 0;border-top:1px solid #e5eee9;font-size:10px}.demo-role{font-weight:700;color:#40516c}.demo-account code{color:var(--navy);font-size:10px;white-space:nowrap}.demo-password{color:#617089;text-align:right;font-family:ui-monospace,SFMono-Regular,Menlo,monospace}
        .auth-foot{text-align:center;padding:14px;color:#8992a2;font-size:10px}
        .form-check-input:checked{background-color:var(--green);border-color:var(--green)}
        .guest-alerts{width:min(100%,540px);margin:0 auto 14px}.guest-alerts .alert{font-size:12px;border-radius:9px}
        @media(max-width:600px){.guest-top{height:64px;padding:0 16px}.guest-top img{width:138px;height:44px}.guest-top span{font-size:10px}.guest-stage{padding:25px 14px;align-items:flex-start}.auth-banner{padding:24px 22px;min-height:124px}.auth-banner:after{right:16px;width:62px;height:62px}.auth-body{padding:0 22px 24px}.demo-account{grid-template-columns:58px minmax(0,1fr);gap:4px}.demo-password{text-align:left;grid-column:2;font-size:9px}}
    </style>
    @stack('styles')
</head>
<body>
<header class="guest-top"><img src="{{ asset('images/unisa-palembang.png') }}" alt="Logo Universitas Aisyiyah Palembang"><span>Sistem Informasi Penelitian<br>&amp; Pengabdian Masyarakat</span></header>
<main class="guest-stage"><div style="width:100%;max-width:540px">
    <div class="guest-alerts">
        @if(session('success'))<div class="alert alert-success mb-0"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger mb-0"><ul class="mb-0 ps-3">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    </div>
    <section class="auth-card">
        <div class="auth-banner"><h1>Selamat datang di SIMBIMA</h1><p>Portal pengelolaan penelitian dan pengabdian kepada masyarakat Universitas Aisyiyah Palembang.</p></div>
        <div class="auth-body"><img class="auth-logo" src="{{ asset('images/unisa-palembang.png') }}" alt="Logo UNISA Palembang"><div class="auth-content">@yield('auth-content')</div></div>
    </section>
    <div class="auth-foot">© {{ date('Y') }} LPPM Universitas Aisyiyah Palembang</div>
</div></main>
<script>
document.querySelectorAll('[data-password-toggle]').forEach(button=>button.addEventListener('click',()=>{const input=document.getElementById(button.dataset.passwordToggle);const showing=input.type==='password';input.type=showing?'text':'password';button.innerHTML=showing?'<i class="bi bi-eye-slash"></i>':'<i class="bi bi-eye"></i>';button.setAttribute('aria-label',showing?'Sembunyikan kata sandi':'Tampilkan kata sandi')}));
</script>
</body>
</html>

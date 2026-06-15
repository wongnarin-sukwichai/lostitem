<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>เข้าสู่ระบบ | Lost & Found MSU</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
@vite(['resources/css/auth.css'])
</head>
<body>

<div class="login-container">
    <div class="login-card">

        <div class="login-header">
            <div class="brand-icon">
                <a href="{{ route('home') }}" class="text-white text-decoration-none">
                    <i class="fas fa-box-open"></i>
                </a>
            </div>
            <h1>เข้าสู่ระบบผู้ดูแล</h1>
            <p>Lost & Found Management System</p>
        </div>

        @if(session('error'))
        <div class="alert" style="background:#fee2e2;color:#b91c1c;border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <div class="text-center mt-2 mb-3">
            <p class="text-muted small mb-3">เข้าสู่ระบบด้วยบัญชีองค์กร <strong>@msu.ac.th</strong> เท่านั้น</p>
            <a href="{{ route('auth.google') }}" class="btn-google">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="20" alt="Google">
                <span>Sign in with Google</span>
            </a>
        </div>

        <div class="login-footer">
            <i class="fas fa-shield-alt me-1"></i>
            สำนักวิทยบริการ มหาวิทยาลัยมหาสารคาม
        </div>

    </div>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>@yield('title', 'Admin') | Lost & Found MSU</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
@vite(['resources/css/admin.css', 'resources/js/admin.js'])
@stack('styles')
<link rel="stylesheet" href="{{ asset('admin/css/main.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="overlay" id="overlay"></div>

{{-- Sidebar --}}
<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <i class="fas fa-box-open me-2"></i>Lost & Found
        </a>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i><span>ภาพรวม</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.lost-items.index') }}" class="{{ request()->routeIs('admin.lost-items.*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i><span>ทรัพย์สิน</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i><span>หมวดหมู่</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.locations.index') }}" class="{{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                <i class="fas fa-map-marker-alt"></i><span>สถานที่</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i><span>ผู้ใช้งาน</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i><span>ตั้งค่า</span>
            </a>
        </li>
    </ul>
</nav>

<div class="content-wrapper">
    {{-- Navbar --}}
    <nav class="top-navbar">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="ms-auto d-flex align-items-center gap-3">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                <i class="fas fa-external-link-alt me-1"></i>หน้าบ้าน
            </a>
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" width="28" height="28" class="rounded-circle">
                    @else
                        <i class="fas fa-user-circle fs-5"></i>
                    @endif
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text small text-muted">{{ auth()->user()->email }}</span></li>
                    <li><span class="dropdown-item-text small text-muted">
                        <span class="badge {{ auth()->user()->role === 'admin' ? 'bg-danger' : 'bg-secondary' }}">
                            {{ auth()->user()->role === 'admin' ? 'Admin' : 'Staff' }}
                        </span>
                    </span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>ออกจากระบบ
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="main-content">
        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="text-center text-muted py-3" style="font-size:12px;">
        © {{ date('Y') }} Lost & Found System — สำนักวิทยบริการ มหาวิทยาลัยมหาสารคาม
    </footer>
</div>

@stack('scripts')
</body>
</html>

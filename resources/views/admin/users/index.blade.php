@extends('admin.layout')
@section('title', 'ผู้ใช้งาน')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1"><i class="fas fa-user-shield me-2 text-warning"></i>จัดการผู้ใช้งาน</h5>
        <small class="text-muted">เฉพาะ email ที่ลงทะเบียนไว้เท่านั้นจึงจะสามารถ Login ด้วย Google OAuth ได้</small>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-4"><i class="fas fa-plus me-2"></i>เพิ่มผู้ใช้</a>
    @endif
</div>
<div class="common-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light"><tr>
                <th class="ps-4">#</th>
                <th>Avatar</th>
                <th>ชื่อ-อีเมล</th>
                <th class="text-center">Role</th>
                <th class="text-center">สถานะ Login</th>
                <th class="text-center">จัดการ</th>
            </tr></thead>
            <tbody>
                @forelse($users as $i => $user)
                <tr>
                    <td class="ps-4 text-muted">{{ $users->firstItem() + $i }}</td>
                    <td>
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" width="36" height="36" class="rounded-circle border">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                <i class="fas fa-user text-white small"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold">{{ $user->name }}</div>
                        <small class="text-muted">{{ $user->email }}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-primary' }}">{{ $user->role }}</span>
                    </td>
                    <td class="text-center">
                        @if($user->google_id)
                            <span class="badge bg-success"><i class="fab fa-google me-1"></i>เชื่อมแล้ว</span>
                        @else
                            <span class="badge bg-warning text-dark">ยังไม่ได้ login</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('ยืนยันการลบผู้ใช้ '+ '{{ addslashes($user->name) }}'+ '?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">ไม่พบข้อมูล</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $users->links() }}</div>
</div>
@endsection

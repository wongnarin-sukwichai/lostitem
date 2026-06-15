@extends('admin.layout')
@section('title', 'เพิ่มผู้ใช้')
@section('content')
<div class="mb-4"><h5 class="fw-bold"><i class="fas fa-plus me-2 text-warning"></i>เพิ่มผู้ใช้งาน</h5></div>
<div class="common-card"><form method="POST" action="{{ route('admin.users.store') }}" class="p-3">
    @csrf
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <div class="alert alert-info d-flex align-items-center mb-3">
        <i class="fas fa-info-circle me-2"></i>
        <small>กรอก email ที่ใช้ Gmail องค์กร <strong>@msu.ac.th</strong> เท่านั้น — ระบบจะตรวจสอบเมื่อ Login ด้วย Google</small>
    </div>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label fw-bold">ชื่อ <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus></div>
        <div class="col-md-6"><label class="form-label fw-bold">Email (@msu.ac.th) <span class="text-danger">*</span></label><input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="example@msu.ac.th" required></div>
        <div class="col-md-4">
            <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
            <select name="role" class="form-select" required>
                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
    </div>
    <div class="d-flex gap-2 justify-content-end mt-3">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">ยกเลิก</a>
        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>บันทึก</button>
    </div>
</form></div>
@endsection

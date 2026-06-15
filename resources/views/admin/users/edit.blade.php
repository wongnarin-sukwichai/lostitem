@extends('admin.layout')
@section('title', 'แก้ไขผู้ใช้')
@section('content')
<div class="mb-4"><h5 class="fw-bold"><i class="fas fa-edit me-2 text-warning"></i>แก้ไขผู้ใช้งาน</h5></div>
<div class="common-card"><form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-3">
    @csrf @method('PUT')
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label fw-bold">ชื่อ <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required></div>
        <div class="col-md-6"><label class="form-label fw-bold">Email</label><input type="text" class="form-control bg-light" value="{{ $user->email }}" disabled></div>
        <div class="col-md-4">
            <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
            <select name="role" class="form-select" required>
                <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
    </div>
    <div class="d-flex gap-2 justify-content-end mt-3">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">ยกเลิก</a>
        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>บันทึก</button>
    </div>
</form></div>
@endsection

@extends('admin.layout')
@section('title', 'แก้ไขเจ้าหน้าที่')
@section('content')
<div class="mb-4"><h5 class="fw-bold"><i class="fas fa-edit me-2 text-warning"></i>แก้ไขเจ้าหน้าที่</h5></div>
<div class="common-card"><form method="POST" action="{{ route('admin.staffs.update', $staff) }}" class="p-3">
    @csrf @method('PUT')
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label fw-bold">ชื่อ <span class="text-danger">*</span></label><input type="text" name="first_name" class="form-control" value="{{ old('first_name', $staff->first_name) }}" required></div>
        <div class="col-md-4"><label class="form-label fw-bold">นามสกุล <span class="text-danger">*</span></label><input type="text" name="last_name" class="form-control" value="{{ old('last_name', $staff->last_name) }}" required></div>
        <div class="col-md-4"><label class="form-label fw-bold">ตำแหน่ง</label><input type="text" name="position" class="form-control" value="{{ old('position', $staff->position) }}"></div>
    </div>
    <div class="d-flex gap-2 justify-content-end mt-3">
        <a href="{{ route('admin.staffs.index') }}" class="btn btn-secondary">ยกเลิก</a>
        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>บันทึก</button>
    </div>
</form></div>
@endsection

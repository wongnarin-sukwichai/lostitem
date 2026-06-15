@extends('admin.layout')
@section('title', 'แก้ไขสถานที่')
@section('content')
<div class="mb-4"><h5 class="fw-bold"><i class="fas fa-edit me-2 text-warning"></i>แก้ไขสถานที่</h5></div>
<div class="common-card"><form method="POST" action="{{ route('admin.locations.update', $location) }}" class="p-3">
    @csrf @method('PUT')
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <div class="mb-3"><label class="form-label fw-bold">ชื่อสถานที่ <span class="text-danger">*</span></label>
    <input type="text" name="location_name" class="form-control" value="{{ old('location_name', $location->location_name) }}" required></div>
    <div class="d-flex gap-2 justify-content-end">
        <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">ยกเลิก</a>
        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>บันทึก</button>
    </div>
</form></div>
@endsection

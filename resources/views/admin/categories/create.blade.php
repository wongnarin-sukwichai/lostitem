@extends('admin.layout')
@section('title', 'เพิ่มหมวดหมู่')
@section('content')
<div class="mb-4"><h5 class="fw-bold"><i class="fas fa-plus me-2 text-warning"></i>เพิ่มหมวดหมู่</h5></div>
<div class="common-card">
    <form method="POST" action="{{ route('admin.categories.store') }}" class="p-3">
        @csrf
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <div class="mb-3">
            <label class="form-label fw-bold">ชื่อหมวดหมู่ <span class="text-danger">*</span></label>
            <input type="text" name="category_name" class="form-control" value="{{ old('category_name') }}" required autofocus>
        </div>
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">ยกเลิก</a>
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>บันทึก</button>
        </div>
    </form>
</div>
@endsection

@extends('admin.layout')
@section('title', 'แก้ไขทรัพย์สิน')

@section('content')
<div class="mb-4">
    <h5 class="fw-bold"><i class="fas fa-edit me-2 text-warning"></i>แก้ไขทรัพย์สิน</h5>
</div>

<div class="common-card">
    <form method="POST" action="{{ route('admin.lost-items.update', $lostItem) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.lost_items._form')
        <div class="d-flex gap-2 justify-content-end p-3 border-top">
            <a href="{{ route('admin.lost-items.index') }}" class="btn btn-secondary">ยกเลิก</a>
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>บันทึก</button>
        </div>
    </form>
</div>
@endsection

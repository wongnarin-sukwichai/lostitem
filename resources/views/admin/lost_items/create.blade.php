@extends('admin.layout')
@section('title', 'เพิ่มทรัพย์สิน')

@section('content')
<div class="mb-4">
    <h5 class="fw-bold"><i class="fas fa-plus me-2 text-warning"></i>เพิ่มทรัพย์สิน</h5>
</div>

<div class="common-card">
    <form method="POST" action="{{ route('admin.lost-items.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.lost_items._form')
        <div class="d-flex gap-2 justify-content-end p-3 border-top">
            <a href="{{ route('admin.lost-items.index') }}" class="btn btn-secondary">ยกเลิก</a>
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>บันทึก</button>
        </div>
    </form>
</div>
@endsection

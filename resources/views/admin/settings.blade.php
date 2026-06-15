@extends('admin.layout')
@section('title', 'ตั้งค่า')
@section('content')
<div class="mb-4">
    <h5 class="fw-bold"><i class="fas fa-cog me-2 text-warning"></i>ตั้งค่าข้อความรายละเอียด (หน้าบ้าน)</h5>
</div>
<div class="common-card">
    <form method="POST" action="{{ route('admin.settings.update') }}" class="p-3">
        @csrf
        <div class="mb-4">
            <label class="form-label fw-bold">ข้อความติดต่อรับของคืน</label>
            <div class="alert alert-light border shadow-sm py-2 px-3 mb-3 small text-muted">
                <i class="fas fa-info-circle me-2 text-warning"></i>
                รองรับ HTML Tag: <code>&lt;br&gt;</code> ขึ้นบรรทัดใหม่, <code>&lt;strong&gt;...&lt;/strong&gt;</code> ตัวหนา, <code>&lt;u&gt;...&lt;/u&gt;</code> ขีดเส้นใต้
            </div>
            <textarea name="contact_info" class="form-control" rows="6" required>{{ old('contact_info', $contactInfo) }}</textarea>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>บันทึก</button>
        </div>
    </form>
</div>
<div class="card border-0 shadow-sm bg-white mt-4" style="border-radius:12px;">
    <div class="card-body p-4">
        <h6 class="text-secondary fw-bold mb-3"><i class="fas fa-eye me-2"></i>ตัวอย่างการแสดงผลจริง</h6>
        <div class="alert alert-warning border-0 d-flex align-items-start mb-0"
             style="font-size:14px;background-color:#fffbef;color:#856404;border-radius:8px;padding:20px;">
            <i class="fas fa-info-circle me-3 mt-1 text-warning" style="font-size:24px;"></i>
            <div style="line-height:1.6;width:100%;">{!! $contactInfo !!}</div>
        </div>
    </div>
</div>
@endsection

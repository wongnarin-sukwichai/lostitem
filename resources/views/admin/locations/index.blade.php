@extends('admin.layout')
@section('title', 'สถานที่')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="fas fa-map-marker-alt me-2 text-warning"></i>จัดการสถานที่</h5>
    <a href="{{ route('admin.locations.create') }}" class="btn btn-primary rounded-pill px-4"><i class="fas fa-plus me-2"></i>เพิ่มสถานที่</a>
</div>
<div class="common-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light"><tr><th class="ps-4">#</th><th>ชื่อสถานที่</th><th class="text-center">สถานะ</th><th class="text-center">จัดการ</th></tr></thead>
            <tbody>
                @forelse($locations as $i => $loc)
                <tr>
                    <td class="ps-4 text-muted">{{ $locations->firstItem() + $i }}</td>
                    <td class="fw-bold">{{ $loc->location_name }}</td>
                    <td class="text-center"><span class="badge {{ $loc->status ? 'bg-success' : 'bg-secondary' }}">{{ $loc->status ? 'เปิดใช้งาน' : 'ปิด' }}</span></td>
                    <td class="text-center">
                        <a href="{{ route('admin.locations.edit', $loc) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.locations.destroy', $loc) }}" class="d-inline" onsubmit="return confirm('ยืนยันการลบ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-5 text-muted">ไม่พบข้อมูล</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $locations->links() }}</div>
</div>
@endsection

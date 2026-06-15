@extends('admin.layout')
@section('title', 'เจ้าหน้าที่')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="fas fa-users me-2 text-warning"></i>จัดการเจ้าหน้าที่</h5>
    <a href="{{ route('admin.staffs.create') }}" class="btn btn-primary rounded-pill px-4"><i class="fas fa-plus me-2"></i>เพิ่มเจ้าหน้าที่</a>
</div>
<div class="common-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light"><tr><th class="ps-4">#</th><th>ชื่อ-นามสกุล</th><th>ตำแหน่ง</th><th class="text-center">สถานะ</th><th class="text-center">จัดการ</th></tr></thead>
            <tbody>
                @forelse($staffs as $i => $s)
                <tr>
                    <td class="ps-4 text-muted">{{ $staffs->firstItem() + $i }}</td>
                    <td class="fw-bold">{{ $s->first_name }} {{ $s->last_name }}</td>
                    <td class="text-muted">{{ $s->position ?? '-' }}</td>
                    <td class="text-center"><span class="badge {{ $s->status ? 'bg-success' : 'bg-secondary' }}">{{ $s->status ? 'ปฏิบัติงาน' : 'ไม่ปฏิบัติงาน' }}</span></td>
                    <td class="text-center">
                        <a href="{{ route('admin.staffs.edit', $s) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.staffs.destroy', $s) }}" class="d-inline" onsubmit="return confirm('ยืนยันการลบ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5 text-muted">ไม่พบข้อมูล</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $staffs->links() }}</div>
</div>
@endsection

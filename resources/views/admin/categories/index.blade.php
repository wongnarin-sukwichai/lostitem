@extends('admin.layout')
@section('title', 'หมวดหมู่')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="fas fa-tags me-2 text-warning"></i>จัดการหมวดหมู่</h5>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus me-2"></i>เพิ่มหมวดหมู่
    </a>
</div>
<div class="common-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">#</th>
                    <th>ชื่อหมวดหมู่</th>
                    <th class="text-center">สถานะ</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $i => $cat)
                <tr>
                    <td class="ps-4 text-muted">{{ $categories->firstItem() + $i }}</td>
                    <td class="fw-bold">{{ $cat->category_name }}</td>
                    <td class="text-center">
                        <span class="badge {{ $cat->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $cat->status ? 'เปิดใช้งาน' : 'ปิด' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="d-inline" onsubmit="return confirm('ยืนยันการลบ?')">
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
    <div class="p-3">{{ $categories->links() }}</div>
</div>
@endsection

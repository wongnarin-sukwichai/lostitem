@extends('admin.layout')
@section('title', 'ทรัพย์สิน')

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold"><i class="fas fa-boxes me-2 text-warning"></i>จัดการทรัพย์สิน</h5>
        <small class="text-muted">รายการทรัพย์สินที่พบทั้งหมด</small>
    </div>
    <a href="{{ route('admin.lost-items.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus me-2"></i>เพิ่มทรัพย์สิน
    </a>
</div>

{{-- Search & Filter --}}
<div class="common-card mb-3">
    <form class="row g-2 align-items-end p-3" method="GET">
        <div class="col-md-3">
            <label class="form-label small text-muted fw-bold mb-1">คำค้นหา</label>
            <input type="text" name="search" class="form-control form-control-sm"
                placeholder="ชื่อทรัพย์สิน / ประเภท / สถานที่..."
                value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small text-muted fw-bold mb-1">ประเภท</label>
            <select name="category_id" class="form-select form-select-sm">
                <option value="">ทั้งหมด</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->category_id }}" {{ request('category_id') == $cat->category_id ? 'selected' : '' }}>
                    {{ $cat->category_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small text-muted fw-bold mb-1">สถานที่</label>
            <select name="location_id" class="form-select form-select-sm">
                <option value="">ทั้งหมด</option>
                @foreach($locations as $loc)
                <option value="{{ $loc->location_id }}" {{ request('location_id') == $loc->location_id ? 'selected' : '' }}>
                    {{ $loc->location_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small text-muted fw-bold mb-1">เจ้าหน้าที่</label>
            <select name="user_id" class="form-select form-select-sm">
                <option value="">ทั้งหมด</option>
                @foreach($users as $u)
                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                    {{ $u->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1">
            <label class="form-label small text-muted fw-bold mb-1">สถานะ</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">ทั้งหมด</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>รอรับคืน</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>คืนแล้ว</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2 align-items-end">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="fas fa-search me-1"></i>ค้นหา
            </button>
            @if(request()->anyFilled(['search','status','category_id','location_id','user_id']))
            <a href="{{ route('admin.lost-items.index') }}" class="btn btn-secondary btn-sm flex-shrink-0" title="ล้างตัวกรอง">
                <i class="fas fa-redo"></i>
            </a>
            @endif
        </div>
        @if(request()->anyFilled(['search','status','category_id','location_id','user_id']))
        <div class="col-12 mt-1">
            <small class="text-muted bg-light px-2 py-1 rounded border">
                <i class="fas fa-info-circle me-1"></i>
                พบข้อมูล <strong>{{ number_format($items->total()) }}</strong> รายการ (จากเงื่อนไขที่เลือก)
            </small>
        </div>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="common-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="d-none d-md-table-cell text-center" style="width:50px;">ลำดับ</th>
                    <th style="width:60px;padding-left:16px;">รูป</th>
                    <th class="text-center" style="width:55px;">แสดง</th>
                    <th>ชื่อทรัพย์สิน</th>
                    <th class="d-none d-lg-table-cell">ประเภท</th>
                    <th class="d-none d-md-table-cell">สถานที่</th>
                    <th class="d-none d-md-table-cell" style="width:95px;">วันที่พบ</th>
                    <th style="width:110px;">สถานะ</th>
                    <th class="d-none d-lg-table-cell">เจ้าหน้าที่</th>
                    <th class="text-center" style="width:120px;">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                @php
                    $origSrc = $item->image
                        ? asset('uploads/'.$item->image)
                        : asset('admin/img/no-image.png');
                    $img = $item->is_image_hidden ? asset('admin/img/hidden.png') : $origSrc;

                    $catName = $item->category?->category_name ?? '';
                    if (str_contains($catName, 'กระเป๋า'))            { $catIcon = 'fa-wallet';    $catColor = 'text-success'; }
                    elseif (str_contains($catName, 'กุญแจ'))           { $catIcon = 'fa-key';       $catColor = 'text-warning'; }
                    elseif (str_contains($catName, 'อิเล็กทรอนิกส์')) { $catIcon = 'fa-mobile-alt'; $catColor = 'text-info'; }
                    elseif (str_contains($catName, 'เสื้อผ้า'))        { $catIcon = 'fa-tshirt';   $catColor = 'text-danger'; }
                    elseif (str_contains($catName, 'เอกสาร'))          { $catIcon = 'fa-file-alt'; $catColor = 'text-primary'; }
                    else                                                { $catIcon = 'fa-tag';      $catColor = 'text-secondary'; }

                    $rowNo = ($items->currentPage() - 1) * $items->perPage() + $loop->iteration;
                @endphp
                <tr>
                    <td class="fw-semibold text-muted text-center d-none d-md-table-cell">{{ $rowNo }}</td>
                    <td style="padding-left:16px;">
                        <img src="{{ $img }}"
                             data-orig="{{ $origSrc }}"
                             class="thumb-img rounded shadow-sm"
                             width="40" height="40"
                             style="object-fit:cover;cursor:zoom-in;"
                             title="คลิกเพื่อดูรูปใหญ่">
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-light border shadow-sm btn-toggle-img"
                                type="button"
                                data-id="{{ $item->lost_item_id }}"
                                title="{{ $item->is_image_hidden ? 'ซ่อนอยู่ (คลิกเพื่อแสดง)' : 'แสดงปกติ (คลิกเพื่อซ่อน)' }}">
                            <i class="fas {{ $item->is_image_hidden ? 'fa-eye-slash text-danger' : 'fa-eye text-success' }}"></i>
                        </button>
                    </td>
                    <td>
                        <a href="#" class="fw-bold text-dark text-decoration-none btn-view-detail"
                           data-id="{{ $item->lost_item_id }}">
                            {{ $item->item_name }}
                        </a>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <i class="fas {{ $catIcon }} me-1 {{ $catColor }}"></i>
                        <small>{{ $catName ?: '-' }}</small>
                    </td>
                    <td class="d-none d-md-table-cell text-muted small">
                        <i class="fas fa-map-marker-alt me-1 text-danger"></i>{{ $item->location?->location_name ?? '-' }}
                    </td>
                    <td class="d-none d-md-table-cell text-muted small">{{ $item->found_date?->format('d/m/Y') }}</td>
                    <td>
                        @if($item->status === 'pending')
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>รอรับคืน</span>
                        @else
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>คืนแล้ว</span>
                        @endif
                    </td>
                    <td class="d-none d-lg-table-cell text-muted small">
                        {{ $item->user?->name ?? '-' }}
                    </td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            @if($item->status === 'pending')
                            <button class="btn btn-sm btn-success" title="บันทึกการคืน"
                                    onclick="openReturnModal({{ $item->lost_item_id }}, '{{ addslashes($item->item_name) }}')">
                                <i class="fas fa-undo"></i>
                            </button>
                            @endif
                            <a href="{{ route('admin.lost-items.edit', $item) }}" class="btn btn-sm btn-warning" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($item->status === 'pending')
                            <form method="POST" action="{{ route('admin.lost-items.destroy', $item) }}" class="d-inline"
                                  onsubmit="return confirm('ยืนยันการลบ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="ลบ"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary"></i>ไม่พบข้อมูล
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $items->links() }}</div>
</div>

{{-- Return Modal --}}
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-undo me-2"></i>บันทึกการคืนทรัพย์สิน</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="returnForm">
                @csrf
                <div class="modal-body p-4">
                    <p class="mb-3 text-muted">รายการ: <strong id="returnItemName"></strong></p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">รหัสนิสิต</label>
                            <input type="text" name="student_id" class="form-control" maxlength="11" placeholder="เช่น 6601xxxxxxx">
                        </div>
                        <div class="col-12"></div>
                        <div class="col-md-6">
                            <label class="form-label">ชื่อผู้รับคืน <span class="text-danger">*</span></label>
                            <input type="text" name="owner_first_name" class="form-control" required placeholder="ชื่อจริง">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" name="owner_last_name" class="form-control" required placeholder="นามสกุล">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">เบอร์โทร</label>
                            <input type="text" name="tel" class="form-control" maxlength="10" placeholder="08x-xxx-xxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">อีเมล</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-success px-4"><i class="fas fa-save me-2"></i>บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Detail Modal --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>รายละเอียดทรัพย์สิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailBody">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> กำลังโหลด...</div>
            </div>
        </div>
    </div>
</div>

{{-- Image Zoom Modal --}}
<div class="modal fade" id="imageZoomModal" tabindex="-1" style="z-index:1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal" style="z-index:1070;"></button>
                <img id="zoomedImg" src="" class="img-fluid rounded shadow-lg" style="max-height:90vh;">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const LOST_ITEMS_URL  = '{{ url("admin/lost-items") }}';
const IMG_HIDDEN      = '{{ asset("admin/img/hidden.png") }}';
const IMG_NO_IMAGE    = '{{ asset("admin/img/no-image.png") }}';
const UPLOADS_URL     = '{{ asset("uploads") }}';
const CSRF            = document.querySelector('meta[name="csrf-token"]').content;

/* ─── Return modal ─── */
let returnItemId = null;

function openReturnModal(id, name) {
    returnItemId = id;
    document.getElementById('returnItemName').textContent = name;
    document.getElementById('returnForm').reset();
    new bootstrap.Modal(document.getElementById('returnModal')).show();
}

document.getElementById('returnForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const res = await fetch(`${LOST_ITEMS_URL}/${returnItemId}/return`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF },
        body: new FormData(this),
    });
    const data = await res.json();
    if (data.success) location.reload();
    else alert(data.message || 'เกิดข้อผิดพลาด');
});

/* ─── Toggle image ─── */
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-toggle-img');
    if (!btn) return;

    fetch(`${LOST_ITEMS_URL}/${btn.dataset.id}/toggle-image`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;
        const row  = btn.closest('tr');
        const img  = row.querySelector('.thumb-img');
        const icon = btn.querySelector('i');
        if (data.new_state) {
            img.src            = IMG_HIDDEN;
            icon.className     = 'fas fa-eye-slash text-danger';
            btn.title          = 'ซ่อนอยู่ (คลิกเพื่อแสดง)';
        } else {
            img.src            = img.dataset.orig;
            icon.className     = 'fas fa-eye text-success';
            btn.title          = 'แสดงปกติ (คลิกเพื่อซ่อน)';
        }
    });
});

/* ─── Thumbnail zoom ─── */
document.addEventListener('click', function (e) {
    const img = e.target.closest('.thumb-img');
    if (!img) return;
    zoomImage(img.src);
});

/* ─── Detail modal ─── */
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-view-detail');
    if (!btn) return;
    e.preventDefault();

    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    document.getElementById('detailBody').innerHTML =
        '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> กำลังโหลด...</div>';
    modal.show();

    fetch(`${LOST_ITEMS_URL}/${btn.dataset.id}`, { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(item => {
        const imgSrc = item.is_image_hidden
            ? IMG_HIDDEN
            : (item.image ? `${UPLOADS_URL}/${item.image}` : IMG_NO_IMAGE);

        const statusBadge = item.status === 'pending'
            ? '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>รอรับคืน</span>'
            : '<span class="badge bg-success"><i class="fas fa-check me-1"></i>คืนแล้ว</span>';

        const fmtDate = d => d ? d.split('T')[0].split('-').reverse().join('/') : '-';

        let returnInfo = '';
        if (item.status === 'returned' && item.owner_first_name) {
            returnInfo = `
            <div class="alert alert-success border-0 py-2 mt-2 mb-0">
                <i class="fas fa-check-circle me-2"></i>
                <strong>คืนให้:</strong> ${item.owner_first_name} ${item.owner_last_name}
                ${item.student_id ? `&nbsp;|&nbsp;รหัสนิสิต: ${item.student_id}` : ''}
                ${item.tel        ? `&nbsp;|&nbsp;โทร: ${item.tel}` : ''}
                ${item.returned_date ? `<br><small class="text-muted">วันที่คืน: ${fmtDate(item.returned_date)}</small>` : ''}
            </div>`;
        }

        document.getElementById('detailBody').innerHTML = `
        <div class="row g-3">
            <div class="col-md-4 text-center">
                <img src="${imgSrc}" class="img-fluid rounded shadow" style="max-height:200px;object-fit:contain;cursor:zoom-in;"
                     onclick="zoomImage('${imgSrc}')">
            </div>
            <div class="col-md-8">
                <h5 class="fw-bold mb-3">${item.item_name}</h5>
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" style="width:35%">ประเภท</td>
                        <td>${item.category?.category_name ?? '-'}</td></tr>
                    <tr><td class="text-muted">สถานที่พบ</td>
                        <td>${item.location?.location_name ?? '-'}</td></tr>
                    <tr><td class="text-muted">วันที่พบ</td>
                        <td>${fmtDate(item.found_date)}</td></tr>
                    <tr><td class="text-muted">เจ้าหน้าที่</td>
                        <td>${item.user?.name ?? '-'}</td></tr>
                    <tr><td class="text-muted">สถานะ</td>
                        <td>${statusBadge}</td></tr>
                    <tr><td class="text-muted">รายละเอียด</td>
                        <td>${item.description ?? '-'}</td></tr>
                </table>
                ${returnInfo}
            </div>
        </div>`;
    });
});

/* ─── Zoom image ─── */
function zoomImage(src) {
    document.getElementById('zoomedImg').src = src;
    new bootstrap.Modal(document.getElementById('imageZoomModal')).show();
}
</script>
@endpush

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ระบบสืบค้นของหาย | Lost & Found MSU</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<nav class="navbar sticky-top">
    <div class="container-fluid px-4 d-flex align-items-center">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-box-open me-2"></i>Lost & Found <span style="font-weight:300;font-size:.9em;">MSU</span>
        </a>
        <a href="{{ route('login') }}" class="btn-signin ms-auto">
            <i class="fas fa-user-shield me-1"></i> เจ้าหน้าที่
        </a>
    </div>
</nav>

<section class="hero-section">
    <div class="container px-3">
        <h1 class="hero-title">ระบบสืบค้น<span>ของหาย</span></h1>
        <p class="hero-subtitle">สำนักวิทยบริการ มหาวิทยาลัยมหาสารคาม</p>
        <div class="search-box">
            <form action="{{ route('home') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-lg"
                    placeholder="ค้นหาชื่อสิ่งของ..." value="{{ $search }}">
                @if($categoryId > 0)
                <input type="hidden" name="category" value="{{ $categoryId }}">
                @endif
                <button type="submit" class="btn btn-search"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
</section>

<div class="container mb-5">
    {{-- Category filter (mobile) --}}
    <div class="d-block d-md-none mb-4">
        <label class="text-muted small mb-2 ps-1">เลือกหมวดหมู่</label>
        <select class="form-select border-warning shadow-sm" onchange="location = this.value;" style="border-radius:12px;">
            <option value="{{ route('home', ['search' => $search, 'limit' => $limit]) }}">ทั้งหมด</option>
            @foreach($categories as $cat)
            <option value="{{ route('home', ['category' => $cat->category_id, 'search' => $search, 'limit' => $limit]) }}"
                {{ $categoryId == $cat->category_id ? 'selected' : '' }}>
                {{ $cat->category_name }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- Category filter (desktop) --}}
    <div class="d-none d-md-flex flex-wrap justify-content-center gap-2 mb-4">
        <a href="{{ route('home', ['search' => $search, 'limit' => $limit]) }}"
           class="filter-tag {{ $categoryId == 0 ? 'active' : '' }}">
            <i class="fas fa-th-large me-1"></i> ทั้งหมด
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('home', ['category' => $cat->category_id, 'search' => $search, 'limit' => $limit]) }}"
           class="filter-tag {{ $categoryId == $cat->category_id ? 'active' : '' }}">
            {{ $cat->category_name }}
        </a>
        @endforeach
    </div>

    {{-- Limit selector --}}
    <div class="d-flex justify-content-end mb-2 align-items-center">
        <form method="GET" class="d-flex align-items-center">
            <input type="hidden" name="search" value="{{ $search }}">
            @if($categoryId > 0)<input type="hidden" name="category" value="{{ $categoryId }}">@endif
            <label class="me-2 small text-muted">แสดง:</label>
            <select name="limit" class="form-select form-select-sm border-0 shadow-none bg-white"
                    style="width:auto;cursor:pointer;border-radius:8px;" onchange="this.form.submit()">
                @foreach([10, 20, 50, 100] as $val)
                <option value="{{ $val }}" {{ $limit == $val ? 'selected' : '' }}>{{ $val }} รายการ</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Items table --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center" width="15%">รูปภาพ</th>
                        <th>ชื่อทรัพย์สิน</th>
                        <th width="200" class="d-none d-md-table-cell">รายละเอียด</th>
                        <th class="col-date d-none d-md-table-cell">วันที่พบ</th>
                        <th class="d-none d-md-table-cell">สถานที่พบ</th>
                        <th width="20%" class="text-center">สถานะ</th>
                        <th width="12%" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    @php
                        $img = $item->is_image_hidden
                            ? asset('admin/img/hidden.png')
                            : ($item->image && file_exists(public_path('uploads/'.$item->image))
                                ? asset('uploads/'.$item->image)
                                : asset('admin/img/no-image.png'));
                        $dateThai = $item->found_date?->format('d/m/Y') ?? '';
                        $returnDate = ($item->status === 'returned' && $item->returned_date) ? $item->returned_date->format('d/m/Y') : '';
                        $modalAttrs = 'data-bs-toggle="modal" data-bs-target="#itemModal"
                            data-img="'.e($img).'"
                            data-title="'.e($item->item_name).'"
                            data-loc="'.e($item->location?->location_name).'"
                            data-date="'.$dateThai.'"
                            data-desc="'.e($item->description ?? '-').'"
                            data-cat="'.e($item->category?->category_name).'"
                            data-status="'.$item->status.'"
                            data-returndate="'.$returnDate.'"';
                    @endphp
                    <tr>
                        <td class="text-center">
                            <img src="{{ $img }}" class="img-thumb" style="cursor:zoom-in;"
                                 onclick="zoomImage('{{ $img }}')" title="คลิกเพื่อดูรูปใหญ่">
                        </td>
                        <td>
                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:35vw;">
                                <a href="#" class="fw-bold text-dark text-decoration-none" {!! $modalAttrs !!}>
                                    {{ $item->item_name }}
                                </a>
                            </div>
                            <div class="text-muted small text-truncate" style="max-width:35vw;">
                                <i class="fas fa-tag me-1"></i>{{ $item->category?->category_name }}
                            </div>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <div class="text-muted small text-truncate" style="max-width:200px;">
                                {{ $item->description ?? '-' }}
                            </div>
                        </td>
                        <td class="col-date d-none d-md-table-cell">
                            <i class="far fa-calendar-alt me-1 text-secondary"></i>{{ $dateThai }}
                        </td>
                        <td class="d-none d-md-table-cell">
                            <i class="fas fa-map-marker-alt me-1 text-danger"></i>{{ $item->location?->location_name }}
                        </td>
                        <td class="text-center align-middle">
                            @if($item->status === 'pending')
                                <span class="badge-status badge-pending"><i class="fas fa-clock me-1"></i> รอรับคืน</span>
                            @else
                                <span class="badge-status badge-returned"><i class="fas fa-check-circle me-1"></i> คืนแล้ว</span>
                                @if($returnDate)
                                <div class="text-success mt-1" style="font-size:11px;"><i class="fas fa-check-double me-1"></i>คืนเมื่อ {{ $returnDate }}</div>
                                @endif
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-dark rounded-pill px-3" {!! $modalAttrs !!}>
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-box">
                                <i class="fas fa-search-minus" style="font-size:40px;color:#ddd;"></i>
                                <p class="mt-3 text-muted">ไม่พบข้อมูลทรัพย์สิน</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($items->lastPage() > 1)
    <div class="d-flex justify-content-center mt-4">
        {{ $items->links() }}
    </div>
    <div class="text-center text-muted small mt-2">
        พบข้อมูลทั้งหมด {{ number_format($items->total()) }} รายการ (หน้า {{ $items->currentPage() }} จาก {{ $items->lastPage() }})
    </div>
    @endif
</div>

{{-- Item Modal --}}
<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:15px;overflow:hidden;border:none;">
            <div class="modal-body p-0">
                <div style="background-color:#f1f5f9;text-align:center;position:relative;">
                    <img id="modalImg" src="" style="max-width:100%;max-height:350px;object-fit:contain;cursor:zoom-in;" onclick="zoomImage(this.src)">
                    <div id="modalStatusBadge" style="position:absolute;top:15px;right:15px;"></div>
                </div>
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-white text-dark border shadow-sm" id="modalCat"></span>
                        <small class="text-muted" id="modalDate"></small>
                    </div>
                    <h5 class="fw-bold mb-3" id="modalTitle" style="color:#333;"></h5>
                    <div class="mb-3 p-3 bg-white border rounded shadow-sm">
                        <small class="text-muted d-block fw-bold mb-1">สถานที่พบ</small>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                            <span id="modalLoc" style="color:#333;"></span>
                        </div>
                    </div>
                    <div id="modalReturnDateSection" class="mb-3 d-none">
                        <div class="alert alert-success border-0 d-flex align-items-center py-2">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>
                                <small class="d-block fw-bold">สถานะ: คืนให้เจ้าของแล้ว</small>
                                <small>เมื่อวันที่: <span id="modalReturnDate"></span></small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <small class="text-muted d-block fw-bold mb-1">รายละเอียดเพิ่มเติม</small>
                        <p id="modalDesc" style="color:#555;font-size:14px;line-height:1.6;background:#f8fafc;padding:10px;border-radius:8px;"></p>
                    </div>
                    <div class="alert alert-warning border-0 d-flex align-items-start"
                         style="font-size:13px;background-color:#fffbef;color:#856404;">
                        <i class="fas fa-info-circle me-3 mt-1 text-warning" style="font-size:18px;"></i>
                        <div>{!! $contactInfoHtml !!}</div>
                    </div>
                    <button type="button" class="btn btn-dark w-100 py-2 mt-1" style="border-radius:10px;" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Zoom Modal --}}
<div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true" style="z-index:1060;">
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

<footer>
    <div class="container">
        <p class="mb-1">© {{ date('Y') }} Lost & Found System. Mahasarakham University.</p>
        <small>สำนักวิทยบริการ มหาวิทยาลัยมหาสารคาม</small>
    </div>
</footer>

<script>
document.getElementById('itemModal').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    if (!btn) return;
    document.getElementById('modalImg').src       = btn.dataset.img;
    document.getElementById('modalTitle').textContent = btn.dataset.title;
    document.getElementById('modalLoc').textContent   = btn.dataset.loc;
    document.getElementById('modalDate').textContent  = btn.dataset.date;
    document.getElementById('modalDesc').textContent  = btn.dataset.desc;
    document.getElementById('modalCat').textContent   = btn.dataset.cat;
    const status  = btn.dataset.status;
    const rdate   = btn.dataset.returndate;
    const badge   = document.getElementById('modalStatusBadge');
    badge.innerHTML = status === 'pending'
        ? '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>รอรับคืน</span>'
        : '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>คืนแล้ว</span>';
    const retSection = document.getElementById('modalReturnDateSection');
    if (status === 'returned' && rdate) {
        document.getElementById('modalReturnDate').textContent = rdate;
        retSection.classList.remove('d-none');
    } else {
        retSection.classList.add('d-none');
    }
});
function zoomImage(src) {
    document.getElementById('zoomedImg').src = src;
    new bootstrap.Modal(document.getElementById('imageZoomModal')).show();
}
</script>
</body>
</html>

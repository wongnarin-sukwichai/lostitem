@extends('admin.layout')
@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h5 class="mb-1 fw-bold text-dark"><i class="fas fa-tachometer-alt me-2 text-warning"></i>ภาพรวมระบบ</h5>
        <small class="text-muted">ข้อมูลสถิติและสถานะทรัพย์สินล่าสุด</small>
    </div>
    <button class="btn btn-white border shadow-sm text-secondary px-3" type="button"
            data-bs-toggle="modal" data-bs-target="#exportModal" style="font-size:14px;background:#fff;">
        <i class="fas fa-file-excel text-success me-2"></i>นำข้อมูลออก (Export)
    </button>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['ทรัพย์สินทั้งหมด', $totalItems,    'boxes',        'primary'],
        ['รอรับคืน',         $pendingItems,  'clock',        'warning'],
        ['คืนแล้ว',          $returnedItems, 'check-circle', 'success'],
        ['เพิ่มวันนี้',       $todayItems,    'calendar-day', 'info'],
    ] as [$label, $value, $icon, $color])
    <div class="col-xl-3 col-md-6">
        <div class="stat-card text-center">
            <i class="fas fa-{{ $icon }}"></i>
            <h5>{{ number_format($value) }}</h5>
            <small>{{ $label }}</small>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="common-card">
            <div class="card-header">
                <span><i class="fas fa-history me-2"></i>ทรัพย์สินที่พบล่าสุด</span>
                <a href="{{ route('admin.lost-items.index') }}" class="btn btn-sm btn-light text-primary" style="font-size:12px;border-radius:20px;padding:2px 10px;">
                    ดูทั้งหมด <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width:60px;padding-left:20px;">รูป</th>
                            <th>ชื่อทรัพย์สิน</th>
                            <th>วันที่พบ</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestItems as $item)
                        @php
                            $img = $item->image ? asset('uploads/'.$item->image) : asset('admin/img/no-image.png');
                        @endphp
                        <tr>
                            <td style="padding-left:20px;">
                                <img src="{{ $img }}" class="rounded shadow-sm" width="40" height="40" style="object-fit:cover;">
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->item_name }}</div>
                                <small class="text-muted"><i class="fas fa-tag me-1"></i>{{ $item->category?->category_name }}</small>
                            </td>
                            <td>
                                <div class="text-dark">{{ $item->found_date?->format('d/m/Y') }}</div>
                                <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>{{ $item->location?->location_name }}</small>
                            </td>
                            <td>
                                @if($item->status === 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>รอรับคืน</span>
                                @else
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>คืนแล้ว</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">ไม่พบข้อมูล</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="common-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-chart-bar me-2"></i>สถิติการพบทรัพย์สิน</span>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-light active" onclick="updateChart('daily',this)">วัน</button>
                    <button type="button" class="btn btn-sm btn-outline-light" onclick="updateChart('monthly',this)">เดือน</button>
                    <button type="button" class="btn btn-sm btn-outline-light" onclick="updateChart('yearly',this)">ปี</button>
                </div>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="mainChart" style="width:100%;height:280px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="common-card">
            <div class="card-header"><span><i class="fas fa-chart-pie me-2"></i>สัดส่วนประเภททรัพย์สิน</span></div>
            <div class="card-body">
                <div class="row align-items-center h-100">
                    <div class="col-sm-5 text-center"><canvas id="categoryChart" style="max-height:200px;"></canvas></div>
                    <div class="col-sm-7">
                        <div class="mt-3 mt-sm-0 ps-sm-3">
                            @php $colors = ['#3b82f6','#8b5cf6','#ec4899','#f59e0b','#10b981']; @endphp
                            @foreach($categoryData->take(5) as $i => $cat)
                            @php $percent = $totalItems > 0 ? round(($cat->lost_items_count / $totalItems) * 100) : 0; @endphp
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center text-truncate" style="min-width:0;">
                                    <span class="legend-dot me-2 flex-shrink-0" style="background:{{ $colors[$i % 5] }}"></span>
                                    <small class="text-muted text-truncate">{{ $cat->category_name }}</small>
                                </div>
                                <div class="ms-2 flex-shrink-0 text-end">
                                    <span class="fw-bold text-dark me-1">{{ $percent }}%</span>
                                    <small class="text-muted" style="font-size:11px;">({{ $cat->lost_items_count }} ชิ้น)</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="common-card">
            <div class="card-header"><span><i class="fas fa-map-marker-alt me-2"></i>สัดส่วนสถานที่พบ</span></div>
            <div class="card-body">
                <div class="row align-items-center h-100">
                    <div class="col-sm-5 text-center"><canvas id="locationChart" style="max-height:200px;"></canvas></div>
                    <div class="col-sm-7">
                        <div class="mt-3 mt-sm-0 ps-sm-3">
                            @php $locColors = ['#ef4444','#eab308','#84cc16','#06b6d4','#8b5cf6']; @endphp
                            @foreach($locationData->take(5) as $i => $loc)
                            @php $percent = $totalItems > 0 ? round(($loc->lost_items_count / $totalItems) * 100) : 0; @endphp
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center text-truncate" style="min-width:0;">
                                    <span class="legend-dot me-2 flex-shrink-0" style="background:{{ $locColors[$i % 5] }}"></span>
                                    <small class="text-muted text-truncate">{{ $loc->location_name }}</small>
                                </div>
                                <div class="ms-2 flex-shrink-0 text-end">
                                    <span class="fw-bold text-dark me-1">{{ $percent }}%</span>
                                    <small class="text-muted" style="font-size:11px;">({{ $loc->lost_items_count }} ชิ้น)</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
<script>
const dashboardData = {
    daily:   { labels: @json($dailyLabels),   data: @json($dailyData),   barThickness: 20 },
    monthly: { labels: @json($monthlyLabels), data: @json($monthlyData), barThickness: 15 },
    yearly:  { labels: @json($yearlyLabels),  data: @json($yearlyData),  barThickness: 40 },
    category: { labels: @json($categoryData->pluck('category_name')), data: @json($categoryData->pluck('lost_items_count')) },
    location: { labels: @json($locationData->pluck('location_name')), data: @json($locationData->pluck('lost_items_count')) },
};
</script>
<script src="{{ asset('admin/js/dashboard.js') }}"></script>

{{-- Export Modal --}}
<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:15px;">
            <div class="modal-header bg-success text-white" style="border-radius:15px 15px 0 0;">
                <h5 class="modal-title"><i class="fas fa-file-excel me-2"></i>เลือกช่วงเวลา (Export)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.export') }}" method="POST" onsubmit="return validateExportDate()">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-light border-success text-success d-flex align-items-center mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>เลือกช่วงวันที่ "พบทรัพย์สิน" เพื่อออกรายงาน</small>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">เริ่มต้น</label>
                            <input type="text" name="start_date" id="export_start" class="form-control" placeholder="เลือกวันที่" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">สิ้นสุด</label>
                            <input type="text" name="end_date" id="export_end" class="form-control" placeholder="เลือกวันที่" required readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-radius:0 0 15px 15px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-success px-4 rounded-pill shadow-sm">
                        <i class="fas fa-download me-2"></i>ดาวน์โหลด Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
flatpickr("#export_start", { locale:"th", dateFormat:"Y-m-d", altInput:true, altFormat:"d/m/Y" });
flatpickr("#export_end",   { locale:"th", dateFormat:"Y-m-d", altInput:true, altFormat:"d/m/Y" });
function validateExportDate() {
    const s = document.getElementById('export_start').value;
    const e = document.getElementById('export_end').value;
    if (!s || !e) { alert('กรุณาเลือกช่วงวันที่'); return false; }
    if (s > e) { alert('วันที่เริ่มต้นต้องน้อยกว่าหรือเท่ากับวันที่สิ้นสุด'); return false; }
    return true;
}
</script>
@endpush

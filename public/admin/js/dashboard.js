/* admin/assets/js/dashboard.js */
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. ตั้งค่า Flatpickr สำหรับ Export Modal
    initExportDatePickers();
    
    // 2. เริ่มต้นกราฟ (ถ้ามี Canvas)
    if(document.getElementById('mainChart')) {
        initMainChart();
        initDonutCharts();
    }
});

/* ===== ฟังก์ชันจัดการ Flatpickr ในหน้า Dashboard ===== */
function initExportDatePickers() {
    const exportModalEl = document.getElementById('exportModal');
    if (!exportModalEl) return;

    let startPicker = null;
    let endPicker = null;

    // เมื่อเปิด Modal
    exportModalEl.addEventListener('shown.bs.modal', function () {
        // ทำลาย instance เก่า (ถ้ามี)
        if (startPicker) startPicker.destroy();
        if (endPicker) endPicker.destroy();

        // สร้าง Flatpickr ใหม่
        startPicker = flatpickr("#export_start", {
            locale: "th",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "j F Y",
            maxDate: "today",
            disableMobile: true,
            clickOpens: true,
            onChange: function(selectedDates, dateStr) {
                // อัปเดต minDate ของ endPicker
                if (endPicker && dateStr) {
                    endPicker.set('minDate', dateStr);
                }
            }
        });

        endPicker = flatpickr("#export_end", {
            locale: "th",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "j F Y",
            maxDate: "today",
            disableMobile: true,
            clickOpens: true
        });

    });

    // เมื่อปิด Modal - เคลียร์ค่า
    exportModalEl.addEventListener('hidden.bs.modal', function () {
        if (startPicker) {
            startPicker.destroy();
            startPicker = null;
        }
        if (endPicker) {
            endPicker.destroy();
            endPicker = null;
        }
        // เคลียร์ค่าใน input
        document.getElementById('export_start').value = '';
        document.getElementById('export_end').value = '';
    });
}

/* ===== ส่วนจัดการ Main Chart (Bar Chart) ===== */
let mainChart;

function initMainChart() {
    const ctxMain = document.getElementById('mainChart').getContext('2d');
    
    function createGradient(ctx) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, '#ffcc00');
        gradient.addColorStop(1, '#f59e0b');
        return gradient;
    }

    mainChart = new Chart(ctxMain, {
        type: 'bar',
        data: {
            labels: dashboardData.daily.labels,
            datasets: [{
                label: 'จำนวนรายการ',
                data: dashboardData.daily.data,
                backgroundColor: createGradient(ctxMain),
                borderRadius: 4,
                barThickness: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: { 
                        title: (context) => 'ช่วงเวลา: ' + context[0].label 
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { borderDash: [4, 4], color: '#f1f5f9' }, 
                    ticks: { stepSize: 1, color: '#64748b' } 
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { color: '#64748b', font: { size: 11 } } 
                }
            }
        }
    });
}

/* ===== ฟังก์ชันสลับกราฟ ===== */
window.updateChart = function(type, btn) {
    document.querySelectorAll('.btn-group .btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    if (dashboardData[type] && mainChart) {
        mainChart.data.labels = dashboardData[type].labels;
        mainChart.data.datasets[0].data = dashboardData[type].data;
        mainChart.data.datasets[0].barThickness = dashboardData[type].barThickness;
        mainChart.update();
    }
};

/* ===== ส่วนจัดการ Donut Charts ===== */
function initDonutCharts() {
    const donutOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        cutout: '70%',
        borderWidth: 0
    };

    if(document.getElementById('categoryChart')) {
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: dashboardData.category.labels,
                datasets: [{
                    data: dashboardData.category.data,
                    backgroundColor: ['#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981']
                }]
            },
            options: donutOptions
        });
    }

    if(document.getElementById('locationChart')) {
        new Chart(document.getElementById('locationChart'), {
            type: 'doughnut',
            data: {
                labels: dashboardData.location.labels,
                datasets: [{
                    data: dashboardData.location.data,
                    backgroundColor: ['#ef4444', '#eab308', '#84cc16', '#06b6d4']
                }]
            },
            options: donutOptions
        });
    }
}

/* ===== ฟังก์ชันตรวจสอบ Form Export ===== */
window.validateExportDate = function() {
    const start = document.getElementById('export_start').value;
    const end = document.getElementById('export_end').value;

    if (!start || !end) {
        alert("⚠️ กรุณาเลือกวันที่เริ่มต้นและสิ้นสุด");
        return false;
    }

    if (start > end) {
        alert("⚠️ วันที่เริ่มต้น ต้องไม่มากกว่า วันที่สิ้นสุด");
        return false;
    }
    
    return true;
};
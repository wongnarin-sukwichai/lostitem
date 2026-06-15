document.addEventListener("DOMContentLoaded", function () {

    /* ===== 1. Sidebar Toggle (ใช้ทุกหน้า) ===== */
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('overlay');
    const menuToggle = document.getElementById('menuToggle');

    if (menuToggle && sidebar && overlay) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

});

/* ===== 2. Global Function: ยืนยันการลบ (ใช้ทุกหน้า: Categories, Locations, Staffs) ===== */
// ประกาศนอก DOMContentLoaded เพื่อให้ HTML เรียก onclick="confirmDelete(...)" ได้
window.confirmDelete = function(id, url) {
    if(typeof Swal !== 'undefined') {
        // ถ้ามี SweetAlert2
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลจะถูกลบถาวร ไม่สามารถกู้คืนได้!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url + '?id=' + id;
            }
        });
    } else {
        // Fallback ถ้าไม่มี SweetAlert2 (กันเหนียว)
        if (confirm('คุณแน่ใจหรือไม่ที่จะลบรายการนี้?')) {
            window.location.href = url + '?id=' + id;
        }
    }
};
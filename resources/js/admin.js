import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('overlay');
    const sidebarToggle = document.getElementById('sidebarToggle');

    if (sidebarToggle && sidebar && overlay) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }
});

window.confirmDelete = function (id, url) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: 'ข้อมูลจะถูกลบถาวร ไม่สามารถกู้คืนได้!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ลบเลย',
            cancelButtonText: 'ยกเลิก',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url + '?id=' + id;
            }
        });
    } else {
        if (confirm('คุณแน่ใจหรือไม่ที่จะลบรายการนี้?')) {
            window.location.href = url + '?id=' + id;
        }
    }
};

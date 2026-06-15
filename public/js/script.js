const itemModal = document.getElementById('itemModal');
    itemModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        
        const status = button.getAttribute('data-bs-status');
        const returnDate = button.getAttribute('data-bs-returndate');

        document.getElementById('modalImg').src = button.getAttribute('data-bs-img');
        document.getElementById('modalTitle').innerText = button.getAttribute('data-bs-title');
        document.getElementById('modalLoc').innerText = button.getAttribute('data-bs-loc');
        document.getElementById('modalDate').innerText = 'วันที่พบ: ' + button.getAttribute('data-bs-date');
        document.getElementById('modalDesc').innerText = button.getAttribute('data-bs-desc') && button.getAttribute('data-bs-desc') !== '-' ? button.getAttribute('data-bs-desc') : 'ไม่มีรายละเอียดเพิ่มเติม';
        document.getElementById('modalCat').innerText = button.getAttribute('data-bs-cat');

        const badgeDiv = document.getElementById('modalStatusBadge');
        if(status === 'pending') {
            badgeDiv.innerHTML = '<span class="badge bg-warning text-dark shadow"><i class="fas fa-clock me-1"></i> รอรับคืน</span>';
            document.getElementById('modalReturnDateSection').classList.add('d-none');
            document.getElementById('contactInfo').classList.remove('d-none');
        } else {
            badgeDiv.innerHTML = '<span class="badge bg-success shadow"><i class="fas fa-check-circle me-1"></i> คืนแล้ว</span>';
            document.getElementById('modalReturnDateSection').classList.remove('d-none');
            document.getElementById('modalReturnDate').innerText = returnDate;
            document.getElementById('contactInfo').classList.add('d-none');
        }
    });

    function zoomImage(imgSrc) {
    if (!imgSrc) return; 
    
    const zoomModalEl = document.getElementById('imageZoomModal');
    const zoomedImg = document.getElementById('zoomedImg');
    zoomedImg.src = imgSrc;
    const zoomModal = bootstrap.Modal.getOrCreateInstance(zoomModalEl);
    zoomModal.show();
}
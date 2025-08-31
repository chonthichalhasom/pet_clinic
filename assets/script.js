// assets/script.js
// SweetAlert based helpers + small UI helpers

function confirmLogout(e) {
  e.preventDefault();
  Swal.fire({
    title: 'ออกจากระบบ?',
    text: 'คุณต้องการออกจากระบบหรือไม่',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'ออกจากระบบ',
    cancelButtonText: 'ยกเลิก',
    confirmButtonColor: '#1fb5a7'
  }).then((r) => {
    if (r.isConfirmed) {
      window.location.href = 'logout.php';
    }
  });
}

function confirmDelete(url, title = 'ลบข้อมูล', text = 'ข้อมูลจะถูกลบและไม่สามารถกู้คืนได้') {
  Swal.fire({
    title: title,
    text: text,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'ใช่, ลบเลย',
    cancelButtonText: 'ยกเลิก',
    confirmButtonColor: '#ff5c6a'
  }).then((r) => {
    if (r.isConfirmed) {
      window.location.href = url;
    }
  });
}

// show success/info toast
function showToast(msg, icon = 'success') {
  Swal.fire({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1600,
    icon: icon,
    title: msg
  });
}

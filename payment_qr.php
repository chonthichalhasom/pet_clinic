<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

// รับ receipt_id จาก GET
$receipt_id = $_GET['id'] ?? null;
if (!$receipt_id) {
    setFlash("ไม่พบเลขใบเสร็จ", 'error');
    header('Location: index.php');
    exit;
}

// ดึงข้อมูลใบเสร็จ + นัดหมาย + สัตว์เลี้ยง + เจ้าของ
$stmt = $pdo->prepare("
    SELECT r.*, a.date AS app_date, a.time AS app_time, a.reason, s.name AS staff_name, 
           p.name AS pet_name, p.species, p.breed, o.name AS owner_name
    FROM receipts r
    JOIN appointments a ON r.appointment_id = a.appointment_id
    JOIN pets p ON a.pet_id = p.pet_id
    JOIN owners o ON p.owner_id = o.owner_id
    JOIN staff s ON a.staff_id = s.staff_id
    WHERE r.receipt_id = ?
    LIMIT 1
");
$stmt->execute([$receipt_id]);
$receipt = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$receipt) {
    setFlash("ไม่พบข้อมูลใบเสร็จนี้", 'error');
    header('Location: index.php');
    exit;
}

include "templates/navbar.php";
?>

<!-- โหลด SweetAlert2 จาก CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="payment-card text-center">
    <h2>ชำระเงินผ่าน QR</h2>
    <p>ยอดรวม: <strong><?= isset($receipt['total_amount']) ? number_format($receipt['total_amount'],2) : '0.00' ?> บาท</strong></p>
    
    <img src="assets/payment.jpeg" alt="QR Payment">

    <div class="mt-4">
        <button id="paidBtn" class="btn btn-success">
            ชำระเงินเรียบร้อย
        </button>
        <a href="index.php" class="btn btn-danger">กลับสู่หน้าหลัก</a>
    </div>
</div>

<script>
document.getElementById('paidBtn').addEventListener('click', function(){
    Swal.fire({
        icon: 'success',
        title: 'ชำระเงินเรียบร้อย',
        showConfirmButton: true,
        confirmButtonText: 'ตกลง',
        timer: 2000
    }).then(() => {
        // redirect ไปหน้า receipts_view.php
        window.location.href = 'receipts_view.php?id=<?= $receipt['receipt_id'] ?>';
    });
});
</script>

<?php include "templates/footer.php"; ?>

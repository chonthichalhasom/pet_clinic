<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

// รับ receipt_id จาก GET
$receipt_id = $_GET['id'] ?? null;
if (!$receipt_id) {
    header('Location: appointments_manage.php');
    exit;
}

// ดึงข้อมูลใบเสร็จ + นัดหมาย + สัตว์เลี้ยง + เจ้าของ + หมอ
$stmt = $pdo->prepare("
    SELECT r.*, a.date AS app_date, a.reason, s.name AS staff_name, 
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
$receipt = $stmt->fetch();

if (!$receipt) {
    setFlash("ไม่พบข้อมูลใบเสร็จนี้", 'error');
    header('Location: appointments_manage.php');
    exit;
}

include "templates/navbar.php";
?>

<div class="container">
    <div class="receipt-card card">
        <h2>ใบเสร็จการชำระเงิน</h2>
        <p>เลขที่ใบเสร็จ: <strong><?= htmlspecialchars($receipt['receipt_id']) ?></strong></p>
        <p>วันที่: <strong><?= date('d/m/Y', strtotime($receipt['date'])) ?></strong></p>

        <h3>ข้อมูลการนัดหมาย</h3>
        <p>เจ้าของ: <strong><?= htmlspecialchars($receipt['owner_name']) ?></strong></p>
        <p>สัตว์เลี้ยง: <strong><?= htmlspecialchars($receipt['pet_name']) ?></strong></p>
        <p>ชนิด: <strong><?= htmlspecialchars($receipt['species']) ?></strong></p>
        <p>พันธุ์: <strong><?= htmlspecialchars($receipt['breed']) ?></strong></p>
        <p>หมอ: <strong><?= htmlspecialchars($receipt['staff_name']) ?></strong></p>
        <p>วันนัด: <strong><?= date('d/m/Y', strtotime($receipt['app_date'])) ?></strong></p>
        <p>อาการ/เหตุผล: <strong><?= htmlspecialchars($receipt['reason']) ?></strong></p>

        <h3>การชำระเงิน</h3>
        <p>วิธีชำระ: <strong><?= htmlspecialchars($receipt['payment_method']) ?></strong></p>
        <p>ยอดรวม: <strong><?= number_format($receipt['total_amount'],2) ?> บาท</strong></p>

        <a href="appointments_manage.php" class="btn btn-secondary" style="margin-top:15px;">กลับไปหน้าจัดการนัดหมาย</a>
    </div>
</div>

<?php include "templates/footer.php"; ?>

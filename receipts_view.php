<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$receipt_id = $_GET['id'] ?? null;
if (!$receipt_id) {
    setFlash("ไม่พบเลขใบเสร็จ", 'error');
    header('Location: index.php');
    exit;
}

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

$payment_methods_th = [
    'cash' => 'เงินสด',
    'transfer' => 'โอนผ่าน QR',
    'credit' => 'บัตรเครดิต'
];
$payment_method_th = $payment_methods_th[$receipt['payment_method']] ?? $receipt['payment_method'];

include "templates/navbar.php";
?>

<div class="container mt-5 mb-5" style="max-width: 700px;">
    <div class="receipt-card card p-4 shadow-sm">
        <h2 class="text-center mb-4">ใบเสร็จการชำระเงิน</h2>
        
        <p>เลขที่ใบเสร็จ: <strong><?= htmlspecialchars($receipt['receipt_id'] ?? '') ?></strong></p>
        <p>วันที่ออกใบเสร็จ: <strong><?= isset($receipt['date']) ? date('d/m/Y', strtotime($receipt['date'])) : '' ?></strong></p>

        <h4 class="mt-4 text-info">ข้อมูลการนัดหมาย</h4>
        <p>เจ้าของ: <strong><?= htmlspecialchars($receipt['owner_name'] ?? '') ?></strong></p>
        <p>สัตว์เลี้ยง: <strong><?= htmlspecialchars($receipt['pet_name'] ?? '') ?></strong></p>
        <p>ชนิด: <strong><?= htmlspecialchars($receipt['species'] ?? '') ?></strong></p>
        <p>พันธุ์: <strong><?= htmlspecialchars($receipt['breed'] ?? '') ?></strong></p>
        <p>หมอ: <strong><?= htmlspecialchars($receipt['staff_name'] ?? '') ?></strong></p>
        <p>วันนัด: <strong>
            <?= isset($receipt['app_date']) ? date('d/m/Y', strtotime($receipt['app_date'])) : '' ?> 
            <?= isset($receipt['app_time']) ? substr($receipt['app_time'],0,5) : '' ?>
        </strong></p>
        <p>อาการ/เหตุผล: <strong><?= htmlspecialchars($receipt['reason'] ?? '') ?></strong></p>

        <h4 class="mt-4 text-info">การชำระเงิน</h4>
        <p>วิธีชำระ: <strong><?= htmlspecialchars($payment_method_th) ?></strong></p>
        <p>ยอดรวม: <strong><?= isset($receipt['total_amount']) ? number_format($receipt['total_amount'],2) : '0.00' ?> บาท</strong></p>

        <div class="text-center mt-4">
            <button class="btn btn-success" id="btn-paid">ชำระเงินเรียบร้อยแล้ว</button>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="paidModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>ชำระเงินเรียบร้อยแล้ว 🎉</p>
        <button id="goIndex" class="btn btn-primary">กลับสู่หน้าหลัก</button>
    </div>
</div>

<style>
body { font-family: 'Prompt', sans-serif; background-color: #f5f5f5; }
.receipt-card { border-radius: 12px; background-color: #fff; }
h2, h4 { font-weight: 600; }
.modal {
    display: none; 
    position: fixed; 
    z-index: 1000; 
    left: 0; top: 0;
    width: 100%; height: 100%;
    overflow: auto; 
    background-color: rgba(0,0,0,0.5);
}
.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}
.modal-content p { font-size: 18px; font-weight: 600; margin-bottom: 20px; }
.modal-content .btn { width: 150px; }
.close { position: absolute; right: 15px; top: 10px; font-size: 24px; cursor: pointer; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById("paidModal");
    const btn = document.getElementById("btn-paid");
    const span = document.querySelector(".close");
    const goIndex = document.getElementById("goIndex");

    btn.onclick = function() { modal.style.display = "block"; }
    span.onclick = function() { modal.style.display = "none"; }
    window.onclick = function(event) { if(event.target==modal){ modal.style.display="none"; } }
    goIndex.onclick = function() { window.location.href="index.php"; }
});
</script>

<?php include "templates/footer.php"; ?>

<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM receipts WHERE receipt_id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch();
if (!$r) { setFlash('ไม่พบข้อมูล', 'error'); header('Location: receipts_manage.php'); exit; }

$appointments = $pdo->query("SELECT appointment_id,date FROM appointments ORDER BY appointment_id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = (int)$_POST['appointment_id'];
    $date = $_POST['date'];
    $payment_method = $_POST['payment_method'];
    $total_amount = (float)$_POST['total_amount'];
    $stmt = $pdo->prepare("UPDATE receipts SET appointment_id=?, date=?, payment_method=?, total_amount=? WHERE receipt_id=?");
    $stmt->execute([$appointment_id, $date, $payment_method, $total_amount, $id]);
    setFlash('แก้ไขใบเสร็จสำเร็จ', 'success');
    header('Location: receipts_manage.php'); exit;
}

include "templates/navbar.php";
?>
<div class="card">
  <h2>แก้ไขใบเสร็จ</h2>
  <form method="post">
    <label>นัดหมาย</label>
    <select name="appointment_id" required>
      <?php foreach($appointments as $a): ?><option value="<?= $a['appointment_id'] ?>" <?= $a['appointment_id']==$r['appointment_id'] ? 'selected':'' ?>>#<?= $a['appointment_id'] ?> (<?= $a['date'] ?>)</option><?php endforeach; ?>
    </select>
    <label>วันที่</label><input type="date" name="date" value="<?= $r['date'] ?>" required>
    <label>วิธีชำระ</label><input type="text" name="payment_method" value="<?= htmlspecialchars($r['payment_method']) ?>" required>
    <label>ยอดรวม (บาท)</label><input type="number" name="total_amount" step="0.01" value="<?= $r['total_amount'] ?>" required>
    <button class="btn btn-primary" type="submit" style="margin-top:10px;">บันทึก</button>
  </form>
</div>
<?php include "templates/footer.php"; ?>

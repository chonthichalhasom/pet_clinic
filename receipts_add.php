<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$appointments = $pdo->query("SELECT appointment_id,date FROM appointments ORDER BY appointment_id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = (int)$_POST['appointment_id'];
    $date = $_POST['date'];
    $payment_method = $_POST['payment_method'];
    $total_amount = (float)$_POST['total_amount'];

    $stmt = $pdo->prepare("INSERT INTO receipts (appointment_id, date, payment_method, total_amount) VALUES (?,?,?,?)");
    $stmt->execute([$appointment_id, $date, $payment_method, $total_amount]);
    setFlash('เพิ่มใบเสร็จสำเร็จ', 'success');
    header('Location: receipts_manage.php'); exit;
}

include "templates/navbar.php";
?>
<div class="card">
  <h2>สร้างใบเสร็จ</h2>
  <form method="post">
    <label>นัดหมาย</label>
    <select name="appointment_id" required>
      <option value="">-- เลือกนัดหมาย --</option>
      <?php foreach($appointments as $a): ?><option value="<?= $a['appointment_id'] ?>">#<?= $a['appointment_id'] ?> (<?= $a['date'] ?>)</option><?php endforeach; ?>
    </select>
    <label>วันที่</label><input type="date" name="date" required>
    <label>วิธีชำระ</label><input type="text" name="payment_method" required>
    <label>ยอดรวม (บาท)</label><input type="number" name="total_amount" step="0.01" required>
    <button class="btn btn-primary" type="submit" style="margin-top:10px;">บันทึก</button>
  </form>
</div>
<?php include "templates/footer.php"; ?>

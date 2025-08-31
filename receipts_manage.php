<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();
include "templates/navbar.php";

$sql = "SELECT r.*, a.date AS app_date, p.name AS pet_name
        FROM receipts r
        LEFT JOIN appointments a ON r.appointment_id = a.appointment_id
        LEFT JOIN pets p ON a.pet_id = p.pet_id
        ORDER BY r.receipt_id DESC";
$receipts = $pdo->query($sql)->fetchAll();
?>
<div class="card">
  <div class="header-row">
    <h2>ใบเสร็จ</h2>
    <a class="btn btn-primary" href="receipts_add.php">+ สร้างใบเสร็จ</a>
  </div>
  <table class="table">
    <thead><tr><th>ID</th><th>วันที่</th><th>นัดหมาย</th><th>สัตว์</th><th>วิธีชำระ</th><th>รวม</th><th>จัดการ</th></tr></thead>
    <tbody>
      <?php foreach($receipts as $r): ?>
      <tr>
        <td><?= $r['receipt_id'] ?></td>
        <td><?= $r['date'] ?></td>
        <td>#<?= $r['appointment_id'] ?> (<?= $r['app_date'] ?>)</td>
        <td><?= htmlspecialchars($r['pet_name']) ?></td>
        <td><?= htmlspecialchars($r['payment_method']) ?></td>
        <td><?= number_format($r['total_amount'],2) ?></td>
        <td>
          <a class="btn btn-warning" href="receipts_edit.php?id=<?= $r['receipt_id'] ?>">แก้ไข</a>
          <a class="btn btn-danger" href="#" onclick="confirmDelete('receipts_del.php?id=<?= $r['receipt_id'] ?>')">ลบ</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include "templates/footer.php"; ?>

<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();
include "templates/navbar.php";

$sql = "SELECT t.*, a.date AS app_date, p.name AS pet_name
        FROM treatments t
        LEFT JOIN appointments a ON t.appointment_id = a.appointment_id
        LEFT JOIN pets p ON a.pet_id = p.pet_id
        ORDER BY t.treatment_id DESC";
$list = $pdo->query($sql)->fetchAll();
?>
<div class="card">
  <div class="header-row">
    <h2>การรักษา</h2>
    <a class="btn btn-primary" href="treatments_add.php">+ เพิ่มการรักษา</a>
  </div>
  <table class="table">
    <thead><tr><th>ID</th><th>นัดหมาย</th><th>สัตว์</th><th>คำอธิบาย</th><th>ค่าใช้จ่าย</th><th>จัดการ</th></tr></thead>
    <tbody>
      <?php foreach($list as $t): ?>
      <tr>
        <td><?= $t['treatment_id'] ?></td>
        <td>#<?= $t['appointment_id'] ?> (<?= $t['app_date'] ?>)</td>
        <td><?= htmlspecialchars($t['pet_name']) ?></td>
        <td><?= htmlspecialchars($t['description']) ?></td>
        <td><?= number_format($t['cost'],2) ?></td>
        <td>
          <a class="btn btn-warning" href="treatments_edit.php?id=<?= $t['treatment_id'] ?>">แก้ไข</a>
          <a class="btn btn-danger" href="#" onclick="confirmDelete('treatments_del.php?id=<?= $t['treatment_id'] ?>')">ลบ</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include "templates/footer.php"; ?>

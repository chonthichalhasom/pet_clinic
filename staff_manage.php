<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();
include "templates/navbar.php";

$staffs = $pdo->query("SELECT * FROM staff ORDER BY staff_id DESC")->fetchAll();
?>
<div class="card">
  <div class="header-row">
    <h2>พนักงาน / สัตวแพทย์</h2>
    <a class="btn btn-primary" href="staff_add.php">+ เพิ่มพนักงาน</a>
  </div>

  <table class="table">
    <thead><tr><th>ID</th><th>ชื่อ</th><th>ตำแหน่ง</th><th>โทรศัพท์</th><th>อีเมล</th><th>จัดการ</th></tr></thead>
    <tbody>
      <?php foreach($staffs as $s): ?>
      <tr>
        <td><?= $s['staff_id'] ?></td>
        <td><?= htmlspecialchars($s['name']) ?></td>
        <td><?= htmlspecialchars($s['role']) ?></td>
        <td><?= htmlspecialchars($s['phone']) ?></td>
        <td><?= htmlspecialchars($s['email']) ?></td>
        <td>
          <a class="btn btn-warning" href="staff_edit.php?id=<?= $s['staff_id'] ?>">แก้ไข</a>
          <a class="btn btn-danger" href="#" onclick="confirmDelete('staff_del.php?id=<?= $s['staff_id'] ?>')">ลบ</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include "templates/footer.php"; ?>

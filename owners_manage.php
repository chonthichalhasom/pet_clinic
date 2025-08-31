<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();
include "templates/navbar.php";

$stmt = $pdo->query("SELECT * FROM owners ORDER BY owner_id DESC");
$owners = $stmt->fetchAll();
?>
<div class="card">
  <div class="header-row">
    <h2>จัดการเจ้าของ</h2>
    <a class="btn btn-primary" href="owners_add.php">+ เพิ่มเจ้าของ</a>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>ชื่อ</th>
        <th>โทรศัพท์</th>
        <th>อีเมล</th>
        <th>ที่อยู่</th>
        <th>จัดการ</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($owners as $o): ?>
      <tr>
        <td><?= $o['owner_id'] ?></td>
        <td><?= htmlspecialchars($o['name']) ?></td>
        <td><?= htmlspecialchars($o['phone']) ?></td>
        <td><?= htmlspecialchars($o['email']) ?></td>
        <td><?= htmlspecialchars($o['address']) ?></td>
        <td>
          <a class="btn btn-warning" href="owners_edit.php?id=<?= $o['owner_id'] ?>">แก้ไข</a>
          <a class="btn btn-danger" href="#" onclick="confirmDelete('owners_del.php?id=<?= $o['owner_id'] ?>')">ลบ</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include "templates/footer.php"; ?>

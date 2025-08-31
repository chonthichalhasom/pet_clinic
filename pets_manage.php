<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();
include "templates/navbar.php";

$sql = "SELECT p.*, o.name AS owner_name FROM pets p LEFT JOIN owners o ON p.owner_id = o.owner_id ORDER BY p.pet_id DESC";
$pets = $pdo->query($sql)->fetchAll();
?>
<div class="card">
  <div class="header-row">
    <h2>สัตว์เลี้ยง</h2>
    <a class="btn btn-primary" href="pets_add.php">+ เพิ่มสัตว์เลี้ยง</a>
  </div>

  <table class="table">
    <thead><tr><th>ID</th><th>ชื่อ</th><th>ชนิด</th><th>สายพันธุ์</th><th>อายุ</th><th>เจ้าของ</th><th>จัดการ</th></tr></thead>
    <tbody>
      <?php foreach($pets as $p): ?>
      <tr>
        <td><?= $p['pet_id'] ?></td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><?= htmlspecialchars($p['species']) ?></td>
        <td><?= htmlspecialchars($p['breed']) ?></td>
        <td><?= $p['age'] ?></td>
        <td><?= htmlspecialchars($p['owner_name']) ?></td>
        <td>
          <a class="btn btn-warning" href="pets_edit.php?id=<?= $p['pet_id'] ?>">แก้ไข</a>
          <a class="btn btn-danger" href="#" onclick="confirmDelete('pets_del.php?id=<?= $p['pet_id'] ?>')">ลบ</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include "templates/footer.php"; ?>

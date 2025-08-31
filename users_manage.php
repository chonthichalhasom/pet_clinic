<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();
include "templates/navbar.php";

$users = $pdo->query("SELECT user_id, username, email, phone FROM users ORDER BY user_id DESC")->fetchAll();
?>

<div class="card">
  <div class="header-row">
    <h2>ผู้ใช้ระบบ</h2>
    <a class="btn btn-primary" href="users_add.php">+ เพิ่มผู้ใช้</a>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>ชื่อผู้ใช้</th>
        <th>อีเมล</th>
        <th>โทรศัพท์</th>
        <th>จัดการ</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($users as $u): ?>
        <tr>
          <td><?= $u['user_id'] ?></td>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['phone']) ?></td>
          <td>
            <a class="btn btn-warning" href="users_edit.php?id=<?= $u['user_id'] ?>">แก้ไข</a>
            <a class="btn btn-danger" href="#" onclick="confirmDelete('users_del.php?id=<?= $u['user_id'] ?>')">ลบ</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include "templates/footer.php"; ?>

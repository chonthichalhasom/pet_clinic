<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$id]);
$u = $stmt->fetch();
if (!$u) { setFlash('ไม่พบข้อมูล', 'error'); header('Location: users_manage.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // ถ้าต้องการเปลี่ยนรหัสผ่าน ให้ hash ใหม่
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET username=?, password=?, email=?, phone=? WHERE user_id=?");
    $stmt->execute([$username, $hash, $email, $phone, $id]);

    setFlash('แก้ไขผู้ใช้สำเร็จ', 'success');
    header('Location: users_manage.php'); exit;
}

include "templates/navbar.php";
?>
<div class="card">
  <h2>แก้ไขผู้ใช้</h2>
  <form method="post">
    <label>ชื่อผู้ใช้</label><input type="text" name="username" value="<?= htmlspecialchars($u['username']) ?>" required>
    <label>รหัสผ่าน (ใส่ใหม่เพื่อเปลี่ยน)</label><input type="password" name="password" required>
    <label>อีเมล</label><input type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>">
    <label>โทรศัพท์</label><input type="text" name="phone" value="<?= htmlspecialchars($u['phone']) ?>">
    <button class="btn btn-primary" type="submit" style="margin-top:10px;">บันทึก</button>
  </form>
</div>
<?php include "templates/footer.php"; ?>

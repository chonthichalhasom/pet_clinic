<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM owners WHERE owner_id = ?");
$stmt->execute([$id]);
$o = $stmt->fetch();
if (!$o) {
    setFlash('ข้อมูลไม่พบ', 'error');
    header('Location: owners_manage.php'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE owners SET password=?, name=?, phone=?, email=?, address=? WHERE owner_id=?");
    $stmt->execute([$hash, $name, $phone, $email, $address, $id]);

    setFlash('แก้ไขเจ้าของสำเร็จ', 'success');
    header('Location: owners_manage.php'); exit;
}

include "templates/navbar.php";
?>
<div class="card">
  <h2>แก้ไขเจ้าของ</h2>
  <form method="post" class="form">
    <label>ชื่อ-สกุล</label>
    <input type="text" name="name" value="<?= htmlspecialchars($o['name']) ?>" required>

    <label>โทรศัพท์</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($o['phone']) ?>" required>

    <label>อีเมล</label>
    <input type="email" name="email" value="<?= htmlspecialchars($o['email']) ?>">

    <label>ที่อยู่</label>
    <textarea name="address"><?= htmlspecialchars($o['address']) ?></textarea>

    <label>รหัสผ่าน (ใส่ใหม่เพื่อเปลี่ยน)</label>
    <input type="password" name="password" required>

    <button class="btn btn-primary" type="submit" style="margin-top:10px;">บันทึกการเปลี่ยนแปลง</button>
  </form>
</div>
<?php include "templates/footer.php"; ?>

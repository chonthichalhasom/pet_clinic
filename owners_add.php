<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO owners (password, name, phone, email, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$hash, $name, $phone, $email, $address]);

    setFlash('เพิ่มเจ้าของสำเร็จ', 'success');
    header('Location: owners_manage.php'); exit;
}

include "templates/navbar.php";
?>
<div class="card">
  <h2>เพิ่มเจ้าของ</h2>
  <form method="post" class="form">
    <label>ชื่อ-สกุล</label>
    <input type="text" name="name" required>

    <label>โทรศัพท์</label>
    <input type="text" name="phone" required>

    <label>อีเมล</label>
    <input type="email" name="email">

    <label>ที่อยู่</label>
    <textarea name="address"></textarea>

    <label>รหัสผ่าน</label>
    <input type="password" name="password" required>

    <button class="btn btn-primary" type="submit" style="margin-top:10px;">บันทึก</button>
  </form>
</div>
<?php include "templates/footer.php"; ?>

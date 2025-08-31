<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, phone) VALUES (?,?,?,?)");
    $stmt->execute([$username, $hash, $email, $phone]);

    setFlash('เพิ่มผู้ใช้สำเร็จ', 'success');
    header('Location: users_manage.php'); 
    exit;
}

include "templates/navbar.php";
?>

<div class="card">
  <h2>เพิ่มผู้ใช้ระบบ</h2>
  <form method="post" class="form">
    <label>ชื่อผู้ใช้</label>
    <input type="text" name="username" required>
    
    <label>รหัสผ่าน</label>
    <input type="password" name="password" required>
    
    <label>อีเมล</label>
    <input type="email" name="email">
    
    <label>โทรศัพท์</label>
    <input type="text" name="phone">
    
    <button class="btn btn-primary" type="submit" style="margin-top:10px;">บันทึก</button>
  </form>
</div>

<?php include "templates/footer.php"; ?>

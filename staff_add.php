<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name']; $role = $_POST['role']; $phone = $_POST['phone']; $email = $_POST['email'];
    $stmt = $pdo->prepare("INSERT INTO staff (name,role,phone,email) VALUES (?,?,?,?)");
    $stmt->execute([$name,$role,$phone,$email]);
    setFlash('เพิ่มพนักงานสำเร็จ', 'success');
    header('Location: staff_manage.php'); exit;
}

include "templates/navbar.php";
?>
<div class="card">
  <h2>เพิ่มพนักงาน</h2>
  <form method="post">
    <label>ชื่อ</label><input type="text" name="name" required>
    <label>ตำแหน่ง</label><input type="text" name="role" required>
    <label>โทรศัพท์</label><input type="text" name="phone">
    <label>อีเมล</label><input type="email" name="email">
    <button class="btn btn-primary" type="submit" style="margin-top:10px;">บันทึก</button>
  </form>
</div>
<?php include "templates/footer.php"; ?>

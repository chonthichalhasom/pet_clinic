<?php
// register.php
include "app/config.php";
include "app/helpers.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($password !== $confirm_password) {
        $error = 'รหัสผ่านไม่ตรงกัน';
    } else {
        // ตรวจสอบว่าชื่อผู้ใช้ซ้ำหรือไม่
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'มีชื่อผู้ใช้นี้แล้ว';
        } else {
            // บันทึก user ใหม่
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $email, $phone]);

            echo "<script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        title: 'สมัครสมาชิกสำเร็จ!',
                        text: 'คุณสามารถเข้าสู่ระบบได้แล้ว',
                        icon: 'success',
                        confirmButtonText: 'ไปยังหน้าล็อคอิน'
                    }).then(() => window.location='login.php');
                });
            </script>";
            exit;
        }
    }
}

include "templates/header.php";
?>

<div class="login-container">
  <div class="login-card">
    <h2 class="login-title">📝 สมัครสมาชิก</h2>
    <p class="login-subtitle">สร้างบัญชีสำหรับระบบคลินิกสัตว์เลี้ยง</p>

    <?php if ($error): ?>
      <script>
        document.addEventListener('DOMContentLoaded', ()=> {
          Swal.fire('สมัครสมาชิกล้มเหลว', <?= json_encode($error) ?>, 'error');
        });
      </script>
    <?php endif; ?>

    <form method="post" class="login-form">
      <label>ชื่อผู้ใช้</label>
      <input type="text" name="username" required placeholder="กรอกชื่อผู้ใช้">

      <label>รหัสผ่าน</label>
      <input type="password" name="password" required placeholder="••••••••">

      <label>ยืนยันรหัสผ่าน</label>
      <input type="password" name="confirm_password" required placeholder="••••••••">

      <label>อีเมล</label>
      <input type="email" name="email" required placeholder="you@example.com">

      <label>เบอร์โทร</label>
      <input type="text" name="phone" required placeholder="089-xxx-xxxx">

      <button class="btn btn-primary" type="submit">สมัครสมาชิก</button>
    </form>

    <p class="login-register">
      มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a>
    </p>
  </div>
</div>

<?php include "templates/footer.php"; ?>

<?php
// login.php
include "app/config.php";
include "app/helpers.php";

// ถ้า login อยู่แล้ว ไปหน้า index
if (!empty($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // ตรวจสอบ password hash
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            echo "<script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        title: 'เข้าสู่ระบบสำเร็จ!',
                        text: 'ยินดีต้อนรับคุณ {$user['username']}',
                        icon: 'success',
                        confirmButtonText: 'ไปยังหน้าหลัก'
                    }).then(() => window.location='index.php');
                });
            </script>";
        } else {
            $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
        }
    } else {
        $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
    }
}

include "templates/header.php";
?>

<div class="login-container">
  <div class="login-card">
    <h2 class="login-title">🐾 เข้าสู่ระบบ</h2>
    <p class="login-subtitle">ระบบจัดการคลินิกสัตว์เลี้ยง</p>

    <?php if ($error): ?>
      <script>
        document.addEventListener('DOMContentLoaded', ()=> {
          Swal.fire('เข้าสู่ระบบล้มเหลว', <?= json_encode($error) ?>, 'error');
        });
      </script>
    <?php endif; ?>

    <form method="post" class="login-form">
      <label>ชื่อผู้ใช้</label>
      <input type="text" name="username" placeholder="กรอกชื่อผู้ใช้" required>

      <label>รหัสผ่าน</label>
      <input type="password" name="password" placeholder="••••••••" required>

      <button class="btn btn-primary" type="submit">เข้าสู่ระบบ</button>
    </form>

    <p class="login-register">
      ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a>
    </p>
  </div>
</div>

<?php include "templates/footer.php"; ?>

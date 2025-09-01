<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM staff WHERE staff_id = ?");
$stmt->execute([$id]);
$s = $stmt->fetch();

if (!$s) { 
    setFlash('ไม่พบข้อมูล', 'error'); 
    header('Location: staff_manage.php'); 
    exit; 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name']; 
    $role = $_POST['role']; 
    $phone = $_POST['phone']; 
    $email = $_POST['email'];

    $stmt = $pdo->prepare("UPDATE staff SET name=?, role=?, phone=?, email=? WHERE staff_id=?");
    $stmt->execute([$name,$role,$phone,$email,$id]);

    setFlash('แก้ไขพนักงานสำเร็จ', 'success');
    header('Location: staff_manage.php'); 
    exit;
}

include "templates/navbar.php";
?>

<div class="card">
  <h2>แก้ไขพนักงาน</h2>
  <form method="post">
    <label>ชื่อ</label>
    <input type="text" name="name" value="<?= htmlspecialchars($s['name']) ?>" required>

    <label>ตำแหน่ง</label>
    <select name="role" required>
      <?php
      $roles = [
          'สัตวแพทย์ทั่วไป',
          'สัตวแพทย์เฉพาะทาง',
          'ผู้ช่วยสัตวแพทย์ / พยาบาลสัตว์',
          'เภสัชกรสัตวแพทย์'
      ];
      foreach ($roles as $role) {
          $selected = ($s['role'] === $role) ? 'selected' : '';
          echo "<option value=\"" . htmlspecialchars($role) . "\" $selected>" . htmlspecialchars($role) . "</option>";
      }
      ?>
    </select>

    <label>โทรศัพท์</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($s['phone']) ?>">

    <label>อีเมล</label>
    <input type="email" name="email" value="<?= htmlspecialchars($s['email']) ?>">

    <button class="btn btn-primary" type="submit" style="margin-top:10px;">บันทึก</button>
  </form>
</div>

<?php include "templates/footer.php"; ?>

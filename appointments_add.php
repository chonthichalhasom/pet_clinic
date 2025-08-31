<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

// ดึงรายชื่อหมอ
$staffs = $pdo->query("SELECT * FROM staff ORDER BY name ASC")->fetchAll();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner_name = trim($_POST['owner_name'] ?? '');
    $owner_phone = trim($_POST['owner_phone'] ?? '');
    $owner_email = trim($_POST['owner_email'] ?? '');
    $owner_address = trim($_POST['owner_address'] ?? '');
    
    $pet_name = trim($_POST['pet_name'] ?? '');
    $species = trim($_POST['species'] ?? '');
    $breed = trim($_POST['breed'] ?? '');
    $age = (int)($_POST['age'] ?? 0);

    $staff_id = $_POST['staff_id'] ?? '';
    $date = $_POST['date'] ?? '';
    $reason = trim($_POST['reason'] ?? '');

    if($owner_name && $pet_name && $species && $staff_id && $date){
        // เพิ่มเจ้าของสัตว์
        $stmt = $pdo->prepare("INSERT INTO owners (name, phone, email, address, password) VALUES (?,?,?,?,?)");
        $stmt->execute([$owner_name, $owner_phone, $owner_email, $owner_address, '']);
        $owner_id = $pdo->lastInsertId();

        // เพิ่มสัตว์เลี้ยง
        $stmt = $pdo->prepare("INSERT INTO pets (name, species, breed, age, owner_id) VALUES (?,?,?,?,?)");
        $stmt->execute([$pet_name, $species, $breed, $age, $owner_id]);
        $pet_id = $pdo->lastInsertId();

        // เพิ่มนัดหมาย
        $stmt = $pdo->prepare("INSERT INTO appointments (pet_id, staff_id, date, reason) VALUES (?,?,?,?)");
        $stmt->execute([$pet_id, $staff_id, $date, $reason]);
        $appointment_id = $pdo->lastInsertId();

        // กำหนดค่า cost สมมติ 500 บาท
        $cost = 500;

        // เพิ่มใบเสร็จ
        $stmt = $pdo->prepare("INSERT INTO receipts (appointment_id, date, payment_method, total_amount) VALUES (?,?,?,?)");
        $stmt->execute([$appointment_id, date('Y-m-d'), 'สด', $cost]);
        $receipt_id = $pdo->lastInsertId();

        // ไปหน้าใบเสร็จ
        header("Location: receipts_view.php?id=$receipt_id");
        exit;
    } else {
        $error = "กรุณากรอกข้อมูลให้ครบถ้วน";
    }
}

include "templates/navbar.php";
?>

<div class="card">
  <h2>จองคิวหมอ</h2>
  <?php if($error): ?>
    <script>
      document.addEventListener('DOMContentLoaded', ()=> Swal.fire('เกิดข้อผิดพลาด', <?= json_encode($error) ?>, 'error'));
    </script>
  <?php endif; ?>

  <form method="post" class="form">
    <label>ชื่อเจ้าของ</label>
    <input type="text" name="owner_name" required>

    <label>โทรศัพท์เจ้าของ</label>
    <input type="text" name="owner_phone">

    <label>อีเมลเจ้าของ</label>
    <input type="email" name="owner_email">

    <label>ที่อยู่เจ้าของ</label>
    <textarea name="owner_address" rows="2"></textarea>

    <label>ชื่อสัตว์เลี้ยง</label>
    <input type="text" name="pet_name" required>

    <label>ชนิดสัตว์</label>
    <input type="text" name="species" required>

    <label>พันธุ์</label>
    <input type="text" name="breed">

    <label>อายุสัตว์เลี้ยง (ปี)</label>
    <input type="number" name="age" min="0">

    <label>เลือกหมอ</label>
    <select name="staff_id" required>
      <option value="">-- เลือกหมอ --</option>
      <?php foreach($staffs as $s): ?>
        <option value="<?= $s['staff_id'] ?>"><?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['role']) ?>)</option>
      <?php endforeach; ?>
    </select>

    <label>วัน/เวลา</label>
    <input type="date" name="date" required>

    <label>อาการ/เหตุผลการมาพบ</label>
    <input type="text" name="reason" required>

    <button type="submit" class="btn btn-primary">จองคิว</button>
  </form>
</div>

<?php include "templates/footer.php"; ?>

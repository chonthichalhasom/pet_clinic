<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT a.*, p.name as pet_name, p.species, p.breed, p.age, p.owner_id, o.name as owner_name, o.phone, o.email, o.address
                       FROM appointments a
                       JOIN pets p ON a.pet_id = p.pet_id
                       JOIN owners o ON p.owner_id = o.owner_id
                       WHERE a.appointment_id=?");
$stmt->execute([$id]);
$a = $stmt->fetch();

if (!$a) { setFlash('ไม่พบข้อมูล', 'error'); header('Location: appointments_manage.php'); exit; }

// รายชื่อหมอ
$staffs = $pdo->query("SELECT staff_id,name FROM staff ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // เจ้าของ
    $owner_name = trim($_POST['owner_name']);
    $owner_phone = trim($_POST['owner_phone']);
    $owner_email = trim($_POST['owner_email']);
    $owner_address = trim($_POST['owner_address']);

    // สัตว์เลี้ยง
    $pet_name = trim($_POST['pet_name']);
    $species = trim($_POST['species']);
    $breed = trim($_POST['breed']);
    $age = (int)$_POST['age'];

    // นัดหมาย
    $staff_id = (int)$_POST['staff_id'];
    $date = $_POST['date'];
    $reason = trim($_POST['reason']);

    // อัปเดตเจ้าของ
    $stmt = $pdo->prepare("UPDATE owners SET name=?, phone=?, email=?, address=? WHERE owner_id=?");
    $stmt->execute([$owner_name, $owner_phone, $owner_email, $owner_address, $a['owner_id']]);

    // อัปเดตสัตว์เลี้ยง
    $stmt = $pdo->prepare("UPDATE pets SET name=?, species=?, breed=?, age=? WHERE pet_id=?");
    $stmt->execute([$pet_name, $species, $breed, $age, $a['pet_id']]);

    // อัปเดตนัดหมาย
    $stmt = $pdo->prepare("UPDATE appointments SET staff_id=?, date=?, reason=? WHERE appointment_id=?");
    $stmt->execute([$staff_id, $date, $reason, $id]);

    setFlash('แก้ไขนัดหมายสำเร็จ', 'success');
    header('Location: appointments_manage.php'); exit;
}

include "templates/navbar.php";
?>

<div class="card">
  <h2>แก้ไขนัดหมาย</h2>

  <form method="post" class="form">
    <h3>ข้อมูลเจ้าของ</h3>
    <label>ชื่อเจ้าของ</label>
    <input type="text" name="owner_name" value="<?= htmlspecialchars($a['owner_name']) ?>" required>
    <label>โทรศัพท์</label>
    <input type="text" name="owner_phone" value="<?= htmlspecialchars($a['phone']) ?>" required>
    <label>อีเมล</label>
    <input type="email" name="owner_email" value="<?= htmlspecialchars($a['email']) ?>" required>
    <label>ที่อยู่</label>
    <textarea name="owner_address" required><?= htmlspecialchars($a['address']) ?></textarea>

    <h3>ข้อมูลสัตว์เลี้ยง</h3>
    <label>ชื่อสัตว์เลี้ยง</label>
    <input type="text" name="pet_name" value="<?= htmlspecialchars($a['pet_name']) ?>" required>
    <label>ชนิดสัตว์</label>
    <input type="text" name="species" value="<?= htmlspecialchars($a['species']) ?>" required>
    <label>พันธุ์</label>
    <input type="text" name="breed" value="<?= htmlspecialchars($a['breed']) ?>">
    <label>อายุ</label>
    <input type="number" name="age" value="<?= htmlspecialchars($a['age']) ?>">

    <h3>นัดหมาย</h3>
    <label>เลือกหมอ</label>
    <select name="staff_id" required>
      <?php foreach($staffs as $s): ?>
        <option value="<?= $s['staff_id'] ?>" <?= $s['staff_id']==$a['staff_id']?'selected':'' ?>>
          <?= htmlspecialchars($s['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>วัน/เวลา</label>
    <input type="date" name="date" value="<?= htmlspecialchars($a['date']) ?>" required>
    <label>อาการ/หมายเหตุ</label>
    <textarea name="reason"><?= htmlspecialchars($a['reason']) ?></textarea>

    <button class="btn btn-primary" type="submit" style="margin-top:10px;">บันทึก</button>
  </form>
</div>

<?php include "templates/footer.php"; ?>

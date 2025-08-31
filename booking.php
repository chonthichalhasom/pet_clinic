<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

include "templates/header.php";
include "templates/navbar.php";

// ดึงหมอ
$staff = $pdo->query("SELECT * FROM staff ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner_name = trim($_POST['owner_name']);
    $pet_name = trim($_POST['pet_name']);
    $pet_species = trim($_POST['pet_species']);
    $pet_breed = trim($_POST['pet_breed']);
    $staff_id = $_POST['staff_id'];
    $date = $_POST['date'];
    $reason = trim($_POST['reason']);
    $cost = floatval($_POST['cost']);
    $payment_method = $_POST['payment_method'];

    // สร้างเจ้าของ
    $stmt = $pdo->prepare("INSERT INTO owners (name, phone, email, address, password) VALUES (?, '', '', '', '')");
    $stmt->execute([$owner_name]);
    $owner_id = $pdo->lastInsertId();

    // สร้างสัตว์เลี้ยง
    $stmt = $pdo->prepare("INSERT INTO pets (name, species, breed, age, owner_id) VALUES (?, ?, ?, 0, ?)");
    $stmt->execute([$pet_name, $pet_species, $pet_breed, $owner_id]);
    $pet_id = $pdo->lastInsertId();

    // สร้าง appointment
    $stmt = $pdo->prepare("INSERT INTO appointments (pet_id, staff_id, date, reason) VALUES (?, ?, ?, ?)");
    $stmt->execute([$pet_id, $staff_id, $date, $reason]);
    $appointment_id = $pdo->lastInsertId();

    // สร้าง treatment
    $stmt = $pdo->prepare("INSERT INTO treatments (appointment_id, description, cost) VALUES (?, ?, ?)");
    $stmt->execute([$appointment_id, $reason, $cost]);

    // สร้าง receipt
    $stmt = $pdo->prepare("INSERT INTO receipts (appointment_id, date, payment_method, total_amount) VALUES (?, ?, ?, ?)");
    $stmt->execute([$appointment_id, $date, $payment_method, $cost]);
    $receipt_id = $pdo->lastInsertId();

    echo "<script>
        document.addEventListener('DOMContentLoaded', ()=>{
            Swal.fire({
                title:'บันทึกสำเร็จ!',
                text:'ไปดูใบเสร็จ',
                icon:'success',
                confirmButtonText:'ตกลง'
            }).then(()=> window.location='receipts_view.php?id={$receipt_id}');
        });
    </script>";
}
?>

<div class="container">
  <h2>จองคิวหมอ</h2>
  <form method="post" class="form">
    <label>ชื่อเจ้าของ</label>
    <input type="text" name="owner_name" required placeholder="ชื่อเจ้าของ">

    <label>ชื่อสัตว์เลี้ยง</label>
    <input type="text" name="pet_name" required placeholder="ชื่อสัตว์เลี้ยง">

    <label>ประเภทสัตว์</label>
    <input type="text" name="pet_species" required placeholder="หมา/แมว/กระต่าย/อื่นๆ">

    <label>พันธุ์</label>
    <input type="text" name="pet_breed" placeholder="พันธุ์สัตว์">

    <label>หมอ</label>
    <select name="staff_id" required>
      <option value="">เลือกหมอ</option>
      <?php foreach($staff as $s): ?>
        <option value="<?= $s['staff_id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>วันที่และเวลา</label>
    <input type="datetime-local" name="date" required>

    <label>อาการ/เหตุผล</label>
    <input type="text" name="reason" required placeholder="เช่น ป่วยเป็นหวัด">

    <label>ราคา (บาท)</label>
    <input type="number" name="cost" step="0.01" required>

    <label>วิธีชำระเงิน</label>
    <select name="payment_method" required>
      <option value="เงินสด">เงินสด</option>
      <option value="โอน">โอน</option>
      <option value="บัตรเครดิต">บัตรเครดิต</option>
    </select>

    <button type="submit" class="btn btn-primary">บันทึกและออกใบเสร็จ</button>
  </form>
</div>

<?php include "templates/footer.php"; ?>

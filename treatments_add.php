<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$appointments = $pdo->query("SELECT appointment_id, date FROM appointments ORDER BY appointment_id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = (int)$_POST['appointment_id']; $description = $_POST['description']; $cost = (float)$_POST['cost'];
    $stmt = $pdo->prepare("INSERT INTO treatments (appointment_id, description, cost) VALUES (?,?,?)");
    $stmt->execute([$appointment_id, $description, $cost]);
    setFlash('เพิ่มการรักษาสำเร็จ', 'success');
    header('Location: treatments_manage.php'); exit;
}

include "templates/navbar.php";
?>
<div class="card">
  <h2>เพิ่มการรักษา</h2>
  <form method="post">
    <label>นัดหมาย</label>
    <select name="appointment_id" required>
      <option value="">-- เลือกนัดหมาย --</option>
      <?php foreach($appointments as $a): ?><option value="<?= $a['appointment_id'] ?>">#<?= $a['appointment_id'] ?> (<?= $a['date'] ?>)</option><?php endforeach; ?>
    </select>
    <label>คำอธิบาย</label><textarea name="description"></textarea>
    <label>ค่าใช้จ่าย (บาท)</label><input type="number" name="cost" step="0.01" required>
    <button class="btn btn-primary" type="submit" style="margin-top:10px;">บันทึก</button>
  </form>
</div>
<?php include "templates/footer.php"; ?>

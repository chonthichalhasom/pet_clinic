<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM treatments WHERE treatment_id = ?");
$stmt->execute([$id]);
$t = $stmt->fetch();
if (!$t) { setFlash('ไม่พบข้อมูล', 'error'); header('Location: treatments_manage.php'); exit; }

$appointments = $pdo->query("SELECT appointment_id,date FROM appointments ORDER BY appointment_id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = (int)$_POST['appointment_id']; $description = $_POST['description']; $cost = (float)$_POST['cost'];
    $stmt = $pdo->prepare("UPDATE treatments SET appointment_id=?, description=?, cost=? WHERE treatment_id=?");
    $stmt->execute([$appointment_id, $description, $cost, $id]);
    setFlash('แก้ไขการรักษาสำเร็จ', 'success');
    header('Location: treatments_manage.php'); exit;
}

include "templates/navbar.php";
?>
<div class="card">
  <h2>แก้ไขการรักษา</h2>
  <form method="post">
    <label>นัดหมาย</label>
    <select name="appointment_id" required>
      <?php foreach($appointments as $a): ?><option value="<?= $a['appointment_id'] ?>" <?= $a['appointment_id']==$t['appointment_id'] ? 'selected' : '' ?>>#<?= $a['appointment_id'] ?> (<?= $a['date'] ?>)</option><?php endforeach; ?>
    </select>
    <label>คำอธิบาย</label><textarea name="description"><?= htmlspecialchars($t['description']) ?></textarea>
    <label>ค่าใช้จ่าย (บาท)</label><input type="number" name="cost" step="0.01" value="<?= $t['cost'] ?>" required>
    <button class="btn btn-primary" type="submit" style="margin-top:10px;">บันทึก</button>
  </form>
</div>
<?php include "templates/footer.php"; ?>

<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM pets WHERE pet_id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { setFlash('ไม่พบข้อมูล', 'error'); header('Location: pets_manage.php'); exit; }

$owners = $pdo->query("SELECT owner_id,name FROM owners ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name']; $species = $_POST['species']; $breed = $_POST['breed'];
    $age = (int)$_POST['age']; $owner_id = (int)$_POST['owner_id'];

    $stmt = $pdo->prepare("UPDATE pets SET name=?, species=?, breed=?, age=?, owner_id=? WHERE pet_id=?");
    $stmt->execute([$name,$species,$breed,$age,$owner_id,$id]);

    setFlash('แก้ไขสัตว์เลี้ยงสำเร็จ', 'success');
    header('Location: pets_manage.php'); exit;
}

include "templates/navbar.php";
?>
<div class="card">
  <h2>แก้ไขสัตว์เลี้ยง</h2>
  <form method="post">
    <label>ชื่อ</label><input type="text" name="name" value="<?= htmlspecialchars($p['name']) ?>" required>
    <label>ชนิด</label><input type="text" name="species" value="<?= htmlspecialchars($p['species']) ?>" required>
    <label>สายพันธุ์</label><input type="text" name="breed" value="<?= htmlspecialchars($p['breed']) ?>" required>
    <label>อายุ (ปี)</label><input type="number" name="age" min="0" value="<?= (int)$p['age'] ?>" required>
    <label>เจ้าของ</label>
    <select name="owner_id" required>
      <?php foreach($owners as $o): ?>
      <option value="<?= $o['owner_id'] ?>" <?= $o['owner_id']==$p['owner_id'] ? 'selected' : '' ?>><?= htmlspecialchars($o['name']) ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn btn-primary" type="submit" style="margin-top:10px;">บันทึก</button>
  </form>
</div>
<?php include "templates/footer.php"; ?>

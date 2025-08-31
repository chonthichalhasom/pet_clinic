<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$owners = $pdo->query("SELECT owner_id, name FROM owners ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name']; $species = $_POST['species']; $breed = $_POST['breed'];
    $age = (int)$_POST['age']; $owner_id = (int)$_POST['owner_id'];

    $stmt = $pdo->prepare("INSERT INTO pets (name,species,breed,age,owner_id) VALUES (?,?,?,?,?)");
    $stmt->execute([$name,$species,$breed,$age,$owner_id]);

    setFlash('เพิ่มสัตว์เลี้ยงสำเร็จ', 'success');
    header('Location: pets_manage.php'); exit;
}

include "templates/navbar.php";
?>
<div class="card">
  <h2>เพิ่มสัตว์เลี้ยง</h2>
  <form method="post">
    <label>ชื่อ</label><input type="text" name="name" required>
    <label>ชนิด (เช่น สุนัข / แมว)</label><input type="text" name="species" required>
    <label>สายพันธุ์</label><input type="text" name="breed" required>
    <label>อายุ (ปี)</label><input type="number" name="age" min="0" required>
    <label>เจ้าของ</label>
    <select name="owner_id" required>
      <option value="">-- เลือกเจ้าของ --</option>
      <?php foreach($owners as $o): ?>
      <option value="<?= $o['owner_id'] ?>"><?= htmlspecialchars($o['name']) ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn btn-primary" type="submit" style="margin-top:10px;">บันทึก</button>
  </form>
</div>
<?php include "templates/footer.php"; ?>

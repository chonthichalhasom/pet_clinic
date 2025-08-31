<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

// ดึงข้อมูลนัดหมายพร้อมเจ้าของ, สัตว์, หมอ
$sql = "SELECT a.appointment_id, a.date, a.reason, 
        p.name as pet_name, p.species, p.breed, p.age,
        o.name as owner_name, o.phone, o.email, o.address,
        s.name as staff_name
        FROM appointments a
        JOIN pets p ON a.pet_id = p.pet_id
        JOIN owners o ON p.owner_id = o.owner_id
        JOIN staff s ON a.staff_id = s.staff_id
        ORDER BY a.date DESC";

$appointments = $pdo->query($sql)->fetchAll();

include "templates/navbar.php";
?>

<h2>จัดการนัดหมาย</h2>

<table class="table">
  <thead>
    <tr>
      <th>เจ้าของ</th>
      <th>โทรศัพท์</th>
      <th>อีเมล</th>
      <th>ที่อยู่</th>
      <th>สัตว์เลี้ยง</th>
      <th>ชนิด</th>
      <th>พันธุ์</th>
      <th>อายุ</th>
      <th>หมอ</th>
      <th>วัน/เวลา</th>
      <th>อาการ</th>
      <th>จัดการ</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($appointments as $a): ?>
      <tr>
        <td><?= htmlspecialchars($a['owner_name']) ?></td>
        <td><?= htmlspecialchars($a['phone']) ?></td>
        <td><?= htmlspecialchars($a['email']) ?></td>
        <td><?= htmlspecialchars($a['address']) ?></td>
        <td><?= htmlspecialchars($a['pet_name']) ?></td>
        <td><?= htmlspecialchars($a['species']) ?></td>
        <td><?= htmlspecialchars($a['breed']) ?></td>
        <td><?= htmlspecialchars($a['age']) ?></td>
        <td><?= htmlspecialchars($a['staff_name']) ?></td>
        <td><?= htmlspecialchars($a['date']) ?></td>
        <td><?= htmlspecialchars($a['reason']) ?></td>
        <td>
          <a href="appointments_edit.php?id=<?= $a['appointment_id'] ?>" class="btn btn-warning">แก้ไข</a>
          <a href="appointments_del.php?id=<?= $a['appointment_id'] ?>" class="btn btn-danger" onclick="return confirm('แน่ใจว่าต้องการลบ?')">ลบ</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include "templates/footer.php"; ?>

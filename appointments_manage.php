<?php 
include "app/config.php"; 
include "app/helpers.php"; 
checkLogin(); 

$keyword = $_GET['keyword'] ?? ''; 

$sql = "
    SELECT a.*, 
           p.name as pet_name, 
           p.species, 
           p.breed, 
           p.age, 
           o.name as owner_name, 
           o.phone, 
           o.email, 
           s.name as staff_name 
    FROM appointments a 
    JOIN pets p ON a.pet_id = p.pet_id 
    JOIN owners o ON p.owner_id = o.owner_id 
    JOIN staff s ON a.staff_id = s.staff_id 
    WHERE 1
";

$params = [];
if ($keyword) {
    $sql .= " AND (o.name LIKE ? OR p.name LIKE ? OR s.name LIKE ?)";
    $params = ["%$keyword%", "%$keyword%", "%$keyword%"];
}

$sql .= " ORDER BY a.date DESC, a.time ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$appointments = $stmt->fetchAll();

include "templates/navbar.php"; 
?>

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="card p-4">
    <h2 class="mb-3">จัดการนัดหมาย</h2>

    <form method="get" class="mb-3 d-flex gap-2 flex-wrap">
        <input 
            type="text" 
            name="keyword" 
            value="<?= htmlspecialchars($keyword) ?>" 
            placeholder="ค้นหาเจ้าของ / สัตว์เลี้ยง / หมอ" 
            class="form-control"
        >
        <button type="submit" class="btn btn-primary">ค้นหา</button>
        <a href="appointments_manage.php" class="btn btn-secondary">ล้างค่า</a>
        <a href="appointments_add.php" class="btn btn-success ms-auto">+ เพิ่มนัดหมาย</a>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>เจ้าของ</th>
                    <th>สัตว์เลี้ยง</th>
                    <th>หมอ</th>
                    <th>วัน/เวลา</th>
                    <th>อาการ/หมายเหตุ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if($appointments): ?>
                    <?php foreach($appointments as $a): ?>
                        <tr>
                            <td>
                                <b><?= htmlspecialchars($a['owner_name']) ?></b><br>
                                📞 <?= htmlspecialchars($a['phone']) ?><br>
                                ✉️ <?= htmlspecialchars($a['email']) ?>
                            </td>
                            <td>
                                🐾 <?= htmlspecialchars($a['pet_name']) ?> (<?= htmlspecialchars($a['species']) ?>)<br>
                                พันธุ์: <?= htmlspecialchars($a['breed']) ?> / อายุ: <?= htmlspecialchars($a['age']) ?>
                            </td>
                            <td><?= htmlspecialchars($a['staff_name']) ?></td>
                            <td>
                                <?= date("d/m/Y", strtotime($a['date'])) ?> <?= substr($a['time'],0,5) ?>
                            </td>
                            <td><?= nl2br(htmlspecialchars($a['reason'])) ?></td>
                            <td>
                                <a href="appointments_edit.php?id=<?= $a['appointment_id'] ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="<?= $a['appointment_id'] ?>">ลบ</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">-- ไม่พบนัดหมาย --</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการลบรายการนัดหมายนี้จริง ๆ ใช่ไหม?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#45c9a2', // สีมิ้นท์
            cancelButtonColor: '#6c757d',  // สีเทา
            confirmButtonText: 'ยืนยันการลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'appointments_del.php?id=' + id;
            }
        });
    });
});
</script>

<?php include "templates/footer.php"; ?>

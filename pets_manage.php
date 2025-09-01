<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$keyword = $_GET['keyword'] ?? '';
$params = [];

$sql = "SELECT p.*, o.name AS owner_name FROM pets p LEFT JOIN owners o ON p.owner_id = o.owner_id WHERE 1";

if ($keyword) {
    $sql .= " AND (p.name LIKE ? OR p.species LIKE ? OR p.breed LIKE ? OR o.name LIKE ?)";
    $params = ["%$keyword%","%$keyword%","%$keyword%","%$keyword%"];
}

$sql .= " ORDER BY p.pet_id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pets = $stmt->fetchAll();

include "templates/navbar.php";
?>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="card p-4">
    <h2 class="mb-3">จัดการสัตว์เลี้ยง</h2>

    <form method="get" class="mb-3 d-flex gap-2 flex-wrap">
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="ค้นหาชื่อ/ชนิด/พันธุ์/เจ้าของ" class="form-control">
        <button type="submit" class="btn btn-primary">ค้นหา</button>
        <a href="pets_manage.php" class="btn btn-secondary">ล้างค่า</a>
        <a href="pets_add.php" class="btn btn-success ms-auto">+ เพิ่มสัตว์เลี้ยง</a>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>ชื่อ</th>
                    <th>ชนิด</th>
                    <th>สายพันธุ์</th>
                    <th>อายุ</th>
                    <th>เจ้าของ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if($pets): ?>
                    <?php foreach($pets as $p): ?>
                        <tr>
                            <td><?= $p['pet_id'] ?></td>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= htmlspecialchars($p['species']) ?></td>
                            <td><?= htmlspecialchars($p['breed']) ?></td>
                            <td><?= $p['age'] ?></td>
                            <td><?= htmlspecialchars($p['owner_name']) ?></td>
                            <td>
                                <a href="pets_edit.php?id=<?= $p['pet_id'] ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="<?= $p['pet_id'] ?>">ลบ</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted">-- ไม่พบสัตว์เลี้ยง --</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.querySelectorAll('.btn-delete').forEach(btn=>{
    btn.addEventListener('click', function(){
        const id = this.dataset.id;
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการลบสัตว์เลี้ยงนี้จริง ๆ ใช่ไหม?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#45c9a2',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ยืนยันการลบ',
            cancelButtonText: 'ยกเลิก'
        }).then(result=>{
            if(result.isConfirmed){
                window.location.href='pets_del.php?id='+id;
            }
        });
    });
});
</script>

<style>
form.d-flex .btn { white-space: nowrap; }
.card.p-4 { display:flex; flex-direction:column; gap:1rem; }
form.d-flex a.btn-success { margin-left:auto; }
</style>

<?php include "templates/footer.php"; ?>

<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$keyword = $_GET['keyword'] ?? '';
$params = [];

$sql = "SELECT * FROM staff WHERE 1";
if ($keyword) {
    $sql .= " AND (name LIKE ? OR role LIKE ? OR phone LIKE ? OR email LIKE ?)";
    $params = ["%$keyword%","%$keyword%","%$keyword%","%$keyword%"];
}
$sql .= " ORDER BY staff_id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$staffs = $stmt->fetchAll();

include "templates/navbar.php";
?>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="card p-4">
    <h2 class="mb-3">จัดการพนักงาน / สัตวแพทย์</h2>

    <form method="get" class="mb-3 d-flex gap-2 flex-wrap">
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="ค้นหาชื่อ/ตำแหน่ง/โทร/อีเมล" class="form-control">
        <button type="submit" class="btn btn-primary">ค้นหา</button>
        <a href="staff_manage.php" class="btn btn-secondary">ล้างค่า</a>
        <a href="staff_add.php" class="btn btn-success ms-auto">+ เพิ่มพนักงาน</a>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>ชื่อ</th>
                    <th>ตำแหน่ง</th>
                    <th>โทรศัพท์</th>
                    <th>อีเมล</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if($staffs): ?>
                    <?php foreach($staffs as $s): ?>
                        <tr>
                            <td><?= $s['staff_id'] ?></td>
                            <td><?= htmlspecialchars($s['name']) ?></td>
                            <td><?= htmlspecialchars($s['role']) ?></td>
                            <td><?= htmlspecialchars($s['phone']) ?></td>
                            <td><?= htmlspecialchars($s['email']) ?></td>
                            <td>
                                <a href="staff_edit.php?id=<?= $s['staff_id'] ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="<?= $s['staff_id'] ?>">ลบ</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted">-- ไม่พบพนักงาน --</td></tr>
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
            text: "คุณต้องการลบพนักงานนี้จริง ๆ ใช่ไหม?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#45c9a2',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ยืนยันการลบ',
            cancelButtonText: 'ยกเลิก'
        }).then(result=>{
            if(result.isConfirmed){
                window.location.href='staff_del.php?id='+id;
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

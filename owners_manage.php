<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$keyword = $_GET['keyword'] ?? '';
$params = [];

$sql = "SELECT * FROM owners WHERE 1";

if ($keyword) {
    $sql .= " AND (name LIKE ? OR phone LIKE ? OR email LIKE ? OR address LIKE ?)";
    $params = ["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"];
}

$sql .= " ORDER BY owner_id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$owners = $stmt->fetchAll();

include "templates/navbar.php";
?>

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="card p-4">
    <h2 class="mb-3">จัดการเจ้าของ</h2>

    <form method="get" class="mb-3 d-flex gap-2 flex-wrap">
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="ค้นหาชื่อ/โทร/อีเมล/ที่อยู่" class="form-control">
        <button type="submit" class="btn btn-primary">ค้นหา</button>
        <a href="owners_manage.php" class="btn btn-secondary">ล้างค่า</a>
        <a href="owners_add.php" class="btn btn-success ms-auto">+ เพิ่มเจ้าของ</a>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>ชื่อ</th>
                    <th>โทรศัพท์</th>
                    <th>อีเมล</th>
                    <th>ที่อยู่</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if($owners): ?>
                    <?php foreach($owners as $o): ?>
                        <tr>
                            <td><?= $o['owner_id'] ?></td>
                            <td><?= htmlspecialchars($o['name']) ?></td>
                            <td><?= htmlspecialchars($o['phone']) ?></td>
                            <td><?= htmlspecialchars($o['email']) ?></td>
                            <td><?= htmlspecialchars($o['address']) ?></td>
                            <td>
                                <a href="owners_edit.php?id=<?= $o['owner_id'] ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="<?= $o['owner_id'] ?>">ลบ</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted">-- ไม่พบเจ้าของ --</td></tr>
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
            text: "คุณต้องการลบเจ้าของนี้จริง ๆ ใช่ไหม?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#45c9a2',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ยืนยันการลบ',
            cancelButtonText: 'ยกเลิก'
        }).then(result=>{
            if(result.isConfirmed){
                window.location.href='owners_del.php?id='+id;
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

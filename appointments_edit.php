<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT a.*, p.name as pet_name, p.species, p.breed, p.age, p.owner_id, 
                              o.name as owner_name, o.phone, o.email, o.address
                       FROM appointments a
                       JOIN pets p ON a.pet_id = p.pet_id
                       JOIN owners o ON p.owner_id = o.owner_id
                       WHERE a.appointment_id=?");
$stmt->execute([$id]);
$a = $stmt->fetch();

if (!$a) { 
    setFlash('ไม่พบข้อมูล', 'error'); 
    header('Location: appointments_manage.php'); 
    exit; 
}

$staffs = $pdo->query("SELECT * FROM staff ORDER BY name")->fetchAll();

$currentDate = date('Y-m-d', strtotime($a['date']));
$currentTime = $a['time'] ?? date('H:i', strtotime($a['date']));

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner_name     = $_POST['owner_name'] ?? '';
    $owner_phone    = $_POST['owner_phone'] ?? '';
    $owner_email    = $_POST['owner_email'] ?? '';
    $owner_address  = $_POST['owner_address'] ?? '';
    $pet_name       = $_POST['pet_name'] ?? '';
    $pet_species    = $_POST['pet_species'] ?? '';
    $pet_breed      = $_POST['pet_breed'] ?? '';
    $pet_age        = (int)($_POST['pet_age'] ?? 0);
    $staff_id       = $_POST['staff_id'] ?? '';
    $date           = $_POST['date'] ?? '';
    $time           = $_POST['time'] ?? '';
    $reason         = $_POST['reason'] ?? '';
    $payment_method = $_POST['payment_method'] ?? 'cash';

    if ($owner_name && $pet_name && $pet_species && $staff_id && $date && $time) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("UPDATE owners SET name=?, phone=?, email=?, address=? WHERE owner_id=?");
            $stmt->execute([$owner_name,$owner_phone,$owner_email,$owner_address,$a['owner_id']]);

            $stmt = $pdo->prepare("UPDATE pets SET name=?, species=?, breed=?, age=? WHERE pet_id=?");
            $stmt->execute([$pet_name,$pet_species,$pet_breed,$pet_age,$a['pet_id']]);

            $dateTime = date('Y-m-d H:i:s', strtotime("$date $time"));
            $stmt = $pdo->prepare("UPDATE appointments SET staff_id=?, date=?, time=?, reason=? WHERE appointment_id=?");
            $stmt->execute([$staff_id,$dateTime,$time,$reason,$id]);

            $pdo->commit();

            setFlash('แก้ไขนัดหมายสำเร็จ','success');
            header('Location: appointments_manage.php');
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
        }
    } else {
        $error = "กรุณากรอกข้อมูลให้ครบถ้วน";
    }
}

include "templates/navbar.php";
?>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600&display=swap" rel="stylesheet">
<style>
body { font-family: 'Prompt', sans-serif; background-color: #f5f5f5; }
.card, .form-section { border-radius: 15px; background-color: #fff; padding: 25px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
h2, h4 { font-weight: 600; }
.btn-primary { border-radius: 8px; padding: 10px 20px; }
.alert { border-radius: 10px; }
</style>

<div class="container mt-5 mb-5" style="max-width: 900px;">
    <h2 class="text-center mb-4">แก้ไขนัดหมาย</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <!-- เจ้าของ -->
        <div class="form-section">
            <h4 class="mb-3 text-primary">ข้อมูลเจ้าของ</h4>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>ชื่อเจ้าของ</label>
                    <input type="text" name="owner_name" class="form-control" value="<?= htmlspecialchars($a['owner_name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label>เบอร์โทร</label>
                    <input type="text" name="owner_phone" class="form-control" value="<?= htmlspecialchars($a['phone']) ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>อีเมล</label>
                    <input type="email" name="owner_email" class="form-control" value="<?= htmlspecialchars($a['email']) ?>">
                </div>
                <div class="col-md-6">
                    <label>ที่อยู่</label>
                    <input type="text" name="owner_address" class="form-control" value="<?= htmlspecialchars($a['address']) ?>">
                </div>
            </div>
        </div>

        <!-- สัตว์เลี้ยง -->
        <div class="form-section">
            <h4 class="mb-3 text-success">ข้อมูลสัตว์เลี้ยง</h4>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label>ชื่อสัตว์เลี้ยง</label>
                    <input type="text" name="pet_name" class="form-control" value="<?= htmlspecialchars($a['pet_name']) ?>" required>
                </div>
                <div class="col-md-3">
                    <label>ชนิด</label>
                    <input type="text" name="pet_species" class="form-control" value="<?= htmlspecialchars($a['species']) ?>" required>
                </div>
                <div class="col-md-3">
                    <label>พันธุ์</label>
                    <input type="text" name="pet_breed" class="form-control" value="<?= htmlspecialchars($a['breed']) ?>">
                </div>
                <div class="col-md-3">
                    <label>อายุ</label>
                    <input type="number" name="pet_age" class="form-control" value="<?= htmlspecialchars($a['age']) ?>" min="0">
                </div>
            </div>
        </div>

        <!-- นัดหมาย -->
        <div class="form-section">
            <h4 class="mb-3 text-warning">ข้อมูลการนัดหมาย</h4>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>เลือกหมอ</label>
                    <select name="staff_id" class="form-control" required>
                        <?php foreach ($staffs as $s): ?>
                            <option value="<?= $s['staff_id'] ?>" <?= $s['staff_id']==$a['staff_id']?'selected':'' ?>>
                                <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['role']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>วันที่</label>
                    <input type="date" name="date" class="form-control" value="<?= $currentDate ?>" required>
                </div>
                <div class="col-md-4">
                    <label>เวลา</label>
                    <select name="time" class="form-control" required>
                        <?php
                        $times = ["09:00","09:30","10:00","10:30","11:00","11:30",
                                  "13:00","13:30","14:00","14:30","15:00","15:30",
                                  "16:00","16:30","17:00","17:30"];
                        foreach ($times as $t) {
                            $selected = $t==$currentTime ? 'selected' : '';
                            echo "<option value='$t' $selected>$t</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>อาการ/หมายเหตุ</label>
                <textarea name="reason" class="form-control" rows="3"><?= htmlspecialchars($a['reason']) ?></textarea>
            <button type="submit" class="btn btn-primary">บันทึกการนัดหมาย</button>
              </div>
        </div>

   
    </form>
</div>

<?php include "templates/footer.php"; ?>

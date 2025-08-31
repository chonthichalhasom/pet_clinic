<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM staff WHERE staff_id = ?");
$stmt->execute([$id]);
setFlash('ลบพนักงานเรียบร้อย', 'success');
header('Location: staff_manage.php'); exit;

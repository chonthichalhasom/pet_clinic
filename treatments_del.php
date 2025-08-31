<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM treatments WHERE treatment_id = ?");
$stmt->execute([$id]);
setFlash('ลบข้อมูลการรักษาแล้ว', 'success');
header('Location: treatments_manage.php'); exit;

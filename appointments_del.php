<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM appointments WHERE appointment_id = ?");
$stmt->execute([$id]);
setFlash('ลบนัดหมายเรียบร้อย', 'success');
header('Location: appointments_manage.php'); exit;

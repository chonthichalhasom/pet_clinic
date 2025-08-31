<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM pets WHERE pet_id = ?");
$stmt->execute([$id]);

setFlash('ลบสัตว์เลี้ยงเรียบร้อย', 'success');
header('Location: pets_manage.php'); exit;

<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM owners WHERE owner_id = ?");
$stmt->execute([$id]);

setFlash('ลบเจ้าของเรียบร้อย', 'success');
header('Location: owners_manage.php'); exit;

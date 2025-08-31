<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM receipts WHERE receipt_id = ?");
$stmt->execute([$id]);
setFlash('ลบใบเสร็จเรียบร้อย', 'success');
header('Location: receipts_manage.php'); exit;

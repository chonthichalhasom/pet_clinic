<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->execute([$id]);
setFlash('ลบผู้ใช้เรียบร้อย', 'success');
header('Location: users_manage.php'); exit;

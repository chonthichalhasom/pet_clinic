<?php
include "app/config.php";
include "app/helpers.php";
checkLogin();

$receipt_id = $_POST['receipt_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$receipt_id || !$action) {
    setFlash("ข้อมูลไม่ครบ", "error");
    header("Location: index.php");
    exit;
}

// ตรวจสอบใบเสร็จ
$stmt = $pdo->prepare("SELECT * FROM receipts WHERE receipt_id = ? LIMIT 1");
$stmt->execute([$receipt_id]);
$receipt = $stmt->fetch();

if (!$receipt) {
    setFlash("ไม่พบข้อมูลใบเสร็จนี้", "error");
    header("Location: index.php");
    exit;
}

if ($action === 'paid') {
    // อัปเดตสถานะการชำระเงิน
    $stmt = $pdo->prepare("UPDATE receipts SET status = 'paid' WHERE receipt_id = ?");
    $stmt->execute([$receipt_id]);

    setFlash("ชำระเงินเรียบร้อยแล้ว", "success");
    header("Location: receipts_view.php?id=$receipt_id");
    exit;
} elseif ($action === 'cancel') {
    setFlash("ยกเลิกการชำระเงิน", "warning");
    header("Location: index.php");
    exit;
} else {
    setFlash("การกระทำไม่ถูกต้อง", "error");
    header("Location: index.php");
    exit;
}

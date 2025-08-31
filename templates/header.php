<?php
// templates/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>ระบบจัดการคลินิกสัตว์เลี้ยง</title>

  <!-- Google Fonts (Noto Sans Thai, Prompt) -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;600&family=Prompt:wght@400;600&display=swap" rel="stylesheet">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<?php
include __DIR__ . '/header.php';
?>
<header class="site-header">
  <nav class="navbar">
    <div class="nav-left">
      <a class="brand" href="index.php">
        <span class="brand-emoji">🏥</span>
        <span class="brand-text">Pet Clinic</span>
      </a>
    </div>
    <div class="nav-right">
      <a href="owners_manage.php">เจ้าของ</a>
      <a href="pets_manage.php">สัตว์เลี้ยง</a>
      <a href="staff_manage.php">หมอ</a>
      <a href="appointments_manage.php">นัดหมาย</a>
      <a href="#" onclick="confirmLogout(event)" class="logout-btn">ออกจากระบบ</a>
    </div>
  </nav>
</header>
<main class="container">

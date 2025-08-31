<?php
// logout.php
include "app/helpers.php";
session_destroy();
session_start();
setFlash('คุณได้ออกจากระบบแล้ว', 'info');
header('Location: login.php');
exit;

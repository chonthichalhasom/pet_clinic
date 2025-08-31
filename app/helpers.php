<?php
// app/helpers.php
if (session_status() === PHP_SESSION_NONE) session_start();

function checkLogin() {
    if (empty($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

function setFlash($msg, $type = 'success') {
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}

function getFlash() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

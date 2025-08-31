<?php
// app/config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";   // แก้เป็น user ของ MySQL
$pass = "";       // ใส่รหัสผ่านของ MySQL
$db   = "pet_clinic"; // database

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage());
}

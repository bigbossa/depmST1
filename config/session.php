<?php
session_start();

// ถ้าไม่ได้เข้าสู่ระบบ ให้เด้งไปหน้า login
if (!isset($_SESSION['user_id'])) {
    header("Location: /public/login.php");
    exit();
}
?>
<?php
$host = "localhost";
$user = "root";      // ชื่อผู้ใช้ MySQL
$pass = "";          // รหัสผ่าน (ถ้ามี)
$dbname = "dormitory_management";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $user, $pass, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
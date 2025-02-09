<?php
// เริ่มเซสชัน
session_start();

// ลบข้อมูลในเซสชันทั้งหมด
session_unset();

// ลบ session id ที่เก็บในคุกกี้
session_destroy();

// รีไดเร็กต์ไปที่หน้าเข้าสู่ระบบ
header("Location: login.php");
exit();
?>
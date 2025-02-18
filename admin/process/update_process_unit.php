<?php
include("../../config/session.php");
include("../../config/database.php");

// ตรวจสอบว่ามีการส่งข้อมูลมาจากฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $id = 1; // กำหนดค่าตายตัวเป็น 1
    $water = $_POST["water"];
    $electricity = $_POST["electricity"];

    // ป้องกัน SQL Injection
    $water = $conn->real_escape_string($water);
    $electricity = $conn->real_escape_string($electricity);

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE meter SET water = '$water', electricity = '$electricity' WHERE id = 1";

    if ($conn->query($sql) === TRUE) {
        // อัปเดตสำเร็จ
        $_SESSION['sweetalert'] = true;
        $_SESSION['message'] = "อัปเดตข้อมูลสำเร็จ";
        $_SESSION['type'] = "success";
        header("Location: ../electricity_and_water_unit.php");
    } else {
        // มีข้อผิดพลาด
        $_SESSION['sweetalert'] = true;
        $_SESSION['message'] = "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง";
        $_SESSION['type'] = "error";
        header("Location: ../electricity_and_water_unit.php");
    }
} else {
    // ถ้าไม่ได้ส่งข้อมูลมาจากฟอร์ม ให้กลับไปหน้าหลัก
    header("Location: ../electricity_and_water_unit.php");
}

$conn->close();
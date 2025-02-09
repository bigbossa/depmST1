<?php
include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenant_id = $_POST['tenant_id'];
    $room_id = $_POST['room_id'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $rent_fee = $_POST['rent_fee'];
    $water_bill = $_POST['water_bill'];
    $electricity_bill = $_POST['electricity_bill'];
    $total = $rent_fee + $water_bill + $electricity_bill;

    $stmt = $conn->prepare("INSERT INTO bills (tenant_id, room_id, month, year, rent_fee, water_bill, electricity_bill, total, paid_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'ยังไม่จ่าย')");
    $stmt->bind_param("iiiiiiii", $tenant_id, $room_id, $month, $year, $rent_fee, $water_bill, $electricity_bill, $total);
    $stmt->execute();

    echo "บันทึกค่าใช้จ่ายสำเร็จ!";
}
?>
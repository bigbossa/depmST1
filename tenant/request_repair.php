<?php
include("../config/session.php");
include("../config/database.php");

// ตรวจสอบสิทธิ์ผู้ใช้
if ($_SESSION['role'] != 'tenant') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenant_id = $_SESSION['user_id'];
    $room_id = $_POST['room_id'];
    $issue_description = $_POST['issue_description'];
    $repair_status = 'pending'; // สถานะเริ่มต้นคือ "รอดำเนินการ"

    // บันทึกการแจ้งซ่อม
    $stmt = $conn->prepare("INSERT INTO repair_requests (tenant_id, room_id, issue_description, repair_status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $tenant_id, $room_id, $issue_description, $repair_status);
    $stmt->execute();

    $success_message = "การแจ้งซ่อมของคุณถูกส่งเรียบร้อยแล้ว";
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แจ้งซ่อม</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h2>แจ้งซ่อมสำหรับห้องพัก</h2>

    <?php if (isset($success_message)) echo "<p style='color:green;'>$success_message</p>"; ?>

    <form method="post">
        <label>ห้องพัก:</label>
        <input type="text" name="room_id" required><br>

        <label>รายละเอียดปัญหาที่พบ:</label>
        <textarea name="issue_description" required></textarea><br>

        <button type="submit">ส่งการแจ้งซ่อม</button>
    </form>

    <p><a href="dashboard.php">กลับไปที่หน้าหลัก</a></p>
</body>

</html>
<?php
include("../config/session.php");
include("../config/database.php");

// ตรวจสอบสิทธิ์ผู้ใช้
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// ตรวจสอบว่าได้ส่ง id ห้องพักมาไหม
if (!isset($_GET['id'])) {
    header("Location: manage_rooms.php");
    exit();
}

$room_id = $_GET['id'];

// ดึงข้อมูลห้องพักจากฐานข้อมูล
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: manage_rooms.php");
    exit();
}

$room = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_number = $_POST["room_number"];
    $type = $_POST["type"];
    $price = $_POST["price"];
    $status = $_POST["status"];

    // อัพเดตข้อมูลห้องพัก
    $stmt = $conn->prepare("UPDATE rooms SET room_number = ?, type = ?, price = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssisi", $room_number, $type, $price, $status, $room_id);
    $stmt->execute();

    $success_message = "ข้อมูลห้องพักถูกอัพเดตเรียบร้อยแล้ว!";
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แก้ไขห้องพัก</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h2>แก้ไขข้อมูลห้องพัก</h2>

    <?php if (isset($success_message)) echo "<p style='color:green;'>$success_message</p>"; ?>

    <form method="post">
        <label>หมายเลขห้อง:</label>
        <input type="text" name="room_number" value="<?= $room['room_number'] ?>" required><br>

        <label>ประเภทห้อง:</label>
        <select name="type" required>
            <option value="single" <?= $room['type'] == 'single' ? 'selected' : '' ?>>ห้องเดี่ยว</option>
            <option value="double" <?= $room['type'] == 'double' ? 'selected' : '' ?>>ห้องคู่</option>
            <option value="suite" <?= $room['type'] == 'suite' ? 'selected' : '' ?>>ห้องสูท</option>
        </select><br>

        <label>ราคา:</label>
        <input type="number" name="price" value="<?= $room['price'] ?>" required><br>

        <label>สถานะห้อง:</label>
        <select name="status" required>
            <option value="available" <?= $room['status'] == 'available' ? 'selected' : '' ?>>ว่าง</option>
            <option value="occupied" <?= $room['status'] == 'occupied' ? 'selected' : '' ?>>ถูกจอง</option>
            <option value="maintenance" <?= $room['status'] == 'maintenance' ? 'selected' : '' ?>>อยู่ระหว่างซ่อม
            </option>
        </select><br>

        <button type="submit">บันทึกการเปลี่ยนแปลง</button>
    </form>

    <p><a href="manage_rooms.php">กลับไปที่หน้าจัดการห้องพัก</a></p>
</body>

</html>
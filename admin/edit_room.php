<?php
include("../config/session.php");
include("../config/database.php");

// ตรวจสอบสิทธิ์ผู้ใช้
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $user_sql = "SELECT full_name FROM users WHERE id = ?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user_data = $user_result->fetch_assoc();
    $full_name = $user_data['full_name'] ?? 'Guest'; // หากไม่มีข้อมูลให้แสดง 'Guest'
    $stmt->close();
} else {
    $full_name = 'Guest'; // หากไม่ได้ล็อกอินให้แสดง 'Guest'
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

// ดึงรายชื่อผู้เช่าจากตาราง users ที่มี role เป็น 'tenant'
$user_query = $conn->query("SELECT id, username, full_name FROM users WHERE role = 'tenant'");
$users = $user_query->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_number = $_POST["room_number"];
    $type = $_POST["type"];
    $price = $_POST["price"] !== '' ? $_POST["price"] : null;
    $status = $_POST["status"];
    $tenant_id = $_POST["tenant_id"] !== '' ? $_POST["tenant_id"] : null;
    $date_of_stay = $_POST["date_of_stay"] !== '' ? $_POST["date_of_stay"] : null;
    $expiration_date = $_POST["expiration_date"] !== '' ? $_POST["expiration_date"] : null;

    // อัพเดตข้อมูลห้องพัก
    $stmt = $conn->prepare("UPDATE rooms SET 
            room_number = ?,
            type = ?,
            price = ?,
            status = ?,
            tenant_id = ?,
            `Date_of_Stay` = ?,
            `Expiration_Date` = ?
            WHERE id = ?");
    $stmt->bind_param(
        "ssdssssi",
        $room_number,
        $type,
        $price,
        $status,
        $tenant_id,
        $date_of_stay,
        $expiration_date,
        $room_id
    );

    if ($stmt->execute()) {
        $success_message = "อัพเดทข้อมูลสำเร็จ";
    } else {
        $error_message = "เกิดข้อผิดพลาด: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลห้องพัก</title>
    <link rel="icon" type="image/png" href="../assets/images/home.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    .sidebar {
        height: 100vh;
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #343a40;
        padding-top: 20px;
        color: white;
    }

    .sidebar a {
        padding: 10px;
        text-decoration: none;
        color: white;
        display: block;
    }

    .sidebar a:hover {
        background-color: #495057;
    }

    .sidebar img {
        display: block;
        margin: 0 auto;
        border-radius: 10px;
    }

    .content {
        margin-left: 260px;
        padding: 20px;
        flex: 1;
    }

    .footer {
        padding: 16px;
        background-color: #343a40;
        color: white;
        text-align: center;
        margin-top: auto;
    }

    .footer a {
        color: #5b9bd5;
        text-decoration: none;
    }

    .footer a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include "../assets/assets/admin_sidebar.php"; ?>

    <!-- Content -->
    <div class="content">
        <h2>แก้ไขข้อมูลห้องพัก</h2>

        <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="id" value="<?= $room['id'] ?>">

            <div class="mb-3">
                <label for="room_number" class="form-label">หมายเลขห้อง:</label>
                <input type="text" name="room_number" id="room_number" class="form-control"
                    value="<?= $room['room_number'] ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">ประเภทห้อง:</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="standard" <?= $room['type'] == 'standard' ? 'selected' : '' ?>>มาตรฐาน</option>
                    <option value="deluxe" <?= $room['type'] == 'deluxe' ? 'selected' : '' ?>>ดีลักซ์</option>
                    <option value="vip" <?= $room['type'] == 'vip' ? 'selected' : '' ?>>วีไอพี</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">ราคา:</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control"
                    value="<?= $room['price'] ?>">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">สถานะห้อง:</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="available" <?= $room['status'] == 'available' ? 'selected' : '' ?>>ว่าง</option>
                    <option value="reserved" <?= $room['status'] == 'reserved' ? 'selected' : '' ?>>ซ่อมแซม</option>
                    <option value="occupied" <?= $room['status'] == 'occupied' ? 'selected' : '' ?>>มีผู้เช่า</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tenant_id" class="form-label">ผู้เช่าที่เกี่ยวข้อง:</label>
                <select name="tenant_id" id="tenant_id" class="form-select">
                    <option value="">ไม่มีผู้เช่า</option>
                    <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>" <?= $room['tenant_id'] == $user['id'] ? 'selected' : '' ?>>
                        <?= $user['full_name'] ?? 'ไม่ระบุชื่อ' ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="date_of_stay" class="form-label">วันที่เข้าพัก:</label>
                <input type="date" name="date_of_stay" id="date_of_stay" class="form-control"
                    value="<?= $room['Date_of_Stay'] ? date('Y-m-d', strtotime($room['Date_of_Stay'])) : '' ?>">
            </div>

            <div class="mb-3">
                <label for="expiration_date" class="form-label">วันที่สิ้นสุด:</label>
                <input type="date" name="expiration_date" id="expiration_date" class="form-control"
                    value="<?= $room['Expiration_Date'] ? date('Y-m-d', strtotime($room['Expiration_Date'])) : '' ?>">
            </div>

            <button type="submit" name="update" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
            <button class="btn btn-warning float-end">
                <a href="manage_rooms.php" style="color: white; text-decoration: none;">กลับไปที่หน้าจัดการห้อง</a>
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
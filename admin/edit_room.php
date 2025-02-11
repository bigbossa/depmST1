<?php
include("../config/session.php");
include("../config/database.php");

// ตรวจสอบสิทธิ์ผู้ใช้
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
$user_query = $conn->query("SELECT id, username FROM users WHERE role = 'tenant'");
$users = $user_query->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_number = $_POST["room_number"];
    $type = $_POST["type"];
    $price = $_POST["price"];
    $status = $_POST["status"];
    $tenant_id = empty($_POST["tenant_id"]) ? NULL : $_POST["tenant_id"]; // เปลี่ยนค่า "" เป็น NULL

    // อัพเดตข้อมูลห้องพัก
    $stmt = $conn->prepare("UPDATE rooms SET room_number = ?, type = ?, price = ?, status = ?, tenant_id = ? WHERE id = ?");
    $stmt->bind_param("ssdsii", $room_number, $type, $price, $status, $tenant_id, $room_id);
    $stmt->execute();

    $success_message = "ข้อมูลห้องพักถูกอัพเดตเรียบร้อยแล้ว!";
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
    <div class="sidebar">
        <img src="../assets/images/home.png" alt="Logo" width="50">
        <center style="color: white;">หอพักบ้านพุธชาติ</center>
        <center style="color: white;"><?php echo htmlspecialchars($full_name); ?></center>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_rooms.php">Manage Rooms</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_bills.php">Manage Bills</a>
        <a href="reports.php">Report</a>
        <a href="../public/logout.php">Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>แก้ไขข้อมูลห้องพัก</h2>

        <?php if (isset($success_message)) echo "<div class='alert alert-success'>$success_message</div>"; ?>

        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="room_number" class="form-label">หมายเลขห้อง:</label>
                <input type="text" name="room_number" id="room_number" class="form-control"
                    value="<?= $room['room_number'] ?>" required readonly>
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
                    value="<?= $room['price'] ?>" required>
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
                        <?= $user['username'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
            <button class="btn btn-warning float-end">
                <a href="manage_users.php" style="color: white; text-decoration: none;">กลับไปที่หน้าจัดการผู้ใช้งาน</a>
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
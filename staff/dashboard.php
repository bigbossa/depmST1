<?php
include '../config/session.php';// เริ่ม session
include '../config/database.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

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

// ตรวจสอบว่าตาราง finance มีอยู่หรือไม่
$check_table_sql = "SHOW TABLES LIKE 'finance'";
$check_result = $conn->query($check_table_sql);
if ($check_result->num_rows == 0) {
    die("Error: Table 'finance' does not exist. Please create the table first.");
}

// ดึงข้อมูลห้องพักจากฐานข้อมูล
$sql = "SELECT id, room_number, status FROM rooms";
$result = $conn->query($sql);

// กำหนดเดือนปัจจุบันเริ่มต้น
$current_month = date('m');
if (isset($_GET['month'])) {
    $current_month = $_GET['month'];
}

// ดึงข้อมูลรายรับ-รายจ่ายของเดือนที่เลือก
$finance_sql = "SELECT MONTH(date) as month, SUM(income) as total_income, SUM(expense) as total_expense FROM finance WHERE MONTH(date) = ? GROUP BY MONTH(date)";
$stmt = $conn->prepare($finance_sql);
$stmt->bind_param("i", $current_month);
$stmt->execute();
$finance_result = $stmt->get_result();
$finance_data = [];
while ($row = $finance_result->fetch_assoc()) {
    $finance_data[] = $row;
}
$stmt->close();

// รายชื่อเดือน
$months = [
    "1" => "มกราคม",
    "2" => "กุมภาพันธ์",
    "3" => "มีนาคม",
    "4" => "เมษายน",
    "5" => "พฤษภาคม",
    "6" => "มิถุนายน",
    "7" => "กรกฎาคม",
    "8" => "สิงหาคม",
    "9" => "กันยายน",
    "10" => "ตุลาคม",
    "11" => "พฤศจิกายน",
    "12" => "ธันวาคม"
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saff Dashboard</title>
    <link rel="icon" type="image/png" href="../assets/images/home.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    .sidebar {
        text-align: left;
        height: 100vh;
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #343a40;
        padding-top: 20px;
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
    }

    footer {
        margin-left: -140px;
        width: 120%;
        padding: 30px;
        background-color: #343a40;
        color: white;
        text-align: center;
    }

    .footer a {
        color: #5b9bd5;
        text-decoration: none;
    }

    .footer a:hover {
        text-decoration: underline;
    }

    .room-status {
        display: grid;
        grid-template-columns: repeat(8, 0.5fr);
        gap: 10px;
        justify-items: center;
    }

    .room-status .room {
        padding: 10px;
        border-radius: 5px;
        color: white;
        text-align: center;
        width: 100px;
    }

    .room-status .room.available {
        background-color: #198754;
    }

    .room-status .room.occupied {
        background-color: #dc3545;
    }

    .room-status .room.maintenance {
        background-color: #ffc107;
    }

    </style>
</head>

<body>
    
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

    <div class="content">
        <h2 class="mb-4 " style ="text-align: left;">Saff Dashboard</h2>
        <!-- ห้องพักสถานะ -->
        <div class="d-flex  gap-1 my-3">
            <div class="bg-success text-white p-3 rounded"></div>
            <div class=" p-1 rounded">ห้องว่าง</div>
            <div class="bg-danger text-white p-3 rounded"></div>
            <div class=" p-1 rounded">มีผู้เช่า</div>
            <div class="bg-warning text-white p-3 rounded"></div>
            <div class=" p-1 rounded">ซ่อมบำรุง</div>
        </div>

        <!-- แสดงห้องพัก -->
        <div class="room-status">
            <?php while ($room = $result->fetch_assoc()) : ?>
            <div class="room <?php
                                    echo $room['status'] == 'available' ? 'available' : ($room['status'] == 'occupied' ? 'occupied' : 'maintenance');
                                    ?>">
                ห้อง <?php echo $room['room_number']; ?>
            </div>
            <?php endwhile; ?>
        </div>
        <br>
       <?php
            include '../assets/assets/calendar.php';
       ?>
        <br>
        <br>
        <br>
        <br>
    <!-- Footer -->
<footer>
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
</footer>
</body>

</html>
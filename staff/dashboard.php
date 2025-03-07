<?php
include("../config/session.php");
include("../config/database.php");

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

// ดึงข้อมูลผู้ใช้ทั้งหมดจากฐานข้อมูล
$result = $conn->query("SELECT id, username, full_name, phone, email, role FROM users");

// ลบผู้ใช้
if (isset($_GET["delete"])) {
    $user_id = $_GET["delete"];
    $conn->query("DELETE FROM users WHERE id = $user_id");
    header("Location: manage_bali.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้</title>
    <link rel="icon" type="image/png" href="../assets/images/home.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
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

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .footer {
            margin-left: 150px;
            padding: 16px;
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

        .sidebar img {
            display: block;
            margin: 0 auto;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php
    include "../assets/assets/Staff_sidebar.php";
    ?>


    <!-- Content -->
    <div class="content">
        <h2 class="mb-4">Staff dashboard</h2>
        <!-- <a href="add_user.php" class="btn btn-primary mb-3">เพิ่มผู้ใช้ใหม่</a> -->
        <?php
        include "../assets/assets/calendar.php";
        ?>
    </div>
    <br><br><br><br><br><br><br><br> <br><br><br><br>



    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
    </div>
</body>

</html>
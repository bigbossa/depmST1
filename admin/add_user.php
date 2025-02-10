<?php
include("../config/session.php");
include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $full_name = $_POST["full_name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $role = $_POST["role"];

    $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, phone, email, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $password, $full_name, $phone, $email, $role);
    $stmt->execute();

    header("Location: manage_users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มผู้ใช้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_rooms.php">Manage Rooms</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="reports.php">Report</a>
        <a href="../public/logout.php">Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2 class="mb-4">เพิ่มผู้ใช้</h2>
        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้:</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน:</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <div class="mb-3">
                <label for="full_name" class="form-label">ชื่อ-นามสกุล:</label>
                <input type="text" class="form-control" name="full_name" id="full_name" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">เบอร์โทร:</label>
                <input type="text" class="form-control" name="phone" id="phone">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">บทบาท:</label>
                <select name="role" class="form-select" id="role">
                    <option value="admin">เจ้าของหอพัก</option>
                    <option value="staff">ลูกจ้าง</option>
                    <option value="tenant">ผู้เช่า</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">เพิ่มผู้ใช้</button>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </div>
</body>

</html>

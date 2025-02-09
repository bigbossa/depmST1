<?php
include("../config/session.php");
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .dashboard-links {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .dashboard-links a {
            display: block;
            background-color: #5b9bd5;
            color: white;
            padding: 12px 25px;
            margin: 8px 0;
            width: 200px;
            text-align: center;
            border-radius: 4px;
            font-size: 16px;
            text-decoration: none;
        }

        .dashboard-links a:hover {
            background-color: #4a8ac7;
        }

        .dashboard-links a:active {
            background-color: #4178a0;
        }

        .logout-btn {
            background-color: #e74c3c;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>ยินดีต้อนรับ Admin</h1>
        <div class="dashboard-links">
            <a href="add_user.php">Add User</a>
            <a href="edit_user.php">Edit User</a>
            <a href="manage_rooms.php">Manage Room</a>
            <a href="manage_users.php">Manage User</a>
            <a href="reports.php">Reports</a>
            <a href="../public/logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</body>

</html>

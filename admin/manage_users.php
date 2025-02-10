<?php
include("../config/session.php");
include("../config/database.php");

// ดึงข้อมูลผู้ใช้ทั้งหมดจากฐานข้อมูล
$result = $conn->query("SELECT id, username, full_name, phone, email, role FROM users");

// ลบผู้ใช้
if (isset($_GET["delete"])) {
    $user_id = $_GET["delete"];
    $conn->query("DELETE FROM users WHERE id = $user_id");
    header("Location: manage_users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้</title>
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
        .sidebar img {
        display: block;
        margin: 0 auto;
        border-radius: 10px;
    }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">

        <img src="../assets/images/home.png" alt="Logo" width="50">
        <center style="color: white;">หอพักบ้านพุธชาติ</center>
        <br>

        <a href="dashboard.php">Dashboard</a>
        <a href="manage_rooms.php">Manage Rooms</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="reports.php">Report</a>
        <a href="../public/logout.php">Logout</a>
    </div>


    <!-- Content -->
    <div class="content">
        <h2 class="mb-4">จัดการผู้ใช้</h2>
        <!-- <a href="add_user.php" class="btn btn-primary mb-3">เพิ่มผู้ใช้ใหม่</a> -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ชื่อผู้ใช้</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>เบอร์โทร</th>
                    <th>Email</th>
                    <th>บทบาท</th>
                    <th>การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row["username"] ?></td>
                        <td><?= $row["full_name"] ?></td>
                        <td><?= $row["phone"] ?></td>
                        <td><?= $row["email"] ?></td>
                        <td><?= ucfirst($row["role"]) ?></td>
                        <td>
                            <a href="edit_user.php?id=<?= $row["id"] ?>" class="btn btn-warning btn-sm">แก้ไข</a> |
                            <a href="manage_users.php?delete=<?= $row["id"] ?>"
                               class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบ?');">ลบ</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<br><br><br><br><br><br><br><br> <br><br><br><br>



    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </div>
</body>

</html>

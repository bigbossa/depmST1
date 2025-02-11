<?php
include("../config/session.php");
include("../config/database.php");

if (!isset($_GET["id"])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = $_GET["id"];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["full_name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $role = $_POST["role"];

    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $full_name, $phone, $email, $role, $user_id);
    $stmt->execute();

    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้ใช้</title>
    <link rel="icon" type="image/png" href="../assets/images/home.png">
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
        flex: 1;
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
        <a href="manage_bills.php">Manage Bills</a>
        <a href="reports.php">Report</a>
        <a href="../public/logout.php">Logout</a>
    </div>


    <!-- Content -->
    <div class="content">
        <h2>แก้ไขข้อมูลผู้ใช้</h2>

        <form method="post">
            <div class="mb-3">
                <label for="full_name" class="form-label">ชื่อ-นามสกุล:</label>
                <input type="text" class="form-control" name="full_name" id="full_name"
                    value="<?= $user['full_name'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">เบอร์โทร:</label>
                <input type="text" class="form-control" name="phone" id="phone" value="<?= $user['phone'] ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email" value="<?= $user['email'] ?>">
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">บทบาท:</label>
                <select name="role" class="form-select" id="role" required>
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>เจ้าของหอพัก</option>
                    <option value="staff" <?= $user['role'] == 'staff' ? 'selected' : '' ?>>ลูกจ้าง</option>
                    <option value="tenant" <?= $user['role'] == 'tenant' ? 'selected' : '' ?>>ผู้เช่า</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">บันทึก</button>
            <button class="btn btn-warning float-end">
                <a href="manage_users.php" style="color: white; text-decoration: none;">กลับไปที่หน้าจัดการผู้ใช้งาน</a>
            </button>
        </form>


    </div>
    <br><br><br><br><br><br><br>
    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
    </div>
</body>

</html>
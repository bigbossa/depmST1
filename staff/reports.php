<?php
include("../config/session.php");
include("../config/database.php");

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user_data = $user_result->fetch_assoc();
    $full_name = $user_data['full_name'] ?? 'Guest';
    $stmt->close();
} else {
    $full_name = 'Guest';
}

// ตรวจสอบบทบาทของผู้ใช้
$user_role = $_SESSION['role'];
$tenant_id = $_SESSION['user_id'];

// Admin และ Staff: ลบคำขอแจ้งซ่อม
if (($user_role == 'admin' || $user_role == 'staff') && isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM maintenance_requests WHERE id = $delete_id");
    header("Location: reports.php");
    exit;
}

// Tenant: ส่งคำขอแจ้งซ่อม
if ($user_role == 'tenant' && isset($_POST['submit_request'])) {
    $room_id = $_POST['room_id'];
    $issue = $_POST['issue'];
    $conn->query("INSERT INTO maintenance_requests (tenant_id, room_id, issue, request_date, status) VALUES ($tenant_id, $room_id, '$issue', NOW(), 'pending')");
    header("Location: reports.php");
    exit;
}

// Admin และ Staff: อัปเดตสถานะ
if (($user_role == 'admin' || $user_role == 'staff') && isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    $assigned_staff_id = $_POST['assigned_staff_id'];
    $conn->query("UPDATE maintenance_requests SET status = '$status', assigned_staff_id = $assigned_staff_id WHERE id = $request_id");
    header("Location: reports.php");
    exit;
}

// ดึงข้อมูลรายการแจ้งซ่อม
$requests = $conn->query("SELECT m.*, u.full_name, r.room_number FROM maintenance_requests m JOIN users u ON m.tenant_id = u.id JOIN rooms r ON m.room_id = r.id");
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
        <h2 class="mb-4">แจ้งซ่อม</h2>

        <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"> <?= $_SESSION['message'];
                                                unset($_SESSION['message']); ?> </div>
        <?php endif; ?>

        <?php if ($user_role == 'tenant'): ?>
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label class="form-label">Room ID:</label>
                <input type="number" name="room_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Issue:</label>
                <textarea name="issue" class="form-control" required></textarea>
            </div>
            <button type="submit" name="submit_request" class="btn btn-primary">Submit</button>
        </form>
        <?php endif; ?>

        <?php if ($user_role == 'admin'): ?>
        <h3>Manage Requests</h3>
        <table class="table table-bordered">
            <tr>
                <!-- <th>ID</th> -->
                <th>Tenant</th>
                <th>Room</th>
                <th>Issuem</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $requests->fetch_assoc()): ?>
            <tr>
                <!-- <td><?= $row['id'] ?></td> -->
                <td><?= $row['full_name'] ?></td>
                <td><?= $row['room_number'] ?></td>
                <td><?= $row['issue'] ?></td>
                <td><?= $row['request_date'] ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                        <select name="status" class="form-select">
                            <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending
                            </option>
                            <option value="in_progress" <?= $row['status'] == 'in_progress' ? 'selected' : '' ?>>In
                                Progress
                            </option>
                            <option value="completed" <?= $row['status'] == 'completed' ? 'selected' : '' ?>>Completed
                            </option>
                        </select>
                        <select name="assigned_staff_id" class="form-control mt-2">
                            <option value="">เลือกผู้ดูแล</option>
                            <option value="1" <?= $row['assigned_staff_id'] == 1 ? 'selected' : '' ?>>Admin</option>
                            <option value="2" <?= $row['assigned_staff_id'] == 2 ? 'selected' : '' ?>>Staff</option>
                        </select>
                        <button type="submit" name="update_status" class="btn btn-success mt-2">Update</button>
                    </form>
                </td>
                <td>
                    <a href="reports.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php endif; ?>

    </div>
    
    <br><br><br><br><br><br><br>



    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
    </div>
</body>

</html>
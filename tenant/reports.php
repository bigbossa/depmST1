<?php
include("../config/session.php");
include("../config/database.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role']; // 'admin' or 'tenant'

// Fetch user data
$user_sql = "SELECT full_name FROM users WHERE id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();
$full_name = $user_data['full_name'] ?? 'Guest';
$stmt->close();

// Fetch room number for tenant
if ($user_role == 'tenant') {
    $room_sql = "SELECT id, room_number FROM rooms WHERE tenant_id = ?";
    $stmt = $conn->prepare($room_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $room_result = $stmt->get_result();
    $room_data = $room_result->fetch_assoc();
    $room_id = $room_data['id'] ?? '';
    $room_number = $room_data['room_number'] ?? 'Not Assigned';
    $stmt->close();
}

// Tenant: Submit maintenance request
if ($user_role == 'tenant' && isset($_POST['submit_request'])) {
    $issue = $_POST['issue'];
    $stmt = $conn->prepare("INSERT INTO maintenance_requests (tenant_id, room_id, issue, request_date, status) VALUES (?, ?, ?, NOW(), 'pending')");
    $stmt->bind_param("iis", $user_id, $room_id, $issue);
    if ($stmt->execute()) {
        $_SESSION['message'] = "ส่งคำขอแจ้งซ่อมสำเร็จ!";
    }
    $stmt->close();
    header("Location: reports.php");
    exit();
}

// Admin: Delete maintenance request
if ($user_role == 'admin' && isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM maintenance_requests WHERE id = $delete_id");
    $_SESSION['message'] = "ลบคำขอแจ้งซ่อมสำเร็จ!";
    header("Location: reports.php");
    exit();
}

// Admin: Update status
if ($user_role == 'admin' && isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    $assigned_staff_id = $_POST['assigned_staff_id'];
    $conn->query("UPDATE maintenance_requests SET status = '$status', assigned_staff_id = $assigned_staff_id WHERE id = $request_id");
    $_SESSION['message'] = "อัปเดตสถานะสำเร็จ!";
    header("Location: reports.php");
    exit();
}

// Fetch maintenance requests
if ($user_role == 'tenant') {
    $requests_sql = "SELECT m.*, u.full_name, r.room_number 
                      FROM maintenance_requests m 
                      JOIN users u ON m.tenant_id = u.id 
                      JOIN rooms r ON m.room_id = r.id 
                      WHERE m.tenant_id = ?";
    $stmt = $conn->prepare($requests_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $requests = $stmt->get_result();
} else {
    // For admin, fetch all requests
    $requests = $conn->query("SELECT m.*, u.full_name, r.room_number 
                              FROM maintenance_requests m 
                              JOIN users u ON m.tenant_id = u.id 
                              JOIN rooms r ON m.room_id = r.id");
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
    body {
        background-color: #f7f7f7;
    }

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

        <!-- Tenant Section -->
        <?php if ($user_role == 'tenant'): ?>
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label class="form-label">Room Number:</label>
                <input type="text" value="<?= $room_number ?>" class="form-control" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Issue:</label>
                <textarea name="issue" class="form-control" required></textarea>
            </div>
            <button type="submit" name="submit_request" class="btn btn-primary">Submit</button>
        </form>

        <h3>รายการแจ้งซ่อมของฉัน</h3>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Room</th>
                <th>Issue</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $requests->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['room_number'] ?></td>
                <td><?= $row['issue'] ?></td>
                <td><?= $row['request_date'] ?></td>
                <td>
                    <?php
                            $status_class = '';
                            $status_message = '';
                            if ($row['status'] == 'pending') {
                                $status_class = 'bg-danger';
                                $status_message = 'ส่งเรื่อง';
                            } elseif ($row['status'] == 'in_progress') {
                                $status_class = 'bg-warning';
                                $status_message = 'กำลังดำเดินการ';
                            } elseif ($row['status'] == 'completed') {
                                $status_class = 'bg-success';
                                $status_message = 'ดำเนินการเสร็จสิ้น';
                            }
                            ?>
                    <span class="badge <?= $status_class ?>"><?= $status_message ?></span>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php endif; ?>

        <!-- Admin Section -->
        <?php if ($user_role == 'admin'): ?>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Tenant</th>
                <th>Room</th>
                <th>Issue</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $requests->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['full_name'] ?></td>
                <td><?= $row['room_number'] ?></td>
                <td><?= $row['issue'] ?></td>
                <td><?= $row['request_date'] ?></td>
                <td>
                    <?php
                            $status_class = '';
                            $status_message = '';
                            if ($row['status'] == 'pending') {
                                $status_class = 'bg-warning';
                                $status_message = 'ส่งเรื่อง';
                            } elseif ($row['status'] == 'in_progress') {
                                $status_class = 'bg-danger';
                                $status_message = 'กำลังดำเดินการ';
                            } elseif ($row['status'] == 'completed') {
                                $status_class = 'bg-success';
                                $status_message = 'ดำเนินการเสร็จสิ้น';
                            }
                            ?>
                    <span class="badge <?= $status_class ?>"><?= $status_message ?></span>
                </td>
                <td>
                    <form method="post">
                        <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                        <select name="status" class="form-select">
                            <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending
                            </option>
                            <option value="in_progress" <?= $row['status'] == 'in_progress' ? 'selected' : '' ?>>In
                                Progress</option>
                            <option value="completed" <?= $row['status'] == 'completed' ? 'selected' : '' ?>>Completed
                            </option>
                        </select>
                        <input type="number" name="assigned_staff_id" class="form-control mt-2" placeholder="Staff ID"
                            value="<?= $row['assigned_staff_id'] ?>">
                        <button type="submit" name="update_status" class="btn btn-success mt-2">Update</button>
                    </form>
                </td>
                <td>
                    <a href="maintenance_requests.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
    </div>
</body>

</html>
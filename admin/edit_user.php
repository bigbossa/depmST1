<?php
include("../config/session.php");
include("../config/database.php");

if (!isset($_GET["id"])) {
    header("Location: manage_users.php");
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

$user_id = $_GET["id"];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize array to store fields that have changed
    $updates = array();
    $types = "";
    $params = array();

    // Check each field for changes
    if ($_POST["full_name"] != $user['full_name']) {
        $updates[] = "full_name = ?";
        $types .= "s";
        $params[] = $_POST["full_name"];
    }

    if ($_POST["phone"] != $user['phone']) {
        $updates[] = "phone = ?";
        $types .= "s";
        $params[] = $_POST["phone"];
    }

    if ($_POST["email"] != $user['email']) {
        $updates[] = "email = ?";
        $types .= "s";
        $params[] = $_POST["email"];
    }

    if ($_POST["role"] != $user['role']) {
        $updates[] = "role = ?";
        $types .= "s";
        $params[] = $_POST["role"];
    }

    if ($_POST["IDCard"] != $user['IDCard']) {
        $updates[] = "IDCard = ?";
        $types .= "s";
        $params[] = $_POST["IDCard"];
    }

    // Handle image file upload
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $img_file = $_FILES['img']['name'];
        $img_tmp = $_FILES['img']['tmp_name'];
        $img_destination = "../assets/Data/img/" . $img_file;

        if (move_uploaded_file($img_tmp, $img_destination)) {
            $updates[] = "img = ?";
            $types .= "s";
            $params[] = $img_file;
        }
    }

    // Handle charter file upload only if new file is selected
    if (isset($_FILES['charter']) && $_FILES['charter']['error'] == 0) {
        $charter_file = $_FILES['charter']['name'];
        $charter_tmp = $_FILES['charter']['tmp_name'];
        $charter_destination = "../assets/Data/file_Charter/" . $charter_file;

        if (move_uploaded_file($charter_tmp, $charter_destination)) {
            $updates[] = "charter = ?";
            $types .= "s";
            $params[] = $charter_file;
        }
    }

    // Execute update query if there are changes
    if (!empty($updates)) {
        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
        $types .= "i"; // Add type for user_id
        $params[] = $user_id; // Add user_id to parameters

        $stmt = $conn->prepare($sql);
        // Bind parameters dynamically
        $bind_params = array($types);
        foreach ($params as $key => $value) {
            $bind_params[] = &$params[$key];
        }
        call_user_func_array(array($stmt, 'bind_param'), $bind_params);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "ข้อมูลผู้ใช้ถูกอัพเดทเรียบร้อยแล้ว";
            $show_success_alert = true; // เพิ่มตัวแปรเพื่อบอกว่าต้องแสดง alert
        } else {
            $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการอัพเดทข้อมูล: " . $conn->error;
            $show_error_alert = true; // เพิ่มตัวแปรเพื่อบอกว่าต้องแสดง alert error
        }
        $stmt->close();
    }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <?php
    include "../assets/assets/admin_sidebar.php";
    ?>

    <!-- Content -->
    <div class="content">
        <h2>แก้ไขข้อมูลผู้ใช้</h2>

        <br>
        <form method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="full_name" class="form-label">ชื่อ-นามสกุล:</label>
                <input type="text" class="form-control" name="full_name" id="full_name"
                    value="<?= $user['full_name'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="IDCard" class="form-label">เลขบัตรประชาชน:</label>
                <input type="text" class="form-control" name="IDCard" id="IDCard" value="<?= $user['IDCard'] ?>"
                    required>
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

            <div class="mb-3">
                <label for="charter" class="form-label">สัญญาเช่า:</label>
                <?php if (!empty($user['charter']) && file_exists("../assets/Data/file_Charter/" . $user['charter'])): ?>
                <div class="mb-2">
                    <a href="../assets/Data/file_Charter/<?= htmlspecialchars($user['charter']) ?>" download
                        class="btn btn-info btn-sm">
                        <i class="fas fa-file-download"></i> ดาวน์โหลด Charter ปัจจุบัน
                    </a>
                </div>
                <?php endif; ?>
                <input type="file" class="form-control" name="charter">
            </div>

            <button type="submit" class="btn btn-primary">บันทึก</button>
            <button class="btn btn-warning float-end">
                <a href="manage_users.php" style="color: white; text-decoration: none;">กลับไปที่หน้าจัดการผู้ใช้งาน</a>
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
    </div>

    <!-- ย้าย script มาไว้ตรงนี้แทน -->
    <?php if (isset($show_success_alert) && $show_success_alert): ?>
    <script>
    Swal.fire({
        title: 'สำเร็จ!',
        text: 'อัปเดตข้อมูลเรียบร้อยแล้ว',
        icon: 'success',
        confirmButtonText: 'ตกลง'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'manage_users.php';
        }
    });
    </script>
    <?php endif; ?>

    <?php if (isset($show_error_alert) && $show_error_alert): ?>
    <script>
    Swal.fire({
        title: 'ข้อผิดพลาด!',
        text: 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล',
        icon: 'error',
        confirmButtonText: 'ตกลง'
    });
    </script>
    <?php endif; ?>
</body>

</html>
<?php
include("../config/session.php");
include("../config/database.php");

// ดึงข้อมูลผู้ใช้ทั้งหมดจากฐานข้อมูล
$result = $conn->query("SELECT id, username, full_name, phone, email, role, IDCard, img, charter FROM users");

// ลบผู้ใช้
if (isset($_GET["delete"])) {
    $user_id = $_GET["delete"];
    $conn->query("DELETE FROM users WHERE id = $user_id");
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
    include "../assets/assets/admin_sidebar.php";
    ?>

    <!-- Content -->
    <div class="content">
        <h2 class="mb-4">จัดการผู้ใช้</h2>
        <a href="add_user.php" class="btn btn-primary mb-3">เพิ่มผู้ใช้ใหม่</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>รูปประจำตัว</th>
                    <th>ชื่อผู้ใช้</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>เลขบัตรประชาชน</th>
                    <th>เบอร์โทร</th>
                    <th>Email</th>
                    <th>บทบาท</th>
                    <th>สัญญาเช่า</th>
                    <th>การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <?php if (!empty($row["img"])): ?>
                        <img src="../assets/Data/img/<?= $row["img"] ?>" alt="รูปประจำตัว"
                            style="width: 50px; height: 50px; object-fit: cover;">
                        <?php else: ?>
                        <img src="../assets/images/home.png" alt="รูปเริ่มต้น"
                            style="width: 50px; height: 50px; object-fit: cover;">
                        <?php endif; ?>
                    </td>
                    <td><?= $row["username"] ?></td>
                    <td><?= $row["full_name"] ?></td>
                    <td><?= $row["IDCard"] ?></td>
                    <td><?= $row["phone"] ?></td>
                    <td><?= $row["email"] ?></td>
                    <td><?= ucfirst($row["role"]) ?></td>
                    <td>
                        <?php if (!empty($row["charter"]) && $row["charter"] != ""): ?>
                        <a href="../assets/Data/file_Charter/<?= $row["charter"] ?>" download
                            class="btn btn-info btn-sm">
                            <i class="fas fa-file-download"></i> ดาวน์โหลด สัญญาเช่า
                        </a>
                        <?php else: ?>
                        <span class="text-muted">ไม่มีไฟล์</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#viewModal<?= $row["id"] ?>">ดู</button> |
                        <a href="edit_user.php?id=<?= $row["id"] ?>" class="btn btn-warning btn-sm">แก้ไข</a> |
                        <a href="manage_users.php?delete=<?= $row["id"] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบ?');">ลบ</a>

                        <!-- Modal สำหรับดูข้อมูล -->
                        <div class="modal fade" id="viewModal<?= $row["id"] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">ข้อมูลผู้ใช้: <?= $row["full_name"] ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center mb-3">
                                            <?php if (!empty($row["img"])): ?>
                                            <img src="../assets/Data/img/<?= $row["img"] ?>" alt="รูปประจำตัว"
                                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                            <?php else: ?>
                                            <img src="../assets/images/home.png" alt="รูปเริ่มต้น"
                                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                            <?php endif; ?>
                                        </div>
                                        <p><strong>ชื่อผู้ใช้:</strong> <?= $row["username"] ?></p>
                                        <p><strong>ชื่อ-นามสกุล:</strong> <?= $row["full_name"] ?></p>
                                        <p><strong>เลขบัตรประชาชน:</strong> <?= $row["IDCard"] ?></p>
                                        <p><strong>เบอร์โทร:</strong> <?= $row["phone"] ?></p>
                                        <p><strong>Email:</strong> <?= $row["email"] ?></p>
                                        <p><strong>บทบาท:</strong> <?= ucfirst($row["role"]) ?></p>
                                        <?php if (!empty($row["charter"]) && $row["charter"] != ""): ?>
                                        <p><strong>สัญญาเช่า:</strong>
                                            <a href="../assets/Data/file_Charter/<?= $row["charter"] ?>" download>
                                                <i class="fas fa-file-download"></i> ดาวน์โหลดสัญญาเช่า
                                            </a>
                                        </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">ปิด</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <br><br><br><br><br><br><br><br> <br><br><br><br>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
    </div>

    <!-- เพิ่ม Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
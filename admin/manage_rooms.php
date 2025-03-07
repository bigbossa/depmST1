<?php
// เริ่ม session และเชื่อมต่อฐานข้อมูล
session_start();
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

// ดึงข้อมูลห้องพักจากฐานข้อมูล
$result = $conn->query("
    SELECT r.*, u.full_name as tenant_name 
    FROM rooms r 
    LEFT JOIN users u ON r.tenant_id = u.id
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการห้องพัก</title>
    <link rel="icon" type="image/png" href="../assets/images/home.png">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Sidebar -->
    <?php include "../assets/assets/admin_sidebar.php"; ?>

    <!-- Content -->
    <div class="content">
        <h2 class="mb-4">รายการห้องพัก</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>หมายเลขห้อง</th>
                    <th>ประเภท</th>
                    <th>ราคา</th>
                    <th>สถานะ</th>
                    <th>ผู้เช่า</th>
                    <th>วันที่เข้าพัก</th>
                    <th>วันที่สิ้นสุด</th>
                    <th>การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row["room_number"]) ?></td>
                    <td><?= htmlspecialchars($row["type"] ?? 'ไม่มีข้อมูล') ?></td>
                    <td><?= number_format($row["price"], 2) ?> บาท</td>
                    <td>
                        <?php
                            $status_labels = [
                                'available' => 'ว่าง',
                                'reserved' => 'ซ่อมแซม',
                                'occupied' => 'มีผู้เช่า'
                            ];
                            echo htmlspecialchars($status_labels[$row['status']] ?? 'ไม่ทราบสถานะ');
                            ?>
                    </td>
                    <td><?= htmlspecialchars($row["tenant_name"] ?? 'ไม่มีผู้เช่า') ?></td>
                    <td><?= $row["Date_of_Stay"] ? date('d/m/Y', strtotime($row["Date_of_Stay"])) : '-' ?></td>
                    <td><?= $row["Expiration_Date"] ? date('d/m/Y', strtotime($row["Expiration_Date"])) : '-' ?></td>
                    <td>
                        <a href="edit_room.php?id=<?= $row["id"] ?>" class="btn btn-warning">แก้ไข</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. |
            <a href="#">Privacy Policy</a> |
            <a href="#">Terms of Service</a>
        </p>
    </div>
</body>

</html>
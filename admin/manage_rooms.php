<?php
include("../config/session.php");
include("../config/database.php");

// ดึงข้อมูลห้องพัก
$result = $conn->query("SELECT * FROM rooms");
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
    <title>จัดการห้องพัก</title>
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
        <h2 class="mb-4">รายการห้องพัก</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>หมายเลขห้อง</th>
                    <th>ประเภท</th>
                    <th>ราคา</th>
                    <th>สถานะ</th>
                    <th>ผู้เช่า</th>
                    <th>การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row["room_number"] ?></td>
                    <td>
                        <?php 
                            // ตรวจสอบและแสดงประเภทห้องที่มีค่า
                            echo $row["type"] ? $row["type"] : 'ไม่มีข้อมูล';
                            ?>
                    </td>
                    <td><?= $row["price"] ?> บาท</td>
                    <td>
                        <?php
    $status_labels = [
        'available' => 'ว่าง',
        'reserved' => 'ซ่อมแซม',
        'occupied' => 'มีผู้เช่า'
    ];
    echo $status_labels[$row['status']] ?? 'ไม่ทราบสถานะ';
    ?>
                    </td>
                    <td>
                        <?php
                            // แสดงผู้เช่าหากมี tenant_id
                            if (!empty($row["tenant_id"])) {
                                // สามารถดึงข้อมูลผู้เช่าจากตารางอื่น ๆ (เช่น users) ได้
                                $tenant_id = $row["tenant_id"];
                                $tenant_result = $conn->query("SELECT full_name FROM users WHERE id = '$tenant_id'");
                                $tenant = $tenant_result->fetch_assoc();
                                echo $tenant ? $tenant["full_name"] : 'ไม่มีผู้เช่า';
                            } else {
                                echo 'ไม่มีผู้เช่า';
                            }
                            ?>
                    </td>
                    <td><a href="edit_room.php?id=<?= $row["id"] ?>" class="btn btn-warning">แก้ไข</a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
    </div>
</body>

</html>
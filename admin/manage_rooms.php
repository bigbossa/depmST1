<?php
include("../config/session.php");
include("../config/database.php");

// ดึงข้อมูลห้องพัก
$result = $conn->query("SELECT * FROM rooms");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการห้องพัก</title>
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
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </div>
</body>

</html>

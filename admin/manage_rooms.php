<?php
include("../config/session.php");
include("../config/database.php");

// ดึงข้อมูลห้องพัก
$result = $conn->query("SELECT * FROM rooms");
?>
<!DOCTYPE html>
<html>

<head>
    <title>จัดการห้องพัก</title>
</head>

<body>
    <h2>รายการห้องพัก</h2>
    <table border="1">
        <tr>
            <th>หมายเลขห้อง</th>
            <th>ประเภท</th>
            <th>ราคา</th>
            <th>สถานะ</th>
            <th>ผู้เช่า</th>
            <th>การกระทำ</th>
        </tr>
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
            <td><?= $row["status"] ?></td>
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
            <td><a href="edit_room.php?id=<?= $row["id"] ?>">แก้ไข</a></td>
        </tr>
        <?php } ?>
    </table>
</body>

</html>
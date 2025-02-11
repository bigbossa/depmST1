<?php
include("../config/session.php");
include("../config/database.php");

$result = $conn->query("SELECT * FROM maintenance_requests");
?>
<!DOCTYPE html>
<html>

<head>
    <title>จัดการแจ้งซ่อม</title>
</head>

<body>
    <h2>รายการแจ้งซ่อม</h2>
    <table border="1">
        <tr>
            <th>รหัส</th>
            <th>ผู้แจ้ง</th>
            <th>ห้อง</th>
            <th>ปัญหา</th>
            <th>สถานะ</th>
            <th>การกระทำ</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><?= $row["tenant_id"] ?></td>
            <td><?= $row["room_id"] ?></td>
            <td><?= $row["issue"] ?></td>
            <td><?= $row["status"] ?></td>
            <td><a href="update_status.php?id=<?= $row["id"] ?>">อัปเดต</a></td>
        </tr>
        <?php } ?>
    </table>
</body>

</html>
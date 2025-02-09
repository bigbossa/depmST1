<?php
include("../config/session.php");
include("../config/database.php");

$user_id = $_SESSION["user_id"];
$result = $conn->query("SELECT * FROM bills WHERE tenant_id = $user_id");
?>
<!DOCTYPE html>
<html>

<head>
    <title>ค่าใช้จ่ายของฉัน</title>
</head>

<body>
    <h2>ค่าใช้จ่ายของฉัน</h2>
    <table border="1">
        <tr>
            <th>เดือน</th>
            <th>ปี</th>
            <th>ค่าเช่า</th>
            <th>ค่าน้ำ</th>
            <th>ค่าไฟ</th>
            <th>รวม</th>
            <th>สถานะ</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row["month"] ?></td>
            <td><?= $row["year"] ?></td>
            <td><?= $row["rent_fee"] ?> บาท</td>
            <td><?= $row["water_bill"] ?> บาท</td>
            <td><?= $row["electricity_bill"] ?> บาท</td>
            <td><?= $row["total"] ?> บาท</td>
            <td><?= ($row["paid_status"] == "paid") ? "ชำระแล้ว" : "ค้างชำระ" ?></td>
        </tr>
        <?php } ?>
    </table>
</body>

</html>
<?php
include("../config/session.php");
include("../config/database.php");

// รับค่าปีปัจจุบัน
$currentYear = date("Y");

// ดึงข้อมูลรายรับรายจ่ายรายเดือน
$query = "SELECT 
            MONTH(payment_date) AS month, 
            YEAR(payment_date) AS year,
            SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) AS total_income,
            SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expense
          FROM transactions
          WHERE YEAR(payment_date) = ?
          GROUP BY MONTH(payment_date), YEAR(payment_date)
          ORDER BY MONTH(payment_date)";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $currentYear);
$stmt->execute();
$result = $stmt->get_result();

$dataPoints = [];
while ($row = $result->fetch_assoc()) {
    $dataPoints[] = [
        "month" => $row["month"],
        "income" => $row["total_income"],
        "expense" => $row["total_expense"]
    ];
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายรับรายจ่าย</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h2>รายรับรายจ่าย ปี <?= $currentYear ?></h2>

    <table border="1">
        <tr>
            <th>เดือน</th>
            <th>รายรับ (บาท)</th>
            <th>รายจ่าย (บาท)</th>
            <th>คงเหลือ (บาท)</th>
        </tr>
        <?php 
        foreach ($dataPoints as $data) { 
            $balance = $data["income"] - $data["expense"];
        ?>
        <tr>
            <td><?= $data["month"] ?></td>
            <td><?= number_format($data["income"], 2) ?></td>
            <td><?= number_format($data["expense"], 2) ?></td>
            <td><?= number_format($balance, 2) ?></td>
        </tr>
        <?php } ?>
    </table>

    <p><a href="generate_graph.php">ดูรายงานแบบกราฟ</a></p>
</body>

</html>
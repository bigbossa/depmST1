<?php
include("../config/session.php");
include("../config/database.php");

// รับค่าปีปัจจุบัน
$currentYear = date("Y");

// ดึงข้อมูลรายรับรายจ่ายรายเดือน
$query = "SELECT 
            MONTH(date_recorded) AS month, 
            YEAR(date_recorded) AS year,
            SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) AS total_income,
            SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expense
          FROM transactions
          WHERE YEAR(date_recorded) = ?
          GROUP BY MONTH(date_recorded), YEAR(date_recorded)";


$stmt = $conn->prepare($query);
$stmt->bind_param("i", $currentYear);
$stmt->execute();
$result = $stmt->get_result();

// เก็บข้อมูลสำหรับกราฟ
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
<html>

<head>
    <title>รายงานรายรับรายจ่าย</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <h2>รายงานรายรับ-รายจ่าย ปี <?= $currentYear ?></h2>

    <table border="1">
        <tr>
            <th>เดือน</th>
            <th>รายรับ (บาท)</th>
            <th>รายจ่าย (บาท)</th>
        </tr>
        <?php foreach ($dataPoints as $data) { ?>
        <tr>
            <td><?= $data["month"] ?></td>
            <td><?= number_format($data["income"], 2) ?></td>
            <td><?= number_format($data["expense"], 2) ?></td>
        </tr>
        <?php } ?>
    </table>

    <canvas id="reportChart"></canvas>
    <script>
    var ctx = document.getElementById('reportChart').getContext('2d');
    var chartData = {
        labels: [<?php foreach ($dataPoints as $data) echo "'เดือน " . $data["month"] . "',"; ?>],
        datasets: [{
                label: 'รายรับ (บาท)',
                data: [<?php foreach ($dataPoints as $data) echo $data["income"] . ","; ?>],
                backgroundColor: 'rgba(0, 128, 0, 0.5)'
            },
            {
                label: 'รายจ่าย (บาท)',
                data: [<?php foreach ($dataPoints as $data) echo $data["expense"] . ","; ?>],
                backgroundColor: 'rgba(255, 0, 0, 0.5)'
            }
        ]
    };

    new Chart(ctx, {
        type: 'bar',
        data: chartData
    });
    </script>
</body>

</html>
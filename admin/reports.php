<?php
include("../config/session.php");
include("../config/database.php");

// Get the current year
$currentYear = date("Y");

// Query to fetch income and expense data for the current year
$query = "SELECT 
            MONTH(date_recorded) AS month, 
            YEAR(date_recorded) AS year,
            SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) AS total_income,
            SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expense
          FROM finance
          WHERE YEAR(date_recorded) = ?
          GROUP BY MONTH(date_recorded), YEAR(date_recorded)";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $currentYear);
$stmt->execute();
$result = $stmt->get_result();

// Prepare data for chart display
$dataPoints = [];
$totalIncome = 0;
$totalExpense = 0;

$months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

for ($i = 1; $i <= 12; $i++) {
    $dataPoints[$i] = ['month' => $months[$i-1], 'income' => 0, 'expense' => 0];  // Initialize months with 0
}

while ($row = $result->fetch_assoc()) {
    $dataPoints[$row["month"]]["income"] = $row["total_income"];
    $dataPoints[$row["month"]]["expense"] = $row["total_expense"];
    $totalIncome += $row["total_income"];
    $totalExpense += $row["total_expense"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานรายรับรายจ่าย</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            margin-left: 120px;
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
    </style>
</head>

<body>
<div class="sidebar">
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_rooms.php">Manage Rooms</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="reports.php">Report</a>
        <a href="../public/logout.php">Logout</a>
    </div>

    <div class="content">
        <h2 class="mb-4">รายงานรายรับ-รายจ่าย ปี <?= $currentYear ?></h2>

        <!-- รายงานรายรับรายจ่าย -->
        <div class="mb-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>เดือน</th>
                        <th>รายรับ (บาท)</th>
                        <th>รายจ่าย (บาท)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataPoints as $data) { ?>
                        <tr>
                            <td><?= $data["month"] ?></td>
                            <td><?= number_format($data["income"], 2) ?></td>
                            <td><?= number_format($data["expense"], 2) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- แผนภูมิแท่ง -->
        <h3>แผนภูมิแท่ง</h3>
        <canvas id="barChart" class="mb-4"></canvas>

        <!-- แผนภูมิวงกลม -->
        <h3>แผนภูมิวงกลม</h3>
        <canvas id="pieChart" class="mb-4"></canvas>

    </div>

    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </div>

    <script>
        // Bar Chart (รายรับและรายจ่ายรายเดือน)
        var ctxBar = document.getElementById('barChart').getContext('2d');
        var barChartData = {
            labels: [<?php foreach ($dataPoints as $data) echo "'" . $data["month"] . "',"; ?>],
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

        new Chart(ctxBar, {
            type: 'bar',
            data: barChartData
        });

        // Pie Chart (รายรับและรายจ่ายทั้งหมด)
        var ctxPie = document.getElementById('pieChart').getContext('2d');
        var pieChartData = {
            labels: ['รายรับ', 'รายจ่าย'],
            datasets: [{
                data: [<?php echo $totalIncome; ?>, <?php echo $totalExpense; ?>],
                backgroundColor: ['rgba(0, 128, 0, 0.5)', 'rgba(255, 0, 0, 0.5)']
            }]
        };

        new Chart(ctxPie, {
            type: 'pie',
            data: pieChartData
        });
    </script>
</body>

</html>

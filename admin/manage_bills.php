<?php
include("../config/session.php");
include("../config/database.php");// ไฟล์เชื่อมต่อฐานข้อมูล

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
    $_SESSION['full_name'] = $full_name; // อัปเดตค่า session
    $stmt->close();
} else {
    $full_name = 'Guest';
}

// ดึงข้อมูลรายรับ-รายจ่ายแต่ละเดือน
$finance_sql = "SELECT MONTH(date) as month, YEAR(date) as year, SUM(income) as total_income, SUM(expense) as total_expense FROM finance GROUP BY YEAR(date), MONTH(date) ORDER BY YEAR(date), MONTH(date)";
$finance_result = $conn->query($finance_sql);
$finance_data = [];
while ($row = $finance_result->fetch_assoc()) {
    $finance_data[] = $row;
}

// คำนวณยอดรวมทั้งปี
$total_income_year = 0;
$total_expense_year = 0;
foreach ($finance_data as $data) {
    $total_income_year += $data['total_income'];
    $total_expense_year += $data['total_expense'];
}

// รายชื่อเดือน
$months = [
    "1" => "มกราคม",
    "2" => "กุมภาพันธ์",
    "3" => "มีนาคม",
    "4" => "เมษายน",
    "5" => "พฤษภาคม",
    "6" => "มิถุนายน",
    "7" => "กรกฎาคม",
    "8" => "สิงหาคม",
    "9" => "กันยายน",
    "10" => "ตุลาคม",
    "11" => "พฤศจิกายน",
    "12" => "ธันวาคม"
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bills</title>
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

    .sidebar img {
        display: block;
        margin: 0 auto;
        border-radius: 10px;
    }

    .content {
        margin-left: 260px;
        padding: 20px;
    }

    .footer {
        margin-left: 190px;
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

    .finance-summary {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }

    .finance-summary .card {
        flex: 1;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        background-color: #f8f9fa;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .finance-summary .card h3 {
        margin-bottom: 10px;
        font-size: 18px;
    }

    .finance-summary .card p {
        font-size: 24px;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php
    include "../assets/assets/admin_sidebar.php";
    ?>

    <div class="content">
        <h2 class="mb-4">Total Salary</h2>

        <!-- สรุปยอดรวมทั้งปี -->
        <div class="finance-summary">
            <div class="card">
                <h3>รายรับทั้งปี</h3>
                <p><?php echo number_format($total_income_year, 2); ?> บาท</p>
            </div>
            <div class="card">
                <h3>รายจ่ายทั้งปี</h3>
                <p><?php echo number_format($total_expense_year, 2); ?> บาท</p>
            </div>
        </div>
        <br>

        <!-- ตารางแสดงรายรับ-รายจ่ายแต่ละเดือน -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>เดือน</th>
                    <th>ปี</th>
                    <th>รายรับ</th>
                    <th>รายจ่าย</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($finance_data as $data) : ?>
                <tr>
                    <td><?php echo $months[$data['month']]; ?></td>
                    <td><?php echo $data['year']; ?></td>
                    <td><?php echo number_format($data['total_income'], 2); ?> บาท</td>
                    <td><?php echo number_format($data['total_expense'], 2); ?> บาท</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>

        <!-- กราฟรายรับ-รายจ่ายทั้งปี -->
        <div>
            <div class="row mb-5">
                <div class="col-md-6">
                    <div style="height: 300px;">
                        <h3 class="text-center">แผนภูมิแท่งรายรับ-รายจ่าย</h3>
                        <canvas id="financeChart"></canvas>
                    </div>
                </div>

                <div class="col-md-6">
                    <div style="height: 300px;">
                        <h3 class="text-center">แผนภูมิวงกลมรายรับ-รายจ่าย</h3>
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
    </div>

    <script>
    var financeData = <?php echo json_encode($finance_data); ?>;
    var months = financeData.map(data => 'เดือน ' + data.month + ' ' + data.year);
    var income = financeData.map(data => data.total_income);
    var expense = financeData.map(data => data.total_expense);

    // แผนภูมิแท่ง
    var ctx = document.getElementById('financeChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                    label: 'รายรับ',
                    backgroundColor: 'green',
                    data: income
                },
                {
                    label: 'รายจ่าย',
                    backgroundColor: 'red',
                    data: expense
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true
        }
    });

    // แผนภูมิวงกลม
    var pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['รายรับ', 'รายจ่าย'],
            datasets: [{
                data: [
                    <?php echo $total_income_year; ?>,
                    <?php echo $total_expense_year; ?>
                ],
                backgroundColor: ['green', 'red']
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    </script>

</body>

</html>
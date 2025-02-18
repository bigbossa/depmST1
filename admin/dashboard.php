<?php
include '../config/session.php'; // เริ่ม session
include '../config/database.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

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

// ตรวจสอบว่าตาราง finance มีอยู่หรือไม่
$check_table_sql = "SHOW TABLES LIKE 'finance'";
$check_result = $conn->query($check_table_sql);
if ($check_result->num_rows == 0) {
    die("Error: Table 'finance' does not exist. Please create the table first.");
}

// ดึงข้อมูลห้องพักจากฐานข้อมูล
$sql = "SELECT id, room_number, status FROM rooms";
$result = $conn->query($sql);

// กำหนดเดือนปัจจุบันเริ่มต้น
$current_month = date('m');
if (isset($_GET['month'])) {
    $current_month = $_GET['month'];
}

// ดึงข้อมูลรายรับ-รายจ่ายของเดือนที่เลือก
$finance_sql = "SELECT MONTH(date) as month, SUM(income) as total_income, SUM(expense) as total_expense FROM finance WHERE MONTH(date) = ? GROUP BY MONTH(date)";
$stmt = $conn->prepare($finance_sql);
$stmt->bind_param("i", $current_month);
$stmt->execute();
$finance_result = $stmt->get_result();
$finance_data = [];
while ($row = $finance_result->fetch_assoc()) {
    $finance_data[] = $row;
}
$stmt->close();

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
    <title>Admin Dashboard</title>
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
        width: 100%;

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

    .room-status {
        display: grid;
        grid-template-columns: repeat(8, 0.5fr);
        gap: 10px;
        justify-items: center;
    }

    .room-status .room {
        padding: 10px;
        border-radius: 5px;
        color: white;
        text-align: center;
        width: 100px;
    }

    .room-status .room.available {
        background-color: #198754;
    }

    .room-status .room.occupied {
        background-color: #dc3545;
    }

    .room-status .room.maintenance {
        background-color: #ffc107;
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

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .sidebar a {
            text-align: center;
            padding: 10px;
        }

        .content {
            margin-left: 0;
            padding: 10px;
        }

        .room-status {
            grid-template-columns: repeat(2, 1fr);
        }

        .finance-summary {
            flex-direction: column;
        }
    }

    @media (max-width: 480px) {
        .room-status {
            grid-template-columns: repeat(1, 1fr);
        }

        .finance-summary .card {
            margin-bottom: 10px;
        }
    }
    </style>
</head>

<body>

    <?php
    include "../assets/assets/admin_sidebar.php";
    ?>

    <div class="content">
        <h2>Admin Dashboard</h2>
        <!-- ห้องพักสถานะ -->
        <div class="d-flex  gap-1 my-3">
            <div class="bg-success text-white p-3 rounded"></div>
            <div class=" p-1 rounded">ห้องว่าง</div>
            <div class="bg-danger text-white p-3 rounded"></div>
            <div class=" p-1 rounded">มีผู้เช่า</div>
            <div class="bg-warning text-white p-3 rounded"></div>
            <div class=" p-1 rounded">ซ่อมบำรุง</div>
        </div>

        <!-- แสดงห้องพัก -->
        <div class="room-status">
            <?php while ($room = $result->fetch_assoc()) : ?>
            <div class="room <?php echo $room['status'] == 'available' ? 'available' : ($room['status'] == 'occupied' ? 'occupied' : 'maintenance'); ?>"
                onclick="showRoomDetails(<?php echo $room['id']; ?>, '<?php echo $room['room_number']; ?>', '<?php echo $room['status']; ?>')">
                ห้อง <?php echo $room['room_number']; ?>
            </div>
            <?php endwhile; ?>
        </div>
        <br>
        <!-- เลือกเดือน -->
        <form method="GET" class="mb-3">
            <label for="month">เลือกเดือน:</label>
            <select name="month" id="month" class="form-select w-auto d-inline" onchange="this.form.submit()">
                <?php foreach ($months as $num => $name) : ?>
                <option value="<?php echo $num; ?>" <?php echo ($current_month == $num) ? 'selected' : ''; ?>>
                    <?php echo $name; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- สรุปข้อมูลรายรับ-รายจ่าย -->
        <div class="finance-summary">
            <div class="card">
                <h3>รายรับ</h3>
                <p><?php echo number_format($finance_data[0]['total_income'] ?? 0, 2); ?> บาท</p>
            </div>
            <div class="card">
                <h3>รายจ่าย</h3>
                <p><?php echo number_format($finance_data[0]['total_expense'] ?? 0, 2); ?> บาท</p>
            </div>
        </div>
        <br>

        <!-- กราฟรายรับ-รายจ่าย -->
        <div>
            <div class="row mb-5">
                <div class="col-md-6">
                    <div style="height: 300px;">
                        <h3 class="text-center">แผนภูมิแท่ง</h3>
                        <canvas id="financeChart"></canvas>
                    </div>
                </div>

                <div class="col-md-6">
                    <div style="height: 300px;">
                        <h3 class="text-center">แผนภูมิวงกลม</h3>
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 หอพักบ้านพุทธชาติ. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms
                of
                Service</a></p>
    </div>

    <!-- Modal สำหรับแสดงรายละเอียดห้อง -->
    <div class="modal fade" id="roomModal" tabindex="-1" aria-labelledby="roomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roomModalLabel">รายละเอียดห้อง</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="roomDetails">
                    <!-- ข้อมูลห้องจะถูกเพิ่มที่นี่ด้วย JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
    var financeData = <?php echo json_encode($finance_data); ?>;
    var months = financeData.map(data => 'เดือน ' + data.month);
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
                    financeData[0]?.total_income ?? 0,
                    financeData[0]?.total_expense ?? 0
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
    var options = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    };

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
        options: options
    });

    var pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['รายรับ', 'รายจ่าย'],
            datasets: [{
                data: [
                    financeData[0]?.total_income ?? 0,
                    financeData[0]?.total_expense ?? 0
                ],
                backgroundColor: ['green', 'red']
            }]
        },
        options: options
    });
    </script>

    <!-- เพิ่ม Bootstrap JS ก่อน </body> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // เพิ่มฟังก์ชัน JavaScript สำหรับแสดง Modal
    function showRoomDetails(roomId, roomNumber, status) {
        // แปลงสถานะเป็นภาษาไทย
        const statusThai = {
            'available': 'ว่าง',
            'occupied': 'มีผู้เช่า',
            'maintenance': 'กำลังซ่อมบำรุง'
        };

        // ส่ง AJAX request เพื่อดึงข้อมูลห้อง
        fetch(`get_room_details.php?room_id=${roomId}`)
            .then(response => response.json())
            .then(data => {
                let detailsHtml = `
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">ข้อมูลห้องพัก</h5>
                            <p><strong>เลขห้อง:</strong> ${roomNumber}</p>
                            <p><strong>สถานะ:</strong> ${statusThai[status]}</p>
                        </div>
                    </div>
                `;

                // เพิ่มข้อมูลผู้เช่า (ถ้ามี)
                if (data.tenant) {
                    detailsHtml += `
                        <p><strong>ผู้เช่า:</strong> ${data.tenant.name}</p>
                        <p><strong>เบอร์โทร:</strong> ${data.tenant.phone}</p>
                        <p><strong>วันที่เข้าอยู่:</strong> ${data.tenant.move_in_date}</p>
                    `;
                }

                document.getElementById('roomDetails').innerHTML = detailsHtml;
                new bootstrap.Modal(document.getElementById('roomModal')).show();
            })
            .catch(error => console.error('Error:', error));
    }
    </script>

</body>

</html>
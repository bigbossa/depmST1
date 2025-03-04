<?php
$conn = new mysqli("localhost", "root", "", "dormitory_management");

$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");

// เพิ่มฟังก์ชันสำหรับแปลงเดือนเป็นภาษาไทย
function thaiMonth($month)
{
    $thai_months = [
        1 => 'มกราคม',
        2 => 'กุมภาพันธ์',
        3 => 'มีนาคม',
        4 => 'เมษายน',
        5 => 'พฤษภาคม',
        6 => 'มิถุนายน',
        7 => 'กรกฎาคม',
        8 => 'สิงหาคม',
        9 => 'กันยายน',
        10 => 'ตุลาคม',
        11 => 'พฤศจิกายน',
        12 => 'ธันวาคม'
    ];
    return $thai_months[$month];
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตารางกำหนดการ</title>
    <style>
    body {
        background-color: #f5f5f5;
    }

    .container {
        width: 100%;
        margin: 30px auto;
        background: white;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }

    h2 {
        color: #ff6600;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
        word-wrap: break-word;
        max-width: 300px;
    }

    th {
        background: #ff6600;
        color: white;
    }

    .btn {
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .edit {
        background: #009688;
        color: white;
    }

    .delete {
        background: #f44336;
        color: white;
    }

    .add {
        display: block;
        margin: 10px auto;
        text-decoration: none;
        background: #4CAF50;
        color: white;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        width: 150px;
    }
    </style>
</head>

<body>

    <div class="container">
        <h2>ตารางกำหนดการ</h2>
        <a href="../assets/assets/add_event.php" class="add">➕ เพิ่มกำหนดการ</a>

        <table>
            <tr>
                <th>วันที่</th>
                <th>รายละเอียด</th>
                <th>การจัดการ</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php
                        $date = strtotime($row['event_date']);
                        $day = date('d', $date);
                        $month = thaiMonth(date('n', $date));
                        $year = date('Y', $date) + 543; // แปลงเป็นปี พ.ศ.
                        echo "$day $month $year";
                        ?></td>
                <td><?= $row['title'] ?></td>
                <td>
                    <a href="../assets/assets/edit_event.php?id=<?= $row['id'] ?>" class="btn edit">✏️ แก้ไข</a>
                    <a href="../assets/assets/delete_event.php?id=<?= $row['id'] ?>" class="btn delete"
                        onclick="return confirm('ต้องการลบหรือไม่?')">🗑️ ลบ</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>

</html>

<?php $conn->close(); ?>
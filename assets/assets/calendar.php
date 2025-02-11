<?php
// กำหนดค่าเริ่มต้นของเดือนและปีปัจจุบัน
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// หาค่าวันแรกของเดือน และจำนวนวันในเดือน
$firstDayOfMonth = strtotime("$year-$month-01");
$daysInMonth = date('t', $firstDayOfMonth);

// หาชื่อเดือนและวันในสัปดาห์
$monthName = date('F', $firstDayOfMonth);
$dayOfWeek = date('w', $firstDayOfMonth); // ค่าตั้งแต่ 0 (อาทิตย์) ถึง 6 (เสาร์)

// หาปีก่อนหน้า/ถัดไป และเดือนก่อนหน้า/ถัดไป
$prevMonth = $month - 1;
$prevYear = $year;
$nextMonth = $month + 1;
$nextYear = $year;

if ($prevMonth == 0) {
    $prevMonth = 12;
    $prevYear--;
}
if ($nextMonth == 13) {
    $nextMonth = 1;
    $nextYear++;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ปฏิทิน <?php echo "$monthName $year"; ?></title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        table { width: 90%; height:600px; margin: auto; border-collapse: collapse; }
        th, td { width: 14%; padding: 10px; text-align: center; border: 1px solid #ccc; }
        th { background: #f4f4f4; }
        .today { background: yellow; }
        .nav { text-decoration: none; font-size: 18px; margin: 10px; display: inline-block; }
    </style>
</head>
<body>
    <h2>ปฏิทิน <?php echo "$monthName $year"; ?></h2>
    
    <a class="nav" href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>">◀ เดือนก่อนหน้า</a>
    <a class="nav" href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>">เดือนถัดไป ▶</a>

    <table>
        <tr>
            <th>อา</th><th>จ</th><th>อ</th><th>พ</th><th>พฤ</th><th>ศ</th><th>ส</th>
        </tr>
        <tr>
            <?php
            // เริ่มต้นเซลล์ว่างก่อนวันแรกของเดือน
            for ($i = 0; $i < $dayOfWeek; $i++) {
                echo "<td></td>";
            }

            // แสดงวันที่ในเดือน
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $todayClass = ($day == date('j') && $month == date('m') && $year == date('Y')) ? 'today' : '';
                echo "<td class='$todayClass'>$day</td>";

                // ขึ้นบรรทัดใหม่เมื่อถึงวันเสาร์
                if (($dayOfWeek + $day) % 7 == 0) {
                    echo "</tr><tr>";
                }
            }

            // ปิดแถวสุดท้าย
            while (($dayOfWeek + $day - 1) % 7 != 0) {
                echo "<td></td>";
                $day++;
            }
            ?>
        </tr>
    </table>
</body>
</html>

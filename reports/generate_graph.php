<?php
include('../config/database.php');
$query = "SELECT month, SUM(total) AS total_income FROM bills GROUP BY month";
$result = mysqli_query($conn, $query);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="incomeChart"></canvas>
<script>
var ctx = document.getElementById('incomeChart').getContext('2d');
var chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php foreach ($data as $d) echo '"' . $d['month'] . '",'; ?>],
        datasets: [{
            label: 'รายรับรายเดือน',
            data: [<?php foreach ($data as $d) echo $d['total_income'] . ','; ?>],
            backgroundColor: 'blue'
        }]
    }
});
</script>
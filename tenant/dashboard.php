
<?php
include("../config/session.php");

if ($_SESSION['role'] !== 'tenant') {
    header("Location: ../public/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/png" href="../assets/images/home.png">
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
        text-align: left;
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

    .calendar {
        
    }
    </style>
</head>
<body>
    
    <div class="sidebar">
        <img src="../assets/images/home.png" alt="Logo" width="50">
        <center style="color: white;">หอพักบ้านพุธชาติ</center>
        <a href="dashboard.php">Dashboard</a>
        <a href="reports.php">Report</a>
        <a href="../public/logout.php">Logout</a>
    </div>

    <div class="content">
        <h2 class="mb-4" style="text-align: left;">Tenant Dashboard</h2>
    </div>

    <div class="calendar">
        <?php include("../assets/assets/calendar.php"); ?>  
    </div>
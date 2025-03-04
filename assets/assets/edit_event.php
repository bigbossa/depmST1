<?php
$conn = new mysqli("localhost", "root", "", "dormitory_management");
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM events WHERE id=$id");
$event = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขกำหนดการ</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
        background-color: #f5f5f5;
    }

    .container {
        width: 300px;
        margin: 50px auto;
        background: white;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }

    h2 {
        color: #ff6600;
    }

    input,
    button {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    button {
        background: #009688;
        color: white;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background: #00796b;
    }
    </style>
</head>

<body>

    <div class="container">
        <h2>แก้ไขกำหนดการ</h2>
        <form action="update_event.php" method="post">
            <input type="hidden" name="id" value="<?= $event['id'] ?>">
            <label>วันที่:</label>
            <input type="date" name="event_date" value="<?= $event['event_date'] ?>" required>
            <label>รายละเอียด:</label>
            <input type="text" name="title" value="<?= $event['title'] ?>" required>
            <button type="submit">บันทึก</button>
        </form>
    </div>

</body>

</html>

<?php $conn->close(); ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มกำหนดการ</title>
    <style>
    body {
        text-align: center;
        background-color: #f5f5f5;
    }

    .container {
        width: 80%;
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
        width: 80%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    button {
        background: #ff6600;
        color: white;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background: #e65c00;
    }
    </style>
</head>

<body>

    <div class="container">
        <h2>เพิ่มกำหนดการ</h2>
        <form action="save_event.php" method="post">
            <label>วันที่:</label><br>
            <input type="date" name="event_date" required>
            <br>
            <label>รายละเอียด:</label><br>
            <input type="text" name="title" required>
            <br>
            <button type="submit">บันทึก</button>
        </form>
        <br>
        <button onclick="window.location.href='javascript:history.back()'"
            style="background-color: #808080;">ย้อนกลับ</button>
    </div>

</body>

</html>
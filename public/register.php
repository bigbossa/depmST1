<?php
include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $full_name = $_POST["full_name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $role = "tenant"; // ผู้สมัครใหม่จะเป็น "ผู้เช่า" โดยอัตโนมัติ

    // ตรวจสอบว่าชื่อผู้ใช้ซ้ำหรือไม่
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว";
    } else {
        // เพิ่มข้อมูลผู้ใช้ใหม่
        $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, phone, email, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $password, $full_name, $phone, $email, $role);
        $stmt->execute();

        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #5f5f5f;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-container {
            background-color:#f5f5f5;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            color: #333;
        }

        .form-group input {
            width:94.5%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-group input:focus {
            border-color: #5b9bd5;
            outline: none;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #5b9bd5;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4a8ac7;
        }

        p {
            text-align: center;
        }

        p a {
            color: #5b9bd5;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>สมัครสมาชิก</h2>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form method="post">
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="full_name">ชื่อ-นามสกุล:</label>
                <input type="text" name="full_name" id="full_name" required>
            </div>
            <div class="form-group">
                <label for="phone">เบอร์โทร:</label>
                <input type="text" name="phone" id="phone">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email">
            </div>
            <button type="submit">สมัครสมาชิก</button>
        </form>
        <p>มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
    </div>
</body>

</html>

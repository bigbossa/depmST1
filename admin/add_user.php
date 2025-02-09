<?php
include("../config/session.php");
include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $full_name = $_POST["full_name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $role = $_POST["role"];

    $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, phone, email, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $password, $full_name, $phone, $email, $role);
    $stmt->execute();

    header("Location: manage_users.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>เพิ่มผู้ใช้</title>
</head>

<body>
    <h2>เพิ่มผู้ใช้</h2>
    <form method="post">
        <label>ชื่อผู้ใช้:</label> <input type="text" name="username" required><br>
        <label>รหัสผ่าน:</label> <input type="password" name="password" required><br>
        <label>ชื่อ-นามสกุล:</label> <input type="text" name="full_name" required><br>
        <label>เบอร์โทร:</label> <input type="text" name="phone"><br>
        <label>Email:</label> <input type="email" name="email"><br>
        <label>บทบาท:</label>
        <select name="role">
            <option value="admin">เจ้าของหอพัก</option>
            <option value="staff">ลูกจ้าง</option>
            <option value="tenant">ผู้เช่า</option>
        </select><br>
        <button type="submit">เพิ่มผู้ใช้</button>
    </form>
</body>

</html>
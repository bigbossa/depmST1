<?php
include("../config/session.php");
include("../config/database.php");

if (!isset($_GET["id"])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = $_GET["id"];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["full_name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $role = $_POST["role"];

    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $full_name, $phone, $email, $role, $user_id);
    $stmt->execute();

    header("Location: manage_users.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>แก้ไขข้อมูลผู้ใช้</title>
</head>

<body>
    <h2>แก้ไขข้อมูลผู้ใช้</h2>
    <form method="post">
        <label>ชื่อ-นามสกุล:</label> <input type="text" name="full_name" value="<?= $user["full_name"] ?>" required><br>
        <label>เบอร์โทร:</label> <input type="text" name="phone" value="<?= $user["phone"] ?>"><br>
        <label>Email:</label> <input type="email" name="email" value="<?= $user["email"] ?>"><br>
        <label>บทบาท:</label>
        <select name="role">
            <option value="admin" <?= $user["role"] == "admin" ? "selected" : "" ?>>เจ้าของหอพัก</option>
            <option value="staff" <?= $user["role"] == "staff" ? "selected" : "" ?>>ลูกจ้าง</option>
            <option value="tenant" <?= $user["role"] == "tenant" ? "selected" : "" ?>>ผู้เช่า</option>
        </select><br>
        <button type="submit">บันทึก</button>
    </form>
</body>

</html>
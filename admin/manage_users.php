<?php
include("../config/session.php");
include("../config/database.php");

// ดึงข้อมูลผู้ใช้ทั้งหมดจากฐานข้อมูล
$result = $conn->query("SELECT id, username, full_name, phone, email, role FROM users");

// ลบผู้ใช้
if (isset($_GET["delete"])) {
    $user_id = $_GET["delete"];
    $conn->query("DELETE FROM users WHERE id = $user_id");
    header("Location: manage_users.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>จัดการผู้ใช้</title>
</head>

<body>
    <h2>จัดการผู้ใช้</h2>
    <a href="add_user.php">เพิ่มผู้ใช้ใหม่</a>
    <table border="1">
        <tr>
            <th>ชื่อผู้ใช้</th>
            <th>ชื่อ-นามสกุล</th>
            <th>เบอร์โทร</th>
            <th>Email</th>
            <th>บทบาท</th>
            <th>การกระทำ</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row["username"] ?></td>
            <td><?= $row["full_name"] ?></td>
            <td><?= $row["phone"] ?></td>
            <td><?= $row["email"] ?></td>
            <td><?= ucfirst($row["role"]) ?></td>
            <td>
                <a href="edit_user.php?id=<?= $row["id"] ?>">แก้ไข</a> |
                <a href="manage_users.php?delete=<?= $row["id"] ?>"
                    onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบ?');">ลบ</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>

</html>
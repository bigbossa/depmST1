<?php
$conn = new mysqli("localhost", "root", "", "dormitory_management");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $event_date = $_POST["event_date"];
    $title = $_POST["title"];

    $stmt = $conn->prepare("UPDATE events SET event_date=?, title=? WHERE id=?");
    $stmt->bind_param("ssi", $event_date, $title, $id);
    $stmt->execute();

    echo "<script>alert('อัปเดตกำหนดการสำเร็จ!'); window.location='../../admin/event.php';</script>";
}

$conn->close();
?>
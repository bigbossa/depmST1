<?php
$conn = new mysqli("localhost", "root", "", "dormitory_management");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_date = $_POST["event_date"];
    $title = $_POST["title"];

    $stmt = $conn->prepare("INSERT INTO events (event_date, title) VALUES (?, ?)");
    $stmt->bind_param("ss", $event_date, $title);
    $stmt->execute();

    echo "<script>alert('เพิ่มกำหนดการสำเร็จ!'); window.location='../../admin/event.php';</script>";
}

$conn->close();
?>
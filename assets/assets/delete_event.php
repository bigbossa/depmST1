<?php
$conn = new mysqli("localhost", "root", "", "dormitory_management");
$id = $_GET['id'];

$conn->query("DELETE FROM events WHERE id=$id");

echo "<script>alert('ลบกำหนดการสำเร็จ!'); window.location='../../admin/event.php';</script>";

$conn->close();
?>
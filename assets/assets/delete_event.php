<?php
$conn = new mysqli("localhost", "root", "", "dormitory_management");
$id = $_GET['id'];

$conn->query("DELETE FROM events WHERE id=$id");

echo "<script>alert('ลบสำเร็จ!'); window.history.back();</script>";


$conn->close();
?>
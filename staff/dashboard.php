<?php
include("../config/session.php");
if ($_SESSION['role'] !== 'staff') {
    header("Location: ../public/index.php");
    exit();
}
?>
<h1>ยินดีต้อนรับ Staff</h1>
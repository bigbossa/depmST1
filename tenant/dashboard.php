<?php
include("../config/session.php");
if ($_SESSION['role'] !== 'tenant') {
    header("Location: ../public/index.php");
    exit();
}
?>
<h1>ยินดีต้อนรับ Tenant</h1>
<?php
include("../config/session.php");
include("../config/database.php");

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $user_sql = "SELECT full_name FROM users WHERE id = ?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user_data = $user_result->fetch_assoc();
    $full_name = $user_data['full_name'] ?? 'Guest'; // หากไม่มีข้อมูลให้แสดง 'Guest'
    $stmt->close();
} else {
    $full_name = 'Guest'; // หากไม่ได้ล็อกอินให้แสดง 'Guest'
}

// ดึงข้อมูลจากฐานข้อมูล ID 1 เท่านั้น
$sql = "SELECT * FROM meter WHERE id = 1";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

if (!$data) {
    echo "ไม่พบข้อมูล";
    exit;
}


// ลบผู้ใช้
if (isset($_GET["delete"])) {
    $user_id = $_GET["delete"];
    $conn->query("DELETE FROM users WHERE id = $user_id");
    header("Location: manage_bali.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้</title>
    <link rel="icon" type="image/png" href="../assets/images/home.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Add SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    .sidebar {
        height: 100vh;
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #343a40;
        padding-top: 20px;
    }

    .sidebar a {
        padding: 10px;
        text-decoration: none;
        color: white;
        display: block;
    }

    .sidebar a:hover {
        background-color: #495057;
    }

    .content {
        margin-left: 260px;
        padding: 20px;
    }

    .footer {
        margin-left: 150px;
        padding: 16px;
        background-color: #343a40;
        color: white;
        text-align: center;
    }

    .footer a {
        color: #5b9bd5;
        text-decoration: none;
    }

    .footer a:hover {
        text-decoration: underline;
    }

    .sidebar img {
        display: block;
        margin: 0 auto;
        border-radius: 10px;
    }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php
    include "../assets/assets/admin_sidebar.php";
    ?>


    <!-- Content -->
    <div class="content">
        <h2 class="mb-4">ราคา น้ำ&ไฟ ต่อหน่วย</h2>

        <?php if (isset($_SESSION['success'])) : ?>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ!',
            text: '<?php echo $_SESSION['success']; ?>',
            timer: 2000,
            showConfirmButton: false
        });
        </script>
        <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])) : ?>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'ผิดพลาด!',
            text: '<?php echo $_SESSION['error']; ?>',
            timer: 2000,
            showConfirmButton: false
        });
        </script>
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="post" action="process/update_process_unit.php">
                    <input type="hidden" name="id" value="1">
                    <div class="mb-3">
                        <label for="water" class="form-label">ค่าน้ำ (บาท/หน่วย)</label>
                        <input type="number" class="form-control" id="water" name="water"
                            value="<?php echo $data['water']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="electricity" class="form-label">ค่าไฟ (บาท/หน่วย)</label>
                        <input type="number" class="form-control" id="electricity" name="electricity"
                            value="<?php echo $data['electricity']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">อัปเดตข้อมูล</button>
                </form>
            </div>
        </div>
    </div>
    <br><br><br><br><br><br><br><br> <br><br><br><br>

    <?php if (isset($_SESSION['sweetalert'])): ?>
    <script>
    Swal.fire({
        icon: '<?php echo $_SESSION['type']; ?>',
        title: '<?php echo $_SESSION['message']; ?>',
        showConfirmButton: false,
        timer: 1500
    });
    <?php
            unset($_SESSION['sweetalert']);
            unset($_SESSION['message']);
            unset($_SESSION['type']);
            ?>
    </script>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of
                Service</a></p>
    </div>
</body>

</html>
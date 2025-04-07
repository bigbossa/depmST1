<?php include("../config/database.php");
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Login handling
    if (isset($_POST['login'])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];

                // ตรวจสอบบทบาทและเปลี่ยนเส้นทาง
                switch ($user["role"]) {
                    case "admin":
                        header("Location: ../admin/dashboard.php");
                        break;
                    case "staff":
                        header("Location: ../staff/dashboard.php");
                        break;
                    case "tenant":
                        header("Location: ../tenant/dashboard.php");
                        break;
                    default:
                        header("Location: index.php");
                        break;
                }
                exit();
            } else {
                $login_error = "รหัสผ่านไม่ถูกต้อง";
                $_SESSION['swal_error'] = "รหัสผ่านไม่ถูกต้อง";
            }
        } else {
            $login_error = "ไม่พบชื่อผู้ใช้";
            $_SESSION['swal_error'] = "ไม่พบชื่อผู้ใช้";
        }
    }

    // Registration handling
    if (isset($_POST['register'])) {
        if (
            isset($_POST["reg_username"]) && isset($_POST["reg_email"]) &&
            isset($_POST["reg_password"]) && isset($_POST["reg_full_name"])
        ) {
            $username = $_POST["reg_username"];
            $email = $_POST["reg_email"];
            $password = $_POST["reg_password"];
            $full_name = $_POST["reg_full_name"];

            // ตรวจสอบทั้ง username และ email ซ้ำ
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                // ตรวจสอบว่าซ้ำที่ username หรือ email
                $check_username = $conn->prepare("SELECT id FROM users WHERE username = ?");
                $check_username->bind_param("s", $username);
                $check_username->execute();

                if ($check_username->get_result()->num_rows > 0) {
                    $_SESSION['swal_error'] = "ชื่อผู้ใช้นี้ถูกใช้งานแล้ว";
                } else {
                    $_SESSION['swal_error'] = "อีเมลนี้ถูกใช้งานแล้ว";
                }
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $role = "tenant";

                $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, full_name) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $username, $email, $hashed_password, $role, $full_name);

                if ($stmt->execute()) {
                    $_SESSION['swal_success'] = "ลงทะเบียนสำเร็จ กรุณาเข้าสู่ระบบ";
                } else {
                    $_SESSION['swal_error'] = "เกิดข้อผิดพลาดในการลงทะเบียน กรุณาลองใหม่อีกครั้ง";
                }
            }
        } else {
            $_SESSION['swal_error'] = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap 5 Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel=" stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/stylelogin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <title>Login Page</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="POST" action="">
                <h1>Create Account!</h1>
                <?php if (isset($register_error)) echo "<p style='color: red;'>$register_error</p>"; ?>
                <?php if (isset($register_success)) echo "<p style='color: green;'>$register_success</p>"; ?>
                <input type="text" name="reg_username" placeholder="Username" required>
                <input type="email" name="reg_email" placeholder="Email" required>
                <input type="password" name="reg_password" placeholder="Password" required>
                <input type="text" name="reg_full_name" placeholder="Full Name" required>
                <button type="submit" name="register">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="POST" action="">
                <div class="text-start w-100">
                    <a href="../index.php" class="btn btn-outline-secondary fw-bold "style="margin-top: -55px; margin-left: -15px;">
                        <i class="bi bi-arrow-left">Back</i>
                    </a>
                </div>
                <br><br>
                <h1>Sign In</h1>
                <br>
                <?php if (isset($login_error)) echo "<p style='color: red;'>$login_error</p>"; ?>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <a href="#">Forget Your Password?</a>
                <button type="submit" name="login">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back! to Phutthachart website</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Welcome to Phutthachart website</h1>
                    <p>ยินดีตอนรับกลับ เข้าสู่ หอพักบ้านพุธทชาติ เว็บไซร์</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/scriptlogin.js"></script>

    <?php if (isset($_SESSION['swal_error'])): ?>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'เกิดข้อผิดพลาด!',
        text: '<?php echo $_SESSION['swal_error']; ?>',
    });
    <?php unset($_SESSION['swal_error']); ?>
    </script>
    <?php endif; ?>

    <?php if (isset($_SESSION['swal_success'])): ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'สำเร็จ!',
        text: '<?php echo $_SESSION['swal_success']; ?>',
    });
    <?php unset($_SESSION['swal_success']); ?>
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
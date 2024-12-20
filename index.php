<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo_list";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$icons = ['chevrons-up'];
$message = "";

$admin_check_stmt = $conn->prepare("SELECT COUNT(*) FROM admins WHERE username = ?");
$admin_username = "admin";
$admin_check_stmt->bind_param("s", $admin_username);
$admin_check_stmt->execute();
$admin_check_stmt->bind_result($admin_count);
$admin_check_stmt->fetch();
$admin_check_stmt->close();

if ($admin_count == 0) {
    $admin_email = "admin";
    $admin_password = password_hash("admin", PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $admin_username, $admin_email, $admin_password);
    if ($stmt->execute()) {
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    if ($stmt->execute()) {
        $message = "Register berhasil!, Selamat datang $username";
    } else {
        $message = "Terjadi kesalahan saat mendaftar.";
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            session_start(); 
            $_SESSION['username'] = $username; 
            header("Location: admin_dashboard.php"); 
            exit();
        } else {
            $message = "Username atau password tidak valid.";
        }
    } else {
        $stmt->close();
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                session_start(); 
                $_SESSION['username'] = $username; 
                header("Location: home.php"); 
                exit();
            } else {
                $message = "Username atau password tidak valid.";
            }
        } else {
            $message = "Username tidak ditemukan.";
        }
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TO DO LIST</title>
    <link rel="stylesheet" href="index.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons"></script>
</head>
<body>
    <div class="wrapper">
        <div class="screen-backdrop"></div>
        <div class="welcome-text">
            <h1><span>HELLOO,</span><br>WELCOME BACK</h1>
        </div>
        <div class="message"><?php echo $message; ?></div>
        <div class="arrow" id="dragArrow" draggable="true">
        <?php foreach ($icons as $icon): ?>
            <i data-feather="<?php echo $icon; ?>"></i>
        <?php endforeach; ?>
        </div>
        <div class="form-container" id="loginForm">
            <h2>Login</h2>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Masuk</button>
                <p class="register-text">
                    Don't Have an Account? <a href="#" id="showRegister">Register Here</a> 
                </p>
            </form>
        </div>
        <div class="form-container" id="registerForm">
            <h2>Register</h2>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="register">Register</button>
                <p class="register-text">
                    Already Have an Account? <a href="#" id="showLogin">Login Here</a> 
                </p>
            </form>
        </div>
    </div>
    <script src="index.js"></script>
</body>
</html>
<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header('Location: admin/index.php');
            $_SESSION['login_message'] = "Login successful as admin.";
        } else {
            header('Location: user/dashboard.php');
            $_SESSION['login_message'] = "Login successful as user.";
        }
    } else {
        $_SESSION['login_message'] = "Login failed. Email or password is incorrect.";
        header('Location: login.php');
    }
}

// tambahkan notifikasi jika user berhasil register
if (isset($_SESSION['register_message']) && $_SESSION['register_message'] == "Registration successful. Please login.") {
    echo "<script>alert('Registration successful. Please login.');</script>";
    unset($_SESSION['register_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lynx Marketplace</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (<?php echo isset($_SESSION['login_message']) ? 'true' : 'false'; ?>) {
                alert("<?php echo $_SESSION['login_message']; ?>");
            }
        });
    </script>
</head>
<body>
    <div class="login-box">
        <h1>Lynx Market</h1>
        <h2>LOGIN</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="login-links">
            <p>Don't have an account? <a href="register.php">Register</a></p>
            <p><a href="index.php">Back to Main Menu</a></p>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>

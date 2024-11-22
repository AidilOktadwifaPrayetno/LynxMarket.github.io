<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // Check if connection exists before using it
    if (isset($conn) && $conn) {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['register_message'] = "Registration failed: Email already exists.";
            header('Location: register.php');
        } else {
            $query = "INSERT INTO users (first_name, last_name, email, phone, address, password, role) 
                      VALUES ('$first_name', '$last_name', '$email', '$phone', '$address', '$password', 'buyer')";

            if (mysqli_query($conn, $query)) {
                $_SESSION['register_message'] = "Registration successful. Please login.";
                header('Location: login.php');
            } else {
                $_SESSION['register_message'] = "Registration failed: " . mysqli_error($conn);
                header('Location: register.php');
            }
        }
    } else {
        $_SESSION['register_message'] = "Database connection failed";
        header('Location: register.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Lynx Marketplace</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (<?php echo isset($_SESSION['register_message']) ? 'true' : 'false'; ?>) {
                alert("<?php echo $_SESSION['register_message']; ?>");
            }
        });
    </script>
</head>
<body>
    <div class="login-box">
        <h1>Lynx Market</h1>
        <h2>REGISTER</h2>
        <form method="POST">
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <textarea name="address" placeholder="Address" required></textarea>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <div class="login-links">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>

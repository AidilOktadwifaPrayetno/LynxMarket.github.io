<?php
session_start();
include '../config/db.php';

// Check if the buyer is already logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get profile data
$user = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'")->fetch_assoc();

// Update profile process
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email']; // Add email
    $password = $_POST['password'];

    // Do not hash the password because the instruction does not change the password
    $query = "UPDATE users SET first_name='$first_name', last_name='$last_name', phone='$phone', address='$address', email='$email'"; // Add email to the query
    if (!empty($password)) {
        $query .= ", password='$password'";
    }
    $query .= " WHERE id='$user_id'";

    if (mysqli_query($conn, $query)) {
        $success_message = "Profile updated successfully!";
        $user = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'")->fetch_assoc();
    } else {
        $error_message = "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN DASHBOARD - Profile</title>
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>
    <header>
        <h1>ADMIN DASHBOARD - Profile</h1>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="products.php">Daftar Produk</a>
            <a href="orders.php">Daftar Pesanan</a>
            <a href="feedback.php">Daftar Feedback</a>
            <a href="users.php">Daftar User</a>
            <a href="profile.php">Profil</a> 
            <a href="../logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>YOUR PROFILE</h2>
        <?php if (isset($success_message)) : ?>
            <p style="color: green;"><?= $success_message ?></p>
        <?php endif; ?>
        <?php if (isset($error_message)) : ?>
            <p style="color: red;"><?= $error_message ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="first_name" value="<?= $user['first_name'] ?>" required>
            <input type="text" name="last_name" value="<?= $user['last_name'] ?>" required>
            <input type="text" name="phone" value="<?= $user['phone'] ?>" required>
            <textarea name="address" required><?= $user['address'] ?></textarea>
            <input type="email" name="email" value="<?= $user['email'] ?>" required> <!-- Add email input -->
            <input type="password" name="password" placeholder="Password (optional)">
            <button type="submit">UPDATE PROFILE</button>
            <button type="button" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
        </form>
    </main>
</body>
</html>

<?php
session_start();
include '../config/db.php';

// Cek apakah pembeli sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data profil
$user = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'")->fetch_assoc();

// Proses update profil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email']; // Tambahkan email
    $password = $_POST['password'];

    // Tidak menghash password karena instruksi tidak mengubah password
    $query = "UPDATE users SET first_name='$first_name', last_name='$last_name', phone='$phone', address='$address', email='$email'"; // Tambahkan email ke query
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
    <title>Profile</title>
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>
    <header>
        <h1>PROFILE</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="orders.php">Orders</a>
            <a href="wishlist.php">Keranjang</a>
            <a href="feedback.php">Feedback</a>
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
            <input type="email" name="email" value="<?= $user['email'] ?>" required> <!-- Tambahkan input email -->
            <input type="password" name="password" placeholder="Password (optional)">
            <button type="submit">UPDATE PROFILE</button>
            <button type="button" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
        </form>
    </main>
</body>
</html>

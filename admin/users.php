<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Fetch all users
$users = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN DASHBOARD - Daftar User</title>
    <link rel="stylesheet" href="../assets/css/products.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>ADMIN DASHBOARD - Daftar User</h1>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="products.php">Daftar Produk</a>
            <a href="orders.php">Daftar Pesanan</a>
            <a href="feedback.php">Daftar Feedback</a>
            <a href="users.php">Daftar User</a>
            <a href="profile.php">Profile</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>
    <main class="main-content">
        <h1>Daftar User</h1>
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($users)) : ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['first_name'] . " " . $user['last_name'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['address'] ?></td>
                        <td><?= $user['phone'] ?></td>
                        <td><?= $user['password'] ?></td>
                        <td><?= $user['role'] ?></td>
                        <td>
                            <a href="edit_users.php?id=<?= $user['id'] ?>">Edit</a> | 
                            <a href="delete_users.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>

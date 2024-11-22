<?php
session_start();
include '../config/db.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Query untuk menghitung data statistik
$total_buyers = mysqli_query($conn, "SELECT COUNT(*) AS count FROM users WHERE role='buyer'")->fetch_assoc()['count'];
$total_orders = mysqli_query($conn, "SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'];
$total_products = mysqli_query($conn, "SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$total_feedbacks = mysqli_query($conn, "SELECT COUNT(*) AS count FROM feedback")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN DASHBOARD</title>
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>ADMIN DASHBOARD</h1>
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

    <!-- Main Content -->
    <main>
        <section class="dashboard">
            <h2>Summary</h2>
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Buyers</h3>
                    <p><?= $total_buyers ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <p><?= $total_orders ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <p><?= $total_products ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Feedbacks</h3>
                    <p><?= $total_feedbacks ?></p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Lynx Marketplace | All Rights Reserved</p>
    </footer>
</body>
</html>

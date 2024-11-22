<?php
session_start();
include '../config/db.php';

// Cek apakah pembeli sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: ../login.php');
    exit;
}

// Ambil id order
$wishlist_id = $_GET['id'];

// Hapus data order dari database
$query = "DELETE FROM wishlist WHERE id='$wishlist_id'";
mysqli_query($conn, $query);

// Tampilkan pesan sukses
if (mysqli_query($conn, $query)) {
    echo "<script>alert('Wishlist deleted successfully.'); window.location.href='wishlist.php';</script>";
} else {
    echo "<script>alert('Failed to delete wishlist.'); window.location.href='wishlist.php';</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Wishlist</title>
    <link rel="stylesheet" href="../assets/css/orders.css">
</head>
<header>
        <h1>DASHBOARD</h1>
        <nav>
            <a href="orders.php">Orders</a>
            <a href="wishlist.php">Wishlist</a>
            <a href="feedback.php">Feedback</a>
            <a href="profile.php">Profile</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>
<body>
    <h2 class="delete-order-title">Delete Wishlist</h2>
    <p>Wishlist deleted successfully. <a href="wishlist.php">Back to wishlist</a></p>
</body>
</html>


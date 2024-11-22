<?php
session_start();
include '../config/db.php';

// Cek apakah pembeli sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: ../login.php');
    exit;
}

// Ambil id order
$order_id = $_GET['id'];

// Cek status order
$query = "SELECT status FROM orders WHERE id='$order_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row['status'] == 'proses' || $row['status'] == 'completed' || $row['status'] == 'cancelled') {
    echo "<script>alert('Tidak bisa menghapus order karna sudah selesai diproses atau sudah dibatalkan.'); window.location.href='orders.php';</script>";
    exit;
}

// Hapus data order dari database
$query = "DELETE FROM orders WHERE id='$order_id'";
mysqli_query($conn, $query);

// Tampilkan pesan sukses
if (mysqli_query($conn, $query)) {
    echo "<script>alert('Order berhasil dihapus.'); window.location.href='orders.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus order karna sudah selesai diproses.'); window.location.href='orders.php';</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Order</title>
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
    <h2 class="delete-order-title">Delete Order</h2>
    <p>Order deleted successfully. <a href="orders.php">Back to orders</a></p>
</body>
</html>

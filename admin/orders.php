<?php
session_start();
include '../config/db.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Ambil data pesanan
$orders = mysqli_query($conn, "SELECT orders.id, users.first_name, products.name AS product_name, orders.quantity, orders.total_price, orders.status
                               FROM orders
                               JOIN users ON orders.user_id = users.id
                               JOIN products ON orders.product_id = products.id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN DASHBOARD - Daftar Pesanan</title>
    <link rel="stylesheet" href="../assets/css/products.css">
</head>
<body>
    <header>
        <h1>ADMIN DASHBOARD - Daftar Pesanan</h1>
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
    <main>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Buyer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($orders)) : ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= $order['first_name'] ?></td>
                        <td><?= $order['product_name'] ?></td>
                        <td><?= $order['quantity'] ?></td>
                        <td><?= $order['total_price'] ?></td>
                        <td><?= $order['status'] ?></td>
                        <td>
                            <a href="edit_order.php?id=<?= $order['id'] ?>">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>

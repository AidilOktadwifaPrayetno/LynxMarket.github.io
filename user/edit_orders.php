<?php
session_start();
require_once '../config/db.php';

// Check if the buyer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: ../login.php');
    exit;
}

// Retrieve the order to be edited
$order_id = $_GET['id'];
$order = mysqli_query($conn, "SELECT * FROM orders WHERE id='$order_id'");

// Check if the order exists
if (mysqli_num_rows($order) == 0) {
    echo "Order not found.";
    exit;
}

// Retrieve order details
$orders_id = $_GET['id'];
$orders = mysqli_query($conn, "SELECT products.name, products.price, products.image, orders.id as orders_id, orders.total_price, orders.quantity, orders.status
                                 FROM orders
                                 JOIN products ON orders.product_id = products.id
                                 WHERE orders.user_id = " . $_SESSION['user_id'] . " AND orders.id = '$orders_id'");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
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
    <h2>Edit Order</h2>
    <form action="" method="post">
        <?php while ($row = mysqli_fetch_assoc($orders)) : ?>
            <input type="hidden" name="order_id" value="<?= $row['orders_id'] ?>">
            <input type="hidden" name="price" value="<?= $row['price'] ?>">
            <img src="../uploads/<?= $row['image'] ?>" alt="<?= $row['name'] ?>" style="width: 100%; height: 200px; object-fit: cover;">
            <p>Nama Produk: <?= $row['name'] ?></p>
            <p>Harga: <?= "Rp.".$row['price'] ?></p>
            <p>Jumlah: <input type="number" name="quantity" value="<?= $row['quantity'] ?>" <?= $row['status'] == 'proses' || $row['status'] == 'completed' || $row['status'] == 'cancelled' ? 'disabled' : '' ?>></p>
            <button type="submit" name="edit" <?= $row['status'] == 'proses' || $row['status'] == 'completed' || $row['status'] == 'cancelled' ? 'disabled' : '' ?>>Simpan Perubahan</button>
            <?php if ($row['status'] == 'pending') : ?>
                <p style="color: green;">Pesanan ini dapat diubah karena statusnya pending.Silahkan ubah jumlah pesananmu.Terima kasih.</p>
            <?php elseif ($row['status'] == 'proses') : ?>
                <p style="color: orange;">Pesanan ini sedang diproses. Tidak dapat diubah. Terima kasih.</p>
            <?php elseif ($row['status'] == 'completed') : ?>
                <p style="color: green;">Pesanan ini telah selesai. Tidak dapat diubah. Terima kasih.</p>
            <?php elseif ($row['status'] == 'cancelled') : ?>
                <p style="color: red;">Pesanan ini telah dibatalkan. Tidak dapat diubah. Terima kasih.</p>
            <?php endif; ?>
            <a href="orders.php">Kembali ke Pesanan</a> <!-- Added button to return to orders menu -->
        <?php endwhile; ?>
    </form>



    <?php
    // Process edit order
    if (isset($_POST['edit'])) {
        
        $order_id = $_POST['order_id'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        // Calculate total price
        $total_price = $price * $quantity;

        // Update order data in the database
        $query = "UPDATE orders SET quantity='$quantity', total_price='$total_price' WHERE id='$order_id'";
        $result = mysqli_query($conn, $query);

        // Check if the update was successful
        if ($result) {
            echo "<script>alert('Order successfully updated.'); window.location.href='orders.php';</script>";
        } else {
            echo "<script>alert('Failed to update order.'); window.location.href='orders.php';</script>";
        }
    }
    ?>
</body>
</html>

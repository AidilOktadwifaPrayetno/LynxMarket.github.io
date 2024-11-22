<?php
session_start();
require_once '../config/db.php';

// Check if the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
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
                                 WHERE orders.id = '$orders_id'");

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
            <img src="../uploads/<?= $row['image'] ?>" alt="<?= $row['name'] ?>" style="width: 100%; height: 200px; object-fit: cover;">
            <p>Product Name: <?= $row['name'] ?></p>
            <p>Price: <?= "Rp.".$row['price'] ?></p>
            <p>Quantity: <?= $row['quantity'] ?></p>
            <select name="status" required>
                <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="completed" <?= $row['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= $row['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                <option value="proses" <?= $row['status'] == 'proses' ? 'selected' : '' ?>>Proses</option>
            </select>
            <button type="submit" name="edit">Save Changes</button>
        <?php endwhile; ?>
    </form>

    <?php
    // Process edit order
    if (isset($_POST['edit'])) {
        
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];

        // Update order status in the database
        $query = "UPDATE orders SET status='$status' WHERE id='$order_id'";
        mysqli_query($conn, $query);

        // Display success message and return to orders menu
        echo "<script>alert('Order status updated successfully.'); window.location.href='orders.php';</script>";
    }
    ?>
</body>
</html>
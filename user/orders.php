<?php
// Include database connection file
require_once '../config/db.php';

// Start session to access session variables
session_start();

// Retrieve order data including product name, payment method, price, and total price
$orders = mysqli_query($conn, "SELECT o.id, o.created_at, o.status, o.total_price, o.quantity, p.image AS product_image, p.name AS product_name, p.price AS product_price, o.metode_pembayaran FROM orders o JOIN products p ON o.product_id = p.id WHERE o.user_id='".$_SESSION['user_id']."'");

// Check if query is successful
if (!$orders) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

// Display table even if there are no orders
if (mysqli_num_rows($orders) == 0) {
    echo "<p>No orders found.</p>"; // Message if no orders
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="../assets/css/orders.css">
</head>
<body>
    <header>
        <h1>DASHBOARD - ORDERS</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="orders.php">Pesanan</a>
            <a href="wishlist.php">Keranjang</a>
            <a href="feedback.php">Feedback</a>
            <a href="profile.php">Profil</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>
    <main>
    <h2 class="orders-title">YOUR ORDERS</h2>   
     <div class="product-list"> 
            <?php while ($order = mysqli_fetch_assoc($orders)) : ?>
                <div class="product">   
                    <img src="../uploads/<?= $order['product_image'] ?>" alt="Product Image" style="width: 100%; height: 200px; object-fit: cover;">
                    <h3><?= $order['product_name'] ?></h3> <!-- Changed from 'product_description' to 'product_name' -->
                    <p>Harga: <?= "Rp.".$order['product_price'] ?></p> <!-- Display product price -->
                    <p>Jumlah: <?= $order['quantity'] ?></p>
                    <p>Total Harga: <?= "Rp.".$order['total_price'] ?></p> <!-- Display total price -->
                    <p>Metode Pembayaran: <?= $order['metode_pembayaran'] ?></p> <!-- Display payment method -->
                    <p>Status: 
                    <?php if ($order['status'] == 'pending') : ?>
                                <span style="color: orange;">Pending</span>
                            <?php elseif ($order['status'] == 'proses') : ?>
                                <span style="color: blue;">Proses</span>
                            <?php elseif ($order['status'] == 'completed') : ?>
                                <span style="color: green;">Completed</span>
                            <?php elseif ($order['status'] == 'cancelled') : ?>
                                <span style="color: red;">Cancelled</span>
                            <?php endif; ?>
                    </p>

                    <a href="delete_orders.php?id=<?= $order['id'] ?>" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a> 
                    <a href="edit_orders.php?id=<?= $order['id'] ?>">Edit</a>
                </div>
            <?php endwhile; ?>
        </div> 
    </main>

    <script>
    // JavaScript for order search
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("orderSearch");
        const orderRows = document.querySelectorAll(".order-table tbody tr");

        searchInput.addEventListener("keyup", function() {
            const filter = searchInput.value.toLowerCase();
            orderRows.forEach(row => {
                const cells = row.getElementsByTagName("td");
                let match = false;
                for (let i = 0; i < cells.length; i++) {
                    if (cells[i].textContent.toLowerCase().includes(filter)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
    </script>
</body>
</html>

<?php
session_start();
include '../config/db.php';

// Cek apakah pembeli sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: ../login.php');
    exit;
}

// Ambil produk yang dipilih
$product_id = $_GET['product'];

// Ambil data produk
$product = mysqli_query($conn, "SELECT * FROM products WHERE id='$product_id'");

// Cek apakah produk ada
if (mysqli_num_rows($product) == 0) {
    echo "Product not found.";
    exit;
}

// Tampilkan form pembelian
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Product</title>
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
    <h2>Buy Product</h2>
    <form action="" method="post">
        <?php while ($row = mysqli_fetch_assoc($product)) : ?>
            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
            <input type="hidden" name="price" value="<?= $row['price'] ?>">
            <img src="../uploads/<?= $row['image'] ?>" alt="<?= $row['name'] ?>" style="width: 100%; height: 200px; object-fit: cover;">
            <p>Nama Produk: <?= $row['name'] ?></p>
            <p>Harga: <?= "Rp.".$row['price'] ?></p>
            <p>Jumlah: <input type="number" name="quantity" value="1"></p>
            <p>Metode Pembayaran: <select name="payment_method">
                <option value="COD">Cash On Delivery (COD)</option>
            </select></p>
            <button type="submit" name="buy">Beli</button>
        <?php endwhile; ?>
    </form>

    <?php
    // Proses pembelian
    if (isset($_POST['buy'])) {
        $product_id = $_POST['product_id'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $payment_method = $_POST['payment_method'];

        // Hitung total harga
        $total_price = $price * $quantity;

        // Simpan data pembelian ke database
        $query = "INSERT INTO orders (user_id, product_id, total_price, quantity, metode_pembayaran) VALUES ('".$_SESSION['user_id']."', '$product_id', '$total_price', '$quantity', '$payment_method')";
        mysqli_query($conn, $query);

        // Tampilkan pesan sukses dan notifikasi
        echo "Product bought successfully. Please check your orders.";
        header('Location: orders.php');
        exit;
    }
    ?>
</body>
</html>


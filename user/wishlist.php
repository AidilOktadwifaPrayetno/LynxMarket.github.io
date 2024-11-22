<?php
session_start();
require_once '../config/db.php';

// Periksa apakah pembeli sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: ../login.php');
    exit;
}

// Tambahkan produk ke wishlist
if (isset($_GET['add'])) {
    $product_id = $_GET['add'];
    $user_id = $_SESSION['user_id'];

    // Cek apakah produk sudah ada di wishlist
    $check = mysqli_query($conn, "SELECT * FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO wishlist (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', 1)");
    }
    header('Location: wishlist.php');
}

// Ambil data wishlist
$wishlist = mysqli_query($conn, "
    SELECT 
        products.name, 
        products.price, 
        products.image, 
        wishlist.id AS wishlist_id, 
        wishlist.quantity 
    FROM 
        wishlist
    JOIN 
        products 
    ON 
        wishlist.product_id = products.id
    WHERE 
        wishlist.user_id = " . $_SESSION['user_id']
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="stylesheet" href="../assets/css/orders.css">
</head>
<body>
    <header>
        <h1>WISHLIST</h1>
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
        <h2 class="wishlist-title">YOUR WISHLIST</h2>
        <div class="product-list">
            <?php while ($item = mysqli_fetch_assoc($wishlist)) : ?>
                <div class="product">
                    <img src="../uploads/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" style="width: 100%; height: 200px; object-fit: cover;">
                    <h3><?= $item['name'] ?></h3>
                    <p>Price: <?= "Rp.".$item['price'] ?></p>
                    <p>Quantity: <?= $item['quantity'] ?></p>
                    <p>Total Price: <?= "Rp.".($item['price'] * $item['quantity']) ?></p>
                    <a href="delete_whislist.php?id=<?= $item['wishlist_id'] ?>" onclick="return confirm('Are you sure you want to delete this wishlist?')">Delete</a>
                    <a href="edit_whislist.php?id=<?= $item['wishlist_id'] ?>">Edit</a>
                    <a href="buy_wishlist.php?product=<?= $item['wishlist_id'] ?>">Beli Produk</a>

                </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>

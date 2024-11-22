<?php
session_start();
include '../config/db.php';

// Cek apakah pembeli sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: ../login.php');
    exit;
}

// Ambil data wishlist yang akan diedit
$wishlist_id = $_GET['id'];
$wishlist = mysqli_query($conn, "SELECT products.name, products.price, products.image, wishlist.id as wishlist_id, wishlist.quantity
                                 FROM wishlist
                                 JOIN products ON wishlist.product_id = products.id
                                 WHERE wishlist.user_id = " . $_SESSION['user_id'] . " AND wishlist.id = '$wishlist_id'");
?>

// Cek apakah wishlist ada
if (mysqli_num_rows($wishlist) == 0) {
    echo "Wishlist not found.";
    exit;
}

// Tampilkan form edit wishlist
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wishlist</title>
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
    <h2>Edit Wishlist</h2>
    <form action="" method="post">
        <?php while ($row = mysqli_fetch_assoc($wishlist)) : ?>
            <img src="../uploads/<?= $row['image'] ?>" alt="<?= $row['name'] ?>" style="width: 100%; height: 200px; object-fit: cover;">
            <p>Product Name: <?= $row['name'] ?></p> <!-- Tampilkan nama produk -->
            <p>Price: <?= $row['price'] ?></p> <!-- Tampilkan harga produk -->
            <p>Quantity: <input type="number" name="quantity" value="<?= $row['quantity'] ?>"></p> <!-- Tampilkan dan edit quantity -->
            <button type="submit" name="edit">Save Changes</button>
        <?php endwhile; ?>
    </form>

    <?php
    // Proses edit wishlist
    if (isset($_POST['edit'])) {
        $quantity = $_POST['quantity']; // Ambil quantity yang diedit

        // Update data wishlist di database
        $query = "UPDATE wishlist SET quantity='$quantity' WHERE id='$wishlist_id'";
        mysqli_query($conn, $query);

        // Tampilkan pesan sukses dan kembali ke menu wishlist
        echo "<script>alert('Wishlist updated successfully.'); window.location.href='wishlist.php';</script>";
    }
    ?>
</body>
</html>


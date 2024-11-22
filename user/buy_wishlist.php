<?php
session_start();
require_once '../config/db.php';

// Periksa apakah pembeli sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: ../login.php');
    exit;
}

// Ambil ID produk yang dipilih
if (!isset($_GET['product'])) {
    echo "Product ID is missing.";
    exit;
}

$wishlist_id = intval($_GET['product']);

// Ambil data produk dari wishlist
$stmt = $conn->prepare("SELECT * FROM wishlist WHERE id = ?");
$stmt->bind_param("i", $wishlist_id);
$stmt->execute();
$result = $stmt->get_result();

// Periksa apakah produk ditemukan
if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}

$wishlist = $result->fetch_assoc();
$stmt->close();

// Ambil data produk dari database
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $wishlist['product_id']);
$stmt->execute();
$result = $stmt->get_result();

// Periksa apakah produk ditemukan
if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Product</title>
    <link rel="stylesheet" href="../assets/css/orders.css">
</head>
<body>
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
<main>
    <h2>Buy Product</h2>
    <form action="" method="post">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
        <input type="hidden" name="price" value="<?= htmlspecialchars($product['price']) ?>">
        <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width: 100%; height: 200px; object-fit: cover;">
        <p>Product Name: <?= htmlspecialchars($product['name']) ?></p>
        <p>Price: <?= "Rp." . number_format($product['price'], 2, ',', '.') ?></p>
        <p>Quantity: <input type="number" name="quantity" value="<?= htmlspecialchars($wishlist['quantity']) ?>" min="1"></p>
        <p>Payment Method: 
            <select name="payment_method" required>
                <option value="COD">Cash On Delivery (COD)</option>
            </select>
        </p>
        <button type="submit" name="buy">Buy</button>
    </form>

    <?php
    // Proses pembelian
    if (isset($_POST['buy'])) {
        $product_id = intval($_POST['product_id']);
        $price = floatval($_POST['price']);
        $quantity = intval($_POST['quantity']);
        $payment_method = htmlspecialchars($_POST['payment_method']);
        $user_id = $_SESSION['user_id'];

        // Validasi jumlah kuantitas
        if ($quantity < 1) {
            echo "<p style='color: red;'>Quantity must be at least 1.</p>";
        } else {
            // Hitung total harga
            $total_price = $price * $quantity;

            // Simpan data ke tabel orders
            $stmt = $conn->prepare("
                INSERT INTO orders (user_id, product_id, total_price, quantity, metode_pembayaran) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("iiids", $user_id, $product_id, $total_price, $quantity, $payment_method);
            if ($stmt->execute()) {
                echo "<p style='color: green;'>Product bought successfully.</p>";

                // Hapus produk dari wishlist setelah dibeli
                $stmt = $conn->prepare("DELETE FROM wishlist WHERE id = ?");
                $stmt->bind_param("i", $wishlist_id);
                $stmt->execute();

                header('Location: orders.php');
                exit;
            } else {
                echo "<p style='color: red;'>Failed to process the order. Please try again.</p>";
            }
            $stmt->close();
        }
    }
    ?>
</main>
</body>
</html>

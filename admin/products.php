<?php
session_start();
include '../config/db.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Proses CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($image);

    if (isset($_POST['create'])) {
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        $sql = "INSERT INTO products (name, description, category, price, image) VALUES ('$name', '$description', '$category', '$price', '$image')";
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        if (!empty($image)) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
            $sql = "UPDATE products SET name='$name', description='$description', category='$category', price='$price', image='$image' WHERE id='$id'";
        } else {
            $sql = "UPDATE products SET name='$name', description='$description', category='$category', price='$price' WHERE id='$id'";
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM products WHERE id='$id'";
    }

    if ($conn->query($sql)) {
        echo "<script>alert('Operation successful.'); window.location.href='products.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href='products.php';</script>";
    }
}

// Ambil semua produk
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN DASHBOARD - Daftar Produk</title>
    <link rel="stylesheet" href="../assets/css/products.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>ADMIN DASHBOARD - Daftar Produk</h1>
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
    <main class="main-content">
        <h1>Manage Products</h1>
        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= $product['name'] ?></td>
                        <td><?= $product['description'] ?></td>
                        <td><?= $product['category'] ?></td>
                        <td><?= $product['price'] ?></td>
                        <td><img src="../uploads/<?= $product['image'] ?>" alt="Product Image" style="width: 100px; height: 100px; object-fit: cover;"></td>
                        <td>
                            <a href="edit_product.php?id=<?= $product['id'] ?>">Edit</a> | 
                            <a href="delete_product.php?id=<?= $product['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>Add Product</h2>
        <form method="POST" enctype="multipart/form-data" class="product-form">
            <input type="hidden" name="id">
            <input type="text" name="name" placeholder="Product Name" required>
            <textarea name="description" placeholder="Product Description" required></textarea>
            <input type="text" name="category" placeholder="Category" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="file" name="image">
            <button type="submit" name="create">Add Product</button>
        </form>
    </main>
</body>
</html>

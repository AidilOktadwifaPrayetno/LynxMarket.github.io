<?php
session_start();
include '../config/db.php';

// Check if admin is already logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Retrieve product data based on id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id='$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<script>alert('Product not found'); window.location.href='products.php';</script>";
        exit;
    }
}

// Process update product
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $sql = "UPDATE products SET name='$name', description='$description', price='$price' WHERE id='$id'";

    if ($conn->query($sql)) {
        echo "<script>alert('Product updated successfully!'); window.location.href='products.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href='products.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../assets/css/products.css">
</head>
<body>
    <h1>Edit Product</h1>
    <form method="POST" class="product-form">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <input type="text" name="name" placeholder="Product Name" value="<?= $product['name'] ?>" required>
        <textarea name="description" placeholder="Product Description" required><?= $product['description'] ?></textarea>
        <input type="number" name="price" placeholder="Product Price" value="<?= $product['price'] ?>" required>
        <div class="product-image">
            <img src="../uploads/<?= $product['image'] ?>" alt="Product Image">
        </div>
        <button type="submit" name="update" onclick="return confirm('Are you sure you want to update this product?')">Update Product</button>
        <a href="products.php">Back to Products</a>
    </form>
</body>
</html>

<?php
session_start();
require_once '../config/db.php';

// Check if the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Check if the product id is set
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Product deleted successfully.'); window.location.href='products.php';</script>";
    } else {
        echo "<script>alert('Failed to delete product.'); window.location.href='products.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Product id not set.'); window.location.href='products.php';</script>";
    exit;
}
?>

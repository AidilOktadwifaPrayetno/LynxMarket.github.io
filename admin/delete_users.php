<?php
session_start();
require_once '../config/db.php';

// Check if the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Check if the user id is set
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    // First, delete any related feedback_admin_reply records to avoid foreign key constraint failure
    $stmt = $conn->prepare("DELETE FROM feedback_admin_reply WHERE user_id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Then, delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('User deleted successfully.'); window.location.href='users.php';</script>";
    } else {
        echo "<script>alert('Failed to delete user.'); window.location.href='users.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('User id not set.'); window.location.href='users.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <aside class="sidebar">
        <h2>Admin Dashboard</h2>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="orders.php">Orders</a>
            <a href="buyers.php">Buyers</a>
            <a href="feedback.php">Feedback</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </aside>
    <main class="main-content">
        <h1>Delete User</h1>
        <p>Are you sure you want to delete this user?</p>
        <a href="users.php">Back to Users</a>
    </main>
</body>
</html>

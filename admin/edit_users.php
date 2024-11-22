<?php
session_start();
include '../config/db.php';

// Check if admin is already logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Retrieve user data based on id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id='$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "<script>alert('User not found'); window.location.href='users.php';</script>";
        exit;
    }
}

// Process update user
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $sql = "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email', role='$role', address='$address', phone='$phone', password='$password' WHERE id='$id'";

    if ($conn->query($sql)) {
        echo "<script>alert('User updated successfully!'); window.location.href='users.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href='users.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/css/users.css">
</head>
<body>
    <header>
        <h1>Edit User</h1>
    </header>
    <main>
        <form method="POST" class="user-form">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <input type="text" name="first_name" placeholder="First Name" value="<?= $user['first_name'] ?>" required>
            <input type="text" name="last_name" placeholder="Last Name" value="<?= $user['last_name'] ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= $user['email'] ?>" required>
            <input type="text" name="address" placeholder="Address" value="<?= $user['address'] ?>" required>
            <input type="text" name="phone" placeholder="Phone" value="<?= $user['phone'] ?>" required>
            <input type="password" name="password" placeholder="Password" value="<?= $user['password'] ?>" required>
            <select name="role" required>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="buyer" <?= $user['role'] == 'buyer' ? 'selected' : '' ?>>Buyer</option>
            </select>
            <button type="submit" name="update">Update User</button>
            <a href="users.php">Back to Users</a>
        </form>
    </main>
</body>
</html>

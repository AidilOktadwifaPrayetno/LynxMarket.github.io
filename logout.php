<?php
session_start();
session_destroy();
$_SESSION['login_message'] = "You have been logged out successfully.";
header('Location: login.php');
?>

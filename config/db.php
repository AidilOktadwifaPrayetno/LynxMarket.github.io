<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'lynx_marketplace';

// Koneksi ke database
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>

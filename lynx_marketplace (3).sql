-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Nov 2024 pada 16.17
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lynx_marketplace`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `message`, `created_at`) VALUES
(7, 1, 'Halo, min ', '2024-11-19 15:03:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `feedback_admin_reply`
--

CREATE TABLE `feedback_admin_reply` (
  `id` int(11) NOT NULL,
  `feedback_response_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `feedback_reply`
--

CREATE TABLE `feedback_reply` (
  `id` int(11) NOT NULL,
  `feedback_response_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `feedback_reply`
--

INSERT INTO `feedback_reply` (`id`, `feedback_response_id`, `user_id`, `message`, `created_at`) VALUES
(6, 3, 1, 'Gaada min', '2024-11-19 15:07:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `feedback_response`
--

CREATE TABLE `feedback_response` (
  `id` int(11) NOT NULL,
  `feedback_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `feedback_response`
--

INSERT INTO `feedback_response` (`id`, `feedback_id`, `user_id`, `message`, `created_at`) VALUES
(3, 7, 5, 'Iyaa halo ', '2024-11-19 15:03:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','proses','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `metode_pembayaran` varchar(50) NOT NULL DEFAULT 'Cash On Delivery (COD)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `total_price`, `status`, `created_at`, `metode_pembayaran`) VALUES
(22, 1, 7, 0, 0.00, 'completed', '2024-11-19 11:21:52', 'COD'),
(28, 1, 5, 2, 42466246.00, 'pending', '2024-11-19 12:48:45', 'COD'),
(30, 1, 9, 1, 4000231.00, 'pending', '2024-11-19 12:49:33', 'COD');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category`) VALUES
(4, 'Realme C5', 'asdwwa', 245000.00, 'images (3).jpg', 'PHONES'),
(5, 'Hadset Back', 'sss', 21233123.00, 'images.jpg', 'AUDIO'),
(6, 'Headset telinga', 'asdw', 30000.00, 'images (1).jpg', 'AUDIO'),
(7, 'Laptop ROG', 'asdsa', 34000000.00, 'images (4).jpg', 'LAPTOP'),
(8, 'Laptop AEROS ', 'sadwasd', 2300000.00, 'main-81d723d634d5adc83be25d3b158fb852_600x400.jpg', 'LAPTOP'),
(9, 'Phone XL', 'asdwa', 4000231.00, 'download.jpg', 'PHONES'),
(10, 'Monitor LG', '144hz', 2999000.00, 'download (1).jpg', 'MONITOR'),
(11, 'MONITOR SAMSUNG', '244Hz', 3000000.00, 'download (2).jpg', 'MONITOR'),
(12, 'Keyboard Gaming', 'RGB', 700000.00, '401949.jpg', 'KEYBOARD'),
(13, 'Mouse  4090GT', 'Wireless', 300000.00, 'download (3).jpg', 'MOUSE');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','buyer') DEFAULT 'buyer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `address`, `password`, `role`) VALUES
(1, 'Aidil', 'Oktadwifa', 'aidil@gmail.com', '081371444187', 'Jl. jamal Jamil no.12', 'admin', 'buyer'),
(4, 'Aidho', 'Oktafrika', 'aidho@gmail.com', '081371444187', 'Jl. Jamal Jamil no.12', 'aidho', 'buyer'),
(5, 'admin', '-', 'admin@gmail.com', '0', 'admin\r\n', 'admin', 'admin'),
(6, 'Udin', ' Np', 'udin@gmail.com', '1231231231243', 'Jl udin', 'udin', 'buyer');

-- --------------------------------------------------------

--
-- Struktur dari tabel `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(8, 1, 8, 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `feedback_admin_reply`
--
ALTER TABLE `feedback_admin_reply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_response_id` (`feedback_response_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `feedback_reply`
--
ALTER TABLE `feedback_reply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_response_id` (`feedback_response_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `feedback_response`
--
ALTER TABLE `feedback_response`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_id` (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `feedback_admin_reply`
--
ALTER TABLE `feedback_admin_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `feedback_reply`
--
ALTER TABLE `feedback_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `feedback_response`
--
ALTER TABLE `feedback_response`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `feedback_admin_reply`
--
ALTER TABLE `feedback_admin_reply`
  ADD CONSTRAINT `feedback_admin_reply_ibfk_1` FOREIGN KEY (`feedback_response_id`) REFERENCES `feedback_response` (`id`),
  ADD CONSTRAINT `feedback_admin_reply_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `feedback_reply`
--
ALTER TABLE `feedback_reply`
  ADD CONSTRAINT `feedback_reply_ibfk_1` FOREIGN KEY (`feedback_response_id`) REFERENCES `feedback_response` (`id`),
  ADD CONSTRAINT `feedback_reply_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `feedback_response`
--
ALTER TABLE `feedback_response`
  ADD CONSTRAINT `feedback_response_ibfk_1` FOREIGN KEY (`feedback_id`) REFERENCES `feedback` (`id`),
  ADD CONSTRAINT `feedback_response_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

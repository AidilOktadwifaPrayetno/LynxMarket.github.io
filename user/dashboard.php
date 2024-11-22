<?php
session_start();
require_once '../config/db.php';

// Check if the buyer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: ../login.php');
    exit;
}

// Fetch products
$products = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .slide img {
            transition: transform 0.5s ease-out, opacity 0.5s ease-out;
            transform: scale(1);
            opacity: 1;
        }
        .slide img.zoom-out {
            transform: scale(1.2);
        }
    </style>
</head>
<body>
    <header>
        <h1>DASHBOARD</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="orders.php">Pesanan</a>
            <a href="wishlist.php">Keranjang</a>
            <a href="feedback.php">Feedback</a>
            <a href="profile.php">Profil</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <section class="hero-slider">
            <div class="slide">
                <img src="../assets/images/slide1.jpg" alt="Latest Smartphones" class="active zoom-out">
                <img src="../assets/images/slide2.jpg" alt="Premium Audio Devices">
                <img src="../assets/images/slide3.jpg" alt="Mobile Accessories">
                <div class="slide-content">
                    <h2>WELCOME!! 
                        <span class="typedText"></span></h2>
                    <p>Experience cutting-edge technology at your fingertips</p>
                    <a href="#phones" class="cta-button">Shop Now</a>
                </div>
            </div>
        </section>

        <h2>PRODUCTS</h2>
        <div class="product-list">
            <?php while ($product = mysqli_fetch_assoc($products)) : ?>
                <div class="product">
                    <img src="../uploads/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" style="width: 100%; height: 200px; object-fit: cover;">
                    <h3><?= $product['name'] ?></h3>
                    <p>Price: <?= $product['price'] ?></p>
                    <a href="wishlist.php?add=<?= $product['id'] ?>">Add to Wishlist</a>
                    <a href="buy.php?product=<?= $product['id'] ?>">Buy Product</a>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script>
        // Slider animation
        const sliderImages = document.querySelectorAll('.slide img');
        let currentImageIndex = 0;

        // Hide all images except first one on load
        sliderImages.forEach((img, index) => {
            if (index !== 0) {
                img.style.display = 'none';
                img.style.opacity = 0;
            }
        });

        // Function to show next image with fade and zoom effect
        function showNextImage() {
            // Fade out and zoom in current image
            sliderImages[currentImageIndex].style.opacity = 0;
            sliderImages[currentImageIndex].classList.remove('zoom-out');
            
            setTimeout(() => {
                sliderImages[currentImageIndex].style.display = 'none';
                
                // Update index to next image
                currentImageIndex = (currentImageIndex + 1) % sliderImages.length;
                
                // Show and fade in next image with zoom out
                sliderImages[currentImageIndex].style.display = 'block';
                sliderImages[currentImageIndex].classList.add('zoom-out');
                
                setTimeout(() => {
                    sliderImages[currentImageIndex].style.opacity = 1;
                }, 50);
            }, 500);
        }

        // Start automatic slideshow
        setInterval(showNextImage, 3000); // Change image every 3 seconds

        // Typing animation with matching delay
        var typingEffect = new Typed(".typedText", {
            strings: ["Lynx MARKET", "Best Prices", "Quality Products", "Fast Delivery"],
            loop: true,
            typeSpeed: 100,
            backSpeed: 80,
            backDelay: 3000 // Match the slider delay
        });
    </script>
</body>
</html>

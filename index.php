<?php
session_start();
require_once 'config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lynx Marketplace</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
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
        <div class="header-left">
            <h1>Lynx MARKET</h1>
            <nav class="main-nav">
                <a href="#phones">Phones</a>
                <a href="#audio">Audio</a>
                <a href="#accessories">Accessories</a>
                <a href="#support">Support</a>
            </nav>
        </div>
        <div class="header-right">
            <nav>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['role'] == 'admin'): ?>
                        <a href="admin/index.php">Dashboard</a>
                    <?php else: ?>
                        <a href="profile.php">Profile</a>
                    <?php endif; ?>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero-slider">
            <div class="slide">
                <img src="assets/images/slide1.jpg" alt="Latest Smartphones" class="active zoom-out">
                <img src="assets/images/slide2.jpg" alt="Premium Audio Devices">
                <img src="assets/images/slide3.jpg" alt="Mobile Accessories">
                <div class="slide-content">
                    <h2>WELCOME!! 
                        <span class="typedText"></span></h2>
                    <p>Experience cutting-edge technology at your fingertips</p>
                    <a href="#phones" class="cta-button">Shop Now</a>
                </div>
            </div>
        </section>
       
        <section class="categories" id="categories">
            <h2>CATEGORIES</h2>
            <div class="category-grid">
                <div class="category-card" id="phones">
                    <img src="uploads/download.jpg" alt="Phones">
                    <h3>Phones</h3>
                </div>
                <div class="category-card" id="audio">
                    <img src="uploads/images (4).jpg" alt="Audio">
                    <h3>Laptop</h3>
                </div>
                <div class="category-card" id="accessories">
                    <img src="uploads/download (3).jpg" alt="Accessories">
                    <h3>Mouse</h3>
                </div>

                <div class="category-card" id="accessories">
                    <img src="uploads/401949.jpg" alt="Accessories">
                    <h3>Keyboard</h3>
                </div>

                <div class="category-card" id="accessories">
                    <img src="uploads/images (1).jpg" alt="Accessories">
                    <h3>Audio</h3>
                </div>

                <div class="category-card" id="accessories">
                    <img src="uploads/download (1).jpg" alt="Accessories">
                    <h3>Monitor</h3>
                </div>

            </div>
        </section>

        <section class="products">
            <h2>PRODUCTS</h2>
            <div class="product-grid">
                <?php
                if ($conn) {
                    $result = mysqli_query($conn, "SELECT * FROM products ORDER BY category");
                    if ($result && mysqli_num_rows($result) > 0) {
                        $current_category = '';
                        while ($product = mysqli_fetch_assoc($result)) {
                            if ($current_category != $product['category']) {
                                if ($current_category != '') {
                                    echo "</div>"; // Close previous category div
                                }
                                $current_category = $product['category'];
                                echo "<div class='category-products'>
                                        <h3 class='category-title' id='{$current_category}'>{$current_category}</h3>";
                            }
                            echo "
                            <div class='product-card'>
                                <img src='uploads/{$product['image']}' alt='{$product['name']}' style='width: 150px; height: 150px; object-fit: cover;'>
                                <div class='product-info'>
                                    <h4>{$product['name']}</h4>
                                    <p class='price'>Rp. " . number_format($product['price'], 0, ',', '.') . "</p>
                                    <a href='login.php?id={$product['id']}' class='view-button'>Buy Now</a>
                                </div>
                            </div>";
                        }
                        if ($current_category != '') {
                            echo "</div>"; // Close last category div
                        }
                    } else {
                        echo "<p>Maaf, tidak ada produk yang tersedia saat ini</p>";
                    }
                } else {
                    echo "<p>Maaf, terjadi kesalahan koneksi database</p>";
                }
                ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>About Us</h4>
                <p>Your trusted online marketplace for quality products</p>
            </div>
            <div class="footer-section">
                <h4>Support</h4>
                <ul>
                    <li><a href="#support">Contact Us</a></li>
                    <li><a href="#support">FAQ</a></li>
                    <li><a href="#support">Shipping Info</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="#" target="_blank"><i class="uil uil-facebook"></i></a>
                    <a href="#" target="_blank"><i class="uil uil-instagram"></i></a>
                    <a href="#" target="_blank"><i class="uil uil-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Lynx Marketplace. All rights reserved.</p>
        </div>
    </footer>

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

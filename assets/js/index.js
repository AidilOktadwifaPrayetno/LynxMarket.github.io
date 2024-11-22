// Get all slider images
const sliderImages = document.querySelectorAll('.slide img');
let currentImageIndex = 0;

// Hide all images except first one on load
sliderImages.forEach((img, index) => {
    if (index !== 0) {
        img.style.display = 'none';
    }
});

// Function to show next image
function showNextImage() {
    // Hide current image
    sliderImages[currentImageIndex].style.display = 'none';
    
    // Update index to next image
    currentImageIndex = (currentImageIndex + 1) % sliderImages.length;
    
    // Show next image
    sliderImages[currentImageIndex].style.display = 'block';
}

// Start automatic slideshow
setInterval(showNextImage, 2000); // Change image every 3 seconds

/* ----- TYPING EFFECT ----- */
var typingEffect = new Typed(".typedText", {
    strings: ["HALAK HITA "],
    loop: true,
    typeSpeed: 100,
    backSpeed: 80,
    backDelay: 2000,
  });

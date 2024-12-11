<?php
session_start();
$error = "";

// Include database configuration
include "config.php";

if (!isset($_SESSION["username"])) {
    $_SESSION["error"] = "You must be logged in to access this page.";
    header("location:userlogin.php");
    exit();
}

// Fetch latest 5 posts from the database
$latest_posts = [];
$conn = new mysqli('localhost', 'root', '', 'imnotadev');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, title, content, photo, date FROM posts ORDER BY id DESC LIMIT 5";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $latest_posts[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="user.css"/>
</head>
<body>
<header>
    <div class="logo">
        <h1>Coffean.</h1>
    </div>
    <nav>
        <ul>
            <li><a href="user.php">Home</a></li>
            <li><a href="#">Solutions</a></li>
            <li><a href="#">Resources</a></li>
            <li><a href="#">Pricing</a></li>
        </ul>
    </nav>
    <div class="header-buttons">
        <a href="logout.php" class="login">Log Out</a>
    </div>
</header>

<div class="layout">
    <aside class="sidebar">
        <h3>Related post</h3>
        <ul>
            <li><a href="#">All</a></li>
            <li><a href="category_view.php?category_id=1">Coffee</a></li>
            <li><a href="category_view.php?category_id=2">Tea</a></li>
            <li><a href="category_view.php?category_id=3">Food</a></li>
            
        </ul>
    </aside>

    <main>
       
    <div class="slider-container">
    <div>Top 5 Latest Posts</div>
    <div class="slider">
        <?php foreach ($latest_posts as $post): ?>
            <div class="slide">
                <img src="<?php echo htmlspecialchars($post['photo']); ?>" width="40%" height="auto" alt="Post Image" class="slide-image">
                <h2 class="webinar-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                <div class="webinar-meta">Published on: <?php echo htmlspecialchars($post['date']); ?></div>
                <div class="webinar-content">
                    <?php echo htmlspecialchars($post['content']); ?>
                </div>
                
                <a href="readmore.php?id=<?php echo htmlspecialchars($post['id']); ?>" class="read-more">Read more</a>

            </div>
        <?php endforeach; ?>
    </div>
</div>

        <button class="nav-button prev">←</button>
        <button class="nav-button next">→</button>
            <div class="dots">
         
            </div>
           
    </main>
</div>

<script>
    const slider = document.querySelector('.slider');
    const slides = document.querySelectorAll('.slide');
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');
    const dotsContainer = document.querySelector('.dots');

    let currentIndex = 0;

    // Create dots
    slides.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });

    const dots = document.querySelectorAll('.dot');

    function updateDots() {
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }

    function goToSlide(index) {
        currentIndex = index;
        slider.style.transform = `translateX(-${currentIndex * 100}%)`;
        updateDots();
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % slides.length;
        goToSlide(currentIndex);
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        goToSlide(currentIndex);
    }

    prevButton.addEventListener('click', prevSlide);
    nextButton.addEventListener('click', nextSlide);
</script>
</body>
</html>
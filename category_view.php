<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "imnotadev");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get category ID from URL
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

// Initialize posts and category variables
$category = "";
$posts = [];

if ($category_id > 0) {
    // Fetch category name
    $categoryNameQuery = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $categoryNameQuery->bind_param("i", $category_id);
    $categoryNameQuery->execute();
    $categoryNameResult = $categoryNameQuery->get_result();

    if ($categoryNameResult->num_rows > 0) {
        $category = $categoryNameResult->fetch_assoc()['name'];
        $categoryNameQuery->close();

        // Fetch posts by category
        $stmt = $conn->prepare("SELECT id, title, content, photo, date FROM posts WHERE category_id = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        $category = "Category not found.";
    }
} else {
    $category = "Invalid category ID.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="user.css" />
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
        <h3>Related Posts</h3>
        <ul>
            <li><a href="#">All</a></li>
            <li><a href="category_view.php?category_id=1">Coffee</a></li>
            <li><a href="category_view.php?category_id=2">Tea</a></li>
            <li><a href="category_view.php?category_id=3">Food</a></li>
        </ul>
    </aside>

    <main>
        <h1>Posts in Category: <?php echo htmlspecialchars($category); ?></h1>

        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                    <?php if (!empty($post['photo'])): ?>
                        <img src="<?php echo htmlspecialchars($post['photo']); ?>" alt="Post Image" style="max-width: 200px;">
                    <?php endif; ?>
                    <p><small>Posted on: <?php echo htmlspecialchars($post['date']); ?></small></p>
                    <hr>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts found in this category.</p>
        <?php endif; ?>

        
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

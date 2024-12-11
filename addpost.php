<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "imnotadev");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="addpost.css"/>
</head>
<body>
<header>
    <div class="logo">
        <h1>Coffean.</h1>
    </div>
    <nav>
        <ul>
            <li><a href="admin.php">Home</a></li>
            <li><a href="addpost.php">Create Posts</a></li>
            <li><a href="managepost.php">Manage Posts</a></li>
            <li><a href="#">Manage User</a></li>
        </ul>
    </nav>
    <div class="header-buttons">
        <a href="logout.php" class="login">Log Out</a>
    </div>
</header>

  <!-- Main Content -->
  <div class="main-content" id="main-content">
    
    <div class="form-container">
      <form action="save_post.php" method="post" enctype="multipart/form-data">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" placeholder="Enter post title">

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="">Select a Category</option>
            <?php
            // Fetch categories from database
            $result = $conn->query("SELECT id, name FROM categories");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                }
            } else {
                echo "<option value=''>No categories available</option>";
            }
            ?>
        </select>

        <label for="content">Content</label>
        <textarea id="content" name="content" placeholder="Write your post content here..."></textarea>

        <label for="photo">Photo</label>
        <input type="file" id="photo" name="photo">

        <label for="date">Date</label>
        <input type="date" id="date" name="date" value="2024-12-03">

     

  
        <button type="submit" class="btn-save">Submit</button>
      </form>
    </div>
  </div>


  </script>
</body>
</html>
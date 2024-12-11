<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "imnotadev");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if (isset($_GET['delete'])) {
    $post_id = intval($_GET['delete']);
    $conn->query("DELETE FROM posts WHERE id = $post_id");
    header("Location: manage_posts.php");
    exit();
}

// Fetch posts from the database
$result = $conn->query("SELECT * FROM posts ORDER BY date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <link rel="stylesheet" href="user.css">
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
            <li><a href="manage_posts.php">Manage Posts</a></li>
            <li><a href="#">Manage User</a></li>
        </ul>
    </nav>
    <div class="header-buttons">
        <a href="logout.php" class="login">Log Out</a>
    </div>
</header>

<div class="main-content">
    <h2>Manage Posts</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>category_id</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['category_id']) . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>";
                    echo "<a href='edit_post.php?id=" . $row['id'] . "' class='btn-edit'>Edit</a> ";
                    echo "<a href='delete_post.php?id=" . $row['id'] . "' class='btn-delete' onclick='return confirm(\"Are you sure you want to delete this post?\")'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No posts found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background-color: #ff8c00;
    color: white;
}

header nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
}

header nav ul li {
    margin: 0 15px;
}

header nav ul li a {
    text-decoration: none;
    color: white;
    font-weight: bold;
}

.main-content {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #f4f4f4;
}

.btn-edit {
    color: #fff;
    background-color: #4CAF50;
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 5px;
}

.btn-delete {
    color: #fff;
    background-color: #f44336;
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 5px;
}

.btn-edit:hover, .btn-delete:hover {
    opacity: 0.9;
}
</style>

</body>
</html>

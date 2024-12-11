<?php
require './middleware/adminMiddleware.php';
include "config.php";
$result = $db->query("SELECT * FROM posts ORDER BY date DESC");
checkAdminAccess();

if(!isset($_SESSION["username"]))
{
  $_SESSION["error"] = "You do not have admin access.";
	header("location:userlogin.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffean Admin</title>
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
            <li><a href="addpost.php">Create Posts</a></li>
            <li><a href="#">Manage Posts</a></li>
            <li><a href="#">Manage User</a></li>
        </ul>
    </nav>
    <div class="header-buttons">
        <a href="logout.php" class="login">Log Out</a>
    </div>
</header>
<body>

</body>

  <!-- Main Content -->
  

  <script>
     const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menu-toggle');

    menuToggle.addEventListener('click', () => {
      // Toggle visibility of the sidebar
      sidebar.classList.toggle('hidden');
    });

  </script>
</body>
</html>
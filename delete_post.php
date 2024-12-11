<?php
$conn = new mysqli("localhost", "root", "", "imnotadev");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete related rows from the 'likes' table
    $conn->query("DELETE FROM likes WHERE post_id = $id");

    // Now delete the post
    $deleteQuery = "DELETE FROM posts WHERE id = $id";

    if ($conn->query($deleteQuery)) {
        echo "Post deleted successfully!";
        header("Location: managepost.php");
        exit();
    } else {
        echo "Error deleting post: " . $conn->error;
    }
}
?>

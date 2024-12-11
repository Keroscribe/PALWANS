<?php
session_start();

// Include the database configuration file to establish the connection
require_once 'config.php';

// Check if the user is logged in


// Get the logged-in user's ID and username from the session
$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;

    if (!empty($comment) && $post_id > 0) {
        // Prepare and execute the SQL query to insert the comment
        $sql = "INSERT INTO comments (post_id, author, content, date) VALUES (?, ?, ?, NOW())";
        $stmt = $db->prepare($sql);

        $stmt->bind_param("iss", $post_id, $username, $comment);

        if ($stmt->execute()) {
            // Redirect to the original post after successful submission
            header("Location: readmore.php?id=$post_id");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Please provide a valid comment and post ID.";
    }
} else {
    echo "Invalid request method.";
}
?>
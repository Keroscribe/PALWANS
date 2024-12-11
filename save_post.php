<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "imnotadev");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $content = trim($_POST['content']);
    $date = trim($_POST['date']);
    $photo = '';

    // Validate fields
    if (empty($title) || empty($category) || empty($content) || empty($date)) {
        die("All fields are required.");
    }

    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadedFile = $uploadDir . basename($_FILES['photo']['name']);

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
            die("Invalid file type. Only JPG, PNG, and GIF are allowed.");
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadedFile)) {
            $photo = $uploadedFile;
        } else {
            die("Failed to upload the photo.");
        }
    }

    // Insert post into database
    $stmt = $conn->prepare("INSERT INTO posts (title, category_id, content, photo, date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $category, $content, $photo, $date);

    if ($stmt->execute()) {
       header ("location: addpost.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<?php

session_start();
// config.php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'imnotadev';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
if (!isset($_SESSION["username"])) {
    $_SESSION["error"] = "You must be logged in to access this page.";
    header("location:userlogin.php");
    exit();}
$user_id = $_SESSION['user_id'] ?? null;
// Get the post ID from the URL
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Initialize variables
$post = null;
$comments = [];
$related_posts = [];

// Fetch the post data
$sql = "SELECT title, content, 
               (SELECT COUNT(*) FROM likes WHERE post_id = ?) AS likes, 
               category_id 
        FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $post_id, $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();
} else {
    die("Post not found.");
}

// Fetch comments
$sql = "SELECT author, content, date FROM comments WHERE post_id = ? ORDER BY date DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}



// Fetch related posts
$sql = "SELECT id, title FROM posts WHERE id != ? LIMIT 6";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $related_posts[] = array(
        'url' => 'readmore.php?id=' . $row['id'],
        'title' => $row['title']
    );
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Add a CSS file if needed -->
</head>
<style>
    
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
        }
        .liked {
    color: red;
    font-weight: bold;
}


        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .main-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .post-title {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .post-content img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 15px 0;
        }

        .post-meta {
            display: flex;
            gap: 20px;
            margin: 15px 0;
            color: #666;
            font-size: 14px;
        }

        .interaction-buttons {
            display: flex;
            gap: 15px;
            margin: 15px 0;
        }

        .interaction-buttons button {
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
        }

        .comments-section {
            margin-top: 30px;
        }

        .comment {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .comment-form {
            margin-top: 20px;
        }

        .comment-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .sidebar {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .related-posts {
            list-style: none;
        }

        .related-posts li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .related-posts a {
            color: #333;
            text-decoration: none;
        }

        .related-posts a:hover {
            color: #666;
        }
        #like-container {
    font-family: Arial, sans-serif;
}

#like-count {
    font-weight: bold;
}

#like-button {
    padding: 10px 20px;
    border: none;
    color: white;
    background-color: #007BFF;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

#like-button[data-liked="true"] {
    background-color: red;
}

#like-button:hover {
    opacity: 0.9;
}

    </style>
</style>
<body>
    <div class="container">
        <main class="main-content">
            <article>
                <h1 class="post-title"><?php echo htmlspecialchars($post['title']); 
                ?></h1>
                <div class="post-content">
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                </div>
                <div class="post-meta">
                   
                  <span>Category: <?php echo htmlspecialchars($post['category_id']); ?></span>

                </div>
                <div class="interaction-buttons">
                <?php
// Check if the user has liked the post
$liked = false;
if ($user_id) {
    $sql = "SELECT 1 FROM likes WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $liked = $result->num_rows > 0;
}

?>
<div class="post" data-post-id="<?php echo $post_id; ?>">
    <button class="like-btn" data-liked="<?php echo $liked ? 'true' : 'false'; ?>">
        <?php echo $liked ? 'Unlike' : 'Like'; ?>
    </button>
    <span class="like-count"><?php echo htmlspecialchars($post['likes']); ?></span>
</div>

                    <button id="comment-button">💬 Comment</button>
                </div>
                <div class="comments-section">
                    <h3>Comments</h3>
                    <?php if (!empty($comments)): ?>
                        <?php foreach($comments as $comment): ?>
                        <div class="comment">
                            <strong><?php echo htmlspecialchars($comment['author']); ?></strong>
                            <p><?php echo htmlspecialchars($comment['content']); ?></p>
                            <small><?php echo htmlspecialchars($comment['date']); ?></small>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No comments yet. Be the first to comment!</p>
                    <?php endif; ?>
                    <form class="comment-form" method="POST" action="add_comment.php">
                        <textarea name="comment" placeholder="Add your comment" required></textarea>
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                        <button type="submit">Submit</button>
                    </form>
                </div>
            </article>
        </main>
        <aside class="sidebar">
            <h2>Related Posts</h2>
            <ul class="related-posts">
                <?php foreach($related_posts as $related_post): ?>
                <li><a href="<?php echo $related_post['url']; ?>"><?php echo htmlspecialchars($related_post['title']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </aside>
    </div>

    <script>
document.querySelectorAll('.like-btn').forEach(button => {
    button.addEventListener('click', function () {
        const postElement = this.closest('.post');
        const postId = postElement.dataset.postId;

        fetch('like.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `post_id=${postId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'liked') {
                this.textContent = 'Liked';
                this.dataset.liked = 'true';

                // Update like count
                const likeCountSpan = postElement.querySelector('.like-count');
                likeCountSpan.textContent = data.like_count;
            } else if (data.status === 'already_liked') {
                alert(data.message); // Inform the user they already liked the post
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});


</script>

</body>
</html>
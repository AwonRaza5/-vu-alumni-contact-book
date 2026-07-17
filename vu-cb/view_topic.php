<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include ('db.php');
// Get topic ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid topic ID.");
}

$topic_id = $_GET['id'];

// Fetch forum topic details
$topic_query = "SELECT forum_topics.id, forum_topics.title, forum_topics.content, forum_topics.created_at, 
                users.name FROM forum_topics 
                JOIN users ON forum_topics.user_id = users.id 
                WHERE forum_topics.id = $topic_id";

$topic_result = $conn->query($topic_query);

if ($topic_result->num_rows == 0) {
    die("Forum topic not found.");
}

$topic = $topic_result->fetch_assoc();

// Handle comment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_comment'])) {
    $user_id = $_SESSION['user_id'];
    $comment_text = $conn->real_escape_string($_POST['comment_text']);
    
    if (!empty($comment_text)) {
        $comment_query = "INSERT INTO comments (discussion_id, user_id, comment, created_at) 
                          VALUES ('$topic_id', '$user_id', '$comment_text', NOW())";
        if ($conn->query($comment_query) === TRUE) {
            echo "<script>alert('Comment posted successfully!'); window.location='view_topic.php?id=$topic_id';</script>";
        } else {
            echo "<script>alert('Error posting comment: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Please enter a comment.');</script>";
    }
}

// Fetch comments for the topic
$comments_query = "SELECT comments.comment, comments.created_at, users.name FROM comments 
                   JOIN users ON comments.user_id = users.id 
                   WHERE comments.discussion_id = $topic_id 
                   ORDER BY comments.created_at ASC";
$comments_result = $conn->query($comments_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Discussion | ConnectBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card-custom {
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .btn-custom {
            background: #007bff;
            color: white;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="card card-custom p-4">
        <h2><?= htmlspecialchars($topic['title']) ?></h2>
        <p><?= nl2br(htmlspecialchars($topic['content'])) ?></p>
        <small>Posted by <?= htmlspecialchars($topic['name']) ?> on <?= $topic['created_at'] ?></small>
    </div>

    <!-- Comment Section -->
    <div class="card card-custom p-4">
        <h4>Comments</h4>
        <?php if ($comments_result->num_rows > 0): ?>
            <ul class="list-group">
                <?php while ($comment = $comments_result->fetch_assoc()): ?>
                    <li class="list-group-item">
                        <strong><?= htmlspecialchars($comment['name']) ?>:</strong>
                        <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                        <small>Posted on <?= $comment['created_at'] ?></small>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>
    </div>

    <!-- Post a Comment -->
    <div class="card card-custom p-4">
        <h4>Post a Comment</h4>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Your Comment:</label>
                <textarea name="comment_text" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" name="post_comment" class="btn btn-custom">Submit Comment</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

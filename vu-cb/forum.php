<?php
include ('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_topic'])) {
    $user_id = $_SESSION['user_id'];
    $topic_title = $_POST['topic_title'];
    $topic_content = $_POST['topic_content'];
    $image_path = "";

    // Handle file upload
    if ($_FILES['topic_image']['name'] != "") {
        $target_dir = "uploads/";
        $image_name = time() . "_" . $_FILES["topic_image"]["name"];
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["topic_image"]["tmp_name"], $target_file);
        $image_path = $target_file;
    }

    // Insert into database
    $query = "INSERT INTO forum_topics (user_id, title, content, picture, created_at) 
              VALUES ('$user_id', '$topic_title', '$topic_content', '$image_path', NOW())";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Topic posted successfully!'); window.location='forum.php';</script>";
    } else {
        echo "<script>alert('Error posting topic.');</script>";
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Discussion Forum | ConnectBook</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .forum-container {
            margin-top: 50px;
        }
        .card-custom {
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .user-img {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            object-fit: cover;
        }
        .content-box {
            flex: 1;
            margin-left: 15px;
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

<div class="container forum-container">
    <h2 class="text-center mb-4">Discussion Forum</h2>

    <!-- Post a new topic -->
    <div class="card card-custom">
        <h4>Start a Discussion</h4>
     <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Topic Title:</label>
        <input type="text" name="topic_title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Topic Content:</label>
        <textarea name="topic_content" class="form-control" rows="4" required></textarea>
    </div>
    <div class="mb-3">
        <label>Upload Image (Optional):</label>
        <input type="file" name="topic_image" class="form-control">
    </div>
    <button type="submit" name="post_topic" class="btn btn-primary">Post Topic</button>
</form>

    </div>

   <?php
$query = "SELECT forum_topics.id, forum_topics.title, forum_topics.content, 
                 forum_topics.picture, forum_topics.created_at, users.name 
          FROM forum_topics 
          JOIN users ON forum_topics.user_id = users.id 
          ORDER BY forum_topics.created_at DESC";
$topics = mysqli_query($conn, $query);
?>
<h4 class="mb-4">Recent Discussions</h4>
<div class="row">
    <?php while ($topic = mysqli_fetch_assoc($topics)): ?>
        <div class="col-md-3 mb-4">
            <div class="card" style="border-radius: 10px; box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1); height: 100%;">
                <?php if (!empty($topic['picture'])): ?>
                    <img src="<?php echo $topic['picture']; ?>" 
                         class="card-img-top" 
                         style="width: 100%; height: 180px; object-fit: cover; border-top-left-radius: 10px; border-top-right-radius: 10px;"
                         alt="Topic Image">
                <?php endif; ?>
                <div class="card-body text-center">
                    <h6 class="card-title"><?php echo $topic['title']; ?></h6>
                    <p class="card-text" style="font-size: 14px; overflow: hidden; height: 50px;"><?php echo substr($topic['content'], 0, 50) . '...'; ?></p>
                    <small class="text-muted d-block mb-2">By <?php echo $topic['name']; ?></small>
                    <a href="view_topic.php?id=<?php echo $topic['id']; ?>" class="btn btn-sm btn-primary w-100">View Discussion</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>





   
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

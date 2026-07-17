<?php
include('db.php');
session_start();


// Get topic ID
if (!isset($_GET['id'])) {
    header("Location: manage_forum.php");
    exit();
}

$id = $_GET['id'];

// Fetch topic details
$query = "SELECT * FROM forum_topics WHERE id = $id";
$result = mysqli_query($conn, $query);
$topic = mysqli_fetch_assoc($result);

if (!$topic) {
    header("Location: manage_forum.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // Image upload
    $picture = $topic['picture']; // Keep old image by default
    if (!empty($_FILES['picture']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
        move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file);
        $picture = $target_file;
    }

    // Update query
    $update_query = "UPDATE forum_topics SET title='$title', content='$content', picture='$picture' WHERE id=$id";
    
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Topic updated successfully!'); window.location='manage_forum.php';</script>";
    } else {
        echo "<script>alert('Error updating topic: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Discussion Topic | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center">Edit Discussion Topic</h2>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Title:</label>
            <input type="text" name="title" class="form-control" value="<?php echo $topic['title']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Content:</label>
            <textarea name="content" class="form-control" rows="4" required><?php echo $topic['content']; ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image:</label><br>
            <?php if ($topic['picture']): ?>
                <img src="<?php echo $topic['picture']; ?>" width="100" height="100" style="object-fit: cover;">
            <?php else: ?>
                No image uploaded.
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload New Image (Optional):</label>
            <input type="file" name="picture" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Topic</button>
        <a href="manage_forum.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include('db.php');
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Add News
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_news'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $date = date("Y-m-d");

    $sql = "INSERT INTO news (title, content, date_posted) VALUES ('$title', '$content', '$date')";
    if (mysqli_query($conn, $sql)) {
        $msg = "News added successfully.";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Delete News
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM news WHERE id = $id");
    header("Location: manage_news.php");
    exit();
}

// Fetch all news
$news_query = mysqli_query($conn, "SELECT * FROM news ORDER BY date_posted DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Manage News</h2>

    <?php if (isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label>News Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>News Content</label>
            <textarea name="content" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" name="add_news" class="btn btn-primary">Add News</button>
    </form>

    <h4>All News</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Date Posted</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($news_query)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['content']); ?></td>
                    <td><?php echo $row['date_posted']; ?></td>
                    <td>
                        <a href="manage_news.php?delete=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Are you sure you want to delete this news?')"
                           class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>

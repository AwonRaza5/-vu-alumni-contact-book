<?php
include('db.php');
session_start();



// Fetch all forum topics
$query = "SELECT forum_topics.id, forum_topics.title, forum_topics.content, forum_topics.picture, forum_topics.created_at, users.name 
          FROM forum_topics 
          JOIN users ON forum_topics.user_id = users.id 
          ORDER BY forum_topics.created_at DESC";
$topics = mysqli_query($conn, $query);

// Delete topic
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM forum_topics WHERE id = $id");
    header("Location: manage_forum.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Discussion Forum | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center">Manage Discussion Forum</h2>

    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Image</th>
                <th>Posted By</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($topic = mysqli_fetch_assoc($topics)): ?>
                <tr>
                    <td><?php echo $topic['id']; ?></td>
                    <td><?php echo $topic['title']; ?></td>
                    <td><?php echo substr($topic['content'], 0, 50) . '...'; ?></td>
                    <td>
                        <?php if ($topic['picture']): ?>
                            <img src="<?php echo $topic['picture']; ?>" width="50" height="50" style="object-fit: cover;">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td><?php echo $topic['name']; ?></td>
                    <td><?php echo $topic['created_at']; ?></td>
                    <td>
                        <a href="edit_forum.php?id=<?php echo $topic['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="manage_forum.php?delete=<?php echo $topic['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

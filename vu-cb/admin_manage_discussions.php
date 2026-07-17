<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include ('db.php');
// Delete post
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM discussions WHERE id = $id");
}

$posts = $conn->query("SELECT discussions.*, users.name FROM discussions 
    JOIN users ON discussions.user_id = users.id ORDER BY discussions.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Discussions</title>
        <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h2>Manage Discussions</h2>
    <a href="admin_dashboard.php">Back to Dashboard</a>
    <table border="1">
        <tr>
            <th>User</th>
            <th>Topic</th>
            <th>Message</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $posts->fetch_assoc()): ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['topic'] ?></td>
            <td><?= $row['message'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this post?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

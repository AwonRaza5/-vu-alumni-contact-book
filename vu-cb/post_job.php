<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("db.php");
include("navbar.php");

// Handle job post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $type = $conn->real_escape_string($_POST['type']);
    $deadline = $_POST['deadline'];

    $insert = "INSERT INTO jobs (title, description, type, deadline)
               VALUES ('$title', '$description', '$type', '$deadline')";

    if ($conn->query($insert)) {
        $message = "<div class='alert alert-success'>Job/Internship posted successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// ✅ Fetch all posted jobs
$jobs = mysqli_query($conn, "SELECT * FROM jobs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Post Job | ConnectBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Post a Job or Internship</h2>
    <?= $message ?? '' ?>
    
    <!-- Post Form -->
    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="Job">Job</option>
                <option value="Internship">Internship</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input type="date" name="deadline" id="deadline" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="5" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Post</button>
    </form>

    <!-- Posted Jobs Table -->
    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Type</th>
                <th>Deadline</th>
                <th>Posted On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($jobs && mysqli_num_rows($jobs) > 0): ?>
                <?php while ($job = mysqli_fetch_assoc($jobs)): ?>
                    <tr>
                        <td><?= $job['id']; ?></td>
                        <td><?= htmlspecialchars($job['title']); ?></td>
                        <td><?= htmlspecialchars(substr($job['description'], 0, 50)) . '...'; ?></td>
                        <td><?= $job['type']; ?></td>
                        <td><?= $job['deadline']; ?></td>
                        <td><?= $job['created_at']; ?></td>
                     
                        <td>
    <a href="edit_job.php?id=<?= $job['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
    <a href="manage_jobs.php?delete=<?= $job['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
</td>

                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No jobs posted yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

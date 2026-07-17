<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("db.php");
include("navbar.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: post_job.php");
    exit();
}

$job_id = intval($_GET['id']);

// Fetch job details
$result = mysqli_query($conn, "SELECT * FROM jobs WHERE id = $job_id");
$job = mysqli_fetch_assoc($result);

if (!$job) {
    echo "<div class='alert alert-danger text-center mt-5'>Job not found.</div>";
    exit();
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $type = $conn->real_escape_string($_POST['type']);
    $deadline = $_POST['deadline'];

    $update = "UPDATE jobs 
               SET title = '$title', description = '$description', type = '$type', deadline = '$deadline'
               WHERE id = $job_id";

    if ($conn->query($update)) {
        $message = "<div class='alert alert-success'>Job updated successfully!</div>";
        // Refresh data
        $result = mysqli_query($conn, "SELECT * FROM jobs WHERE id = $job_id");
        $job = mysqli_fetch_assoc($result);
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Job | ConnectBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Edit Job/Internship</h2>
    <?= $message ?? '' ?>
    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" required value="<?= htmlspecialchars($job['title']) ?>">
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="Job" <?= $job['type'] == 'Job' ? 'selected' : '' ?>>Job</option>
                <option value="Internship" <?= $job['type'] == 'Internship' ? 'selected' : '' ?>>Internship</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input type="date" name="deadline" id="deadline" class="form-control" required value="<?= $job['deadline'] ?>">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="5" class="form-control" required><?= htmlspecialchars($job['description']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="post_job.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>

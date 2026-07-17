<?php
include('db.php');
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle job delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM jobs WHERE id = $id");
    header("Location: manage_jobs.php");
    exit();
}

// Handle new job post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);

    mysqli_query($conn, "INSERT INTO jobs (title, description, type, deadline, created_at) 
                         VALUES ('$title', '$description', '$type', '$deadline', NOW())");
    header("Location: manage_jobs.php");
    exit();
}

// Fetch jobs
$jobs = mysqli_query($conn, "SELECT * FROM jobs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Jobs & Internships | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center">Manage Jobs & Internships</h2>

    <!-- Job Posting Form -->
    <div class="card my-4">
        <div class="card-header bg-primary text-white">Post New Job / Internship</div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label>Type</label>
                    <select name="type" class="form-select">
                        <option value="Job">Job</option>
                        <option value="Internship">Internship</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Application Deadline</label>
                    <input type="date" name="deadline" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Post Job</button>
            </form>
        </div>
    </div>

    <!-- Existing Jobs Table -->
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
            <?php while ($job = mysqli_fetch_assoc($jobs)): ?>
                <tr>
                    <td><?php echo $job['id']; ?></td>
                    <td><?php echo htmlspecialchars($job['title']); ?></td>
                    <td><?php echo substr($job['description'], 0, 50) . '...'; ?></td>
                    <td><?php echo $job['type']; ?></td>
                    <td><?php echo $job['deadline']; ?></td>
                    <td><?php echo $job['created_at']; ?></td>
                   <td class="d-flex gap-1">
    <a href="edit_job_admin.php?id=<?= $job['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
    <a href="manage_jobs.php?delete=<?= $job['id'] ?>" class="btn btn-danger btn-sm"
       onclick="return confirm('Delete this job?');">Delete</a>
</td>

                    
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

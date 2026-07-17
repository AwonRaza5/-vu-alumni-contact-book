<?php
include("db.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("navbar.php");

// Fetch all jobs
$query = "SELECT * FROM jobs ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>All Jobs | ConnectBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .job-card {
            border-radius: 10px;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
        }
        .job-title {
            font-size: 18px;
            font-weight: bold;
        }
        .badge-job {
            background-color: #198754;
        }
        .badge-internship {
            background-color: #0d6efd;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">All Job & Internship Listings</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while ($job = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 mb-4">
                    <div class="card job-card">
                        <div class="card-body">
                            <h5 class="job-title">
                                <?= htmlspecialchars($job['title']) ?>
                                <span class="badge <?= $job['type'] == 'Job' ? 'badge-job' : 'badge-internship' ?>">
                                    <?= $job['type'] ?>
                                </span>
                            </h5>
                            <p class="card-text mt-2"><?= nl2br(htmlspecialchars(substr($job['description'], 0, 120))) ?>...</p>
                            <p class="text-muted mb-2">Deadline: <?= htmlspecialchars($job['deadline']) ?></p>
                            <p class="text-muted">Posted on: <?= date("M d, Y", strtotime($job['created_at'])) ?></p>
                            <a href="apply_job.php?id=<?= $job['id'] ?>" class="btn btn-sm btn-outline-primary">View & Apply</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">No job or internship posts found.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

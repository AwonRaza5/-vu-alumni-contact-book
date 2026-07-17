<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
include "db.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_jobs.php");
    exit();
}
$job_id = (int)$_GET['id'];

/* Fetch the job */
$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();
if (!$job) {
    echo "<div class='alert alert-danger m-5'>Job not found.</div>";
    exit();
}

/* Handle update */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = $_POST['title'];
    $description = $_POST['description'];
    $type        = $_POST['type'];
    $deadline    = $_POST['deadline'];

    $up = $conn->prepare(
        "UPDATE jobs SET title = ?, description = ?, type = ?, deadline = ? WHERE id = ?"
    );
    $up->bind_param("ssssi", $title, $description, $type, $deadline, $job_id);
    if ($up->execute()) {
        header("Location: manage_jobs.php");
        exit();
    } else {
        $msg = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Edit Job</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width:770px;">
  <h3 class="mb-4">Edit Job #<?= $job_id ?></h3>
  <?= $msg ?? '' ?>
  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input name="title" value="<?= htmlspecialchars($job['title']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" rows="5" class="form-control" required><?= htmlspecialchars($job['description']) ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Type</label>
      <select name="type" class="form-select">
        <option value="Job"        <?= $job['type']=='Job'?'selected':'' ?>>Job</option>
        <option value="Internship" <?= $job['type']=='Internship'?'selected':'' ?>>Internship</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Deadline</label>
      <input type="date" name="deadline" value="<?= $job['deadline'] ?>" class="form-control" required>
    </div>
    <button class="btn btn-success">Update</button>
    <a href="manage_jobs.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>

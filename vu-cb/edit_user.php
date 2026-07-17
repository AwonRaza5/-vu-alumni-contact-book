<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include "db.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}
$user_id = (int)$_GET['id'];

/* Fetch user */
$stmt = $conn->prepare("SELECT name, email, user_type, status FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) {
    echo "<div class='alert alert-danger m-5'>User not found.</div>";
    exit();
}

/* Update user */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = $_POST['name'];
    $email     = $_POST['email'];
    $user_type = $_POST['user_type'];
    $status    = $_POST['status'];

    $up = $conn->prepare("
        UPDATE users
        SET name = ?, email = ?, user_type = ?, status = ?
        WHERE id = ?
    ");
    $up->bind_param("ssssi", $name, $email, $user_type, $status, $user_id);
    if ($up->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $msg = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width:600px">
  <h3 class="mb-4">Edit User #<?= $user_id ?></h3>
  <?= $msg ?? '' ?>
  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input name="name" class="form-control" required value="<?= htmlspecialchars($user['name']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input name="email" type="email" class="form-control" required value="<?= htmlspecialchars($user['email']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">User Type</label>
      <select name="user_type" class="form-select">
        <option value="student" <?= $user['user_type']=='student'?'selected':'' ?>>Student</option>
        <option value="alumni"  <?= $user['user_type']=='alumni'?'selected':'' ?>>Alumni</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Status</label>
      <select name="status" class="form-select">
        <option value="Pending"  <?= $user['status']=='Pending'?'selected':'' ?>>Pending</option>
        <option value="Approved" <?= $user['status']=='Approved'?'selected':'' ?>>Approved</option>
        <option value="Rejected" <?= $user['status']=='Rejected'?'selected':'' ?>>Rejected</option>
      </select>
    </div>

    <button class="btn btn-success">Update</button>
    <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>

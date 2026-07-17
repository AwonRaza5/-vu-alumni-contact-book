<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
include "db.php";

/* ===== Handle approve / reject / delete ===== */
if (isset($_GET['action'], $_GET['id'])) {
    $id      = (int)$_GET['id'];
    $action  = $_GET['action'];

    if ($action === 'approve' || $action === 'reject') {
        $newStatus = $action === 'approve' ? 'Approved' : 'Rejected';
        $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $id);
        $stmt->execute();
        $msg = "User $newStatus successfully!";
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $msg = "User deleted successfully!";
    }
    echo "<script>alert('$msg'); window.location='admin_dashboard.php';</script>";
    exit();
}

/* ===== Fetch all users ===== */
$users = $conn->query("SELECT id, name, email, user_type, status FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "admin_navbar.php"; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">User Management Panel</h2>

    <table class="table table-bordered table-striped shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>User&nbsp;Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php $i=1; while ($row = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= ucfirst($row['user_type']) ?></td>
                <td>
                    <span class="badge bg-<?= 
                        $row['status'] === 'Approved' ? 'success' :
                        ($row['status'] === 'Rejected' ? 'danger' : 'secondary') ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>
              <td class="d-flex gap-1">
    <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>

    <a href="?action=approve&id=<?= $row['id'] ?>" class="btn btn-success btn-sm"
       <?= $row['status']==='Approved'?'disabled':'' ?>>Approve</a>

    <a href="?action=reject&id=<?= $row['id'] ?>" class="btn btn-warning btn-sm text-white"
       <?= $row['status']==='Rejected'?'disabled':'' ?>>Reject</a>

    <a href="?action=delete&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
       onclick="return confirm('Delete this user?')">Delete</a>
</td>

            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

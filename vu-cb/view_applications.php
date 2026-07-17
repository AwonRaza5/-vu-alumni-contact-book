<?php
session_start();
include("db.php");
include("navbar.php");

// Check role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['alumni', 'admin'])) {
    header("Location: login.php");
    exit();
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'], $_POST['status'])) {
    $appId = intval($_POST['application_id']);
    $newStatus = $_POST['status'];

    $allowedStatuses = ['Pending', 'Short-listed', 'Rejected', 'Hired'];

    if (in_array($newStatus, $allowedStatuses)) {
        $stmt = $conn->prepare("UPDATE job_applications SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $appId);
        $stmt->execute();
    }
}

// Fetch all applications
$sql = "
SELECT 
    ja.id AS application_id,
    u.name AS student_name,
    u.email AS student_email,
    j.title AS job_title,
    ja.status,
    ja.applied_at
FROM job_applications ja
JOIN users u ON ja.student_id = u.id
JOIN jobs j ON ja.job_id = j.id
ORDER BY ja.applied_at DESC
";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Applications | ConnectBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Manage Job Applications</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Email</th>
                    <th>Job Title</th>
                    <th>Status</th>
                    <th>Change Status</th>
                    <th>Applied On</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['application_id'] ?></td>
                        <td><?= htmlspecialchars($row['student_name']) ?></td>
                        <td><?= htmlspecialchars($row['student_email']) ?></td>
                        <td><?= htmlspecialchars($row['job_title']) ?></td>
                        <td><span class="badge bg-secondary"><?= $row['status'] ?></span></td>
                        <td>
                            <form method="POST" class="d-flex gap-1">
                                <input type="hidden" name="application_id" value="<?= $row['application_id'] ?>">
                                <select name="status" class="form-select form-select-sm" required>
                                    <option <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option <?= $row['status'] == 'Short-listed' ? 'selected' : '' ?>>Short-listed</option>
                                    <option <?= $row['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                    <option <?= $row['status'] == 'Hired' ? 'selected' : '' ?>>Hired</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                        <td><?= date("M d, Y h:i A", strtotime($row['applied_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">No applications found.</div>
    <?php endif; ?>
</div>
</body>
</html>

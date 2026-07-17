<?php
include('db.php');
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Delete reward
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM rewards WHERE id = $id");
    header("Location: manage_rewards.php");
    exit();
}

// Add new reward
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $reward_title = mysqli_real_escape_string($conn, $_POST['reward_title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $date_awarded = mysqli_real_escape_string($conn, $_POST['date_awarded']);

    mysqli_query($conn, "INSERT INTO rewards (student_name, reward_title, description, date_awarded, created_at)
                         VALUES ('$student_name', '$reward_title', '$description', '$date_awarded', NOW())");

    header("Location: manage_rewards.php");
    exit();
}

// Fetch all rewards
$rewards = mysqli_query($conn, "SELECT * FROM rewards ORDER BY date_awarded DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Rewards | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center">Manage Student Rewards</h2>

    <!-- Add Reward Form -->
    <div class="card my-4">
        <div class="card-header bg-success text-white">Add New Reward</div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label>Student Name</label>
                    <input type="text" name="student_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Reward Title</label>
                    <input type="text" name="reward_title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label>Date Awarded</label>
                    <input type="date" name="date_awarded" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Add Reward</button>
            </form>
        </div>
    </div>

    <!-- Display Rewards -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Reward Title</th>
                <th>Description</th>
                <th>Date Awarded</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($reward = mysqli_fetch_assoc($rewards)): ?>
                <tr>
                    <td><?php echo $reward['id']; ?></td>
                    <td><?php echo htmlspecialchars($reward['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($reward['reward_title']); ?></td>
                    <td><?php echo htmlspecialchars($reward['description']); ?></td>
                    <td><?php echo $reward['date_awarded']; ?></td>
                    <td><?php echo $reward['created_at']; ?></td>
                    <td>
                        <a href="manage_rewards.php?delete=<?php echo $reward['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this reward?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

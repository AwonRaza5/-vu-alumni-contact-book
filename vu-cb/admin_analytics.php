<?php
include('db.php');
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch counts
$total_students = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE user_type = 'student'"))[0];
$total_tutors = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE user_type = 'tutor'"))[0];

$total_events = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM events"))[0];
$total_rewards = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM rewards"))[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Admin Analytics Dashboard</h2>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <h3><?php echo $total_students; ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Tutors</h5>
                    <h3><?php echo $total_tutors; ?></h3>
                </div>
            </div>
        </div>


  

        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Total Events</h5>
                    <h3><?php echo $total_events; ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Total Rewards</h5>
                    <h3><?php echo $total_rewards; ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('db.php');
include('navbar.php');

$user_id = $_SESSION['user_id'];

// Get user data
$query = "SELECT name, user_type FROM users WHERE id = $user_id AND user_type = 'alumni'";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Access denied. This page is for alumni only.</div></div>";
    exit();
}

$user = $result->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alumni Dashboard | ConnectBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-container {
            margin-top: 50px;
        }
        .card-custom {
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .card-custom:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body>

<div class="container dashboard-container">
    <h2 class="text-center mb-4">Welcome, <?= ucfirst($user['name']) ?> (Alumni)</h2>

    <div class="row g-4">

        <!-- Profile Management -->
        <div class="col-md-4">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="card-title">My Profile</h5>
                    <p class="card-text">View and update your alumni profile.</p>
                    <a href="edit_profile.php" class="btn btn-primary">Manage Profile</a>
                </div>
            </div>
        </div>

        <!-- Mentorship Participation -->
        <div class="col-md-4">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="card-title">Mentorship</h5>
                    <p class="card-text">Volunteer to guide current students.</p>
                    <a href="mentorship_panel.php" class="btn btn-success">Mentor Students</a>
                </div>
            </div>
        </div>

 
        <!-- Job/Internship Posting -->
        <div class="col-md-4">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="card-title">Post Jobs</h5>
                    <p class="card-text">Post job and internship opportunities for students.</p>
                    <a href="post_job.php" class="btn btn-warning">Post Opportunities</a>
                </div>
            </div>
        </div>

      

  

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

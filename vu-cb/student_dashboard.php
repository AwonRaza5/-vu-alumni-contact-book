<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include ('db.php');
include ('navbar.php');

$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, user_type FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Dashboard | ConnectBook</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Arial', sans-serif;
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
        .btn-custom {
            background: #007bff;
            color: white;
            font-size: 1rem;
            padding: 10px 20px;
            border-radius: 25px;
            transition: 0.3s;
            text-decoration: none;
        }
        .btn-custom:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>



<div class="container dashboard-container">
    <h2 class="text-center mb-4">Welcome, <?= ucfirst($user['name']) ?>!</h2>
<!DOCTYPE html>
<html lang="en">
<head>  
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-4">
    <h2 class="text-center mb-4">Student Dashboard</h2>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title">Profile</h5>
            <p class="card-text">View and update your academic profile.</p>
            <a href="edit_profile.php" class="btn btn-primary">Manage Profile</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title">Request Mentorship</h5>
            <p class="card-text">Request guidance from alumni in your field of interest.</p>
            <a href="request_mentorship.php" class="btn btn-success">Find Mentors</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title">Events</h5>
            <p class="card-text">View and register for upcoming events.</p>
            <a href="student_events.php" class="btn btn-info">Event List</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title">Job & Internship</h5>
            <p class="card-text">Apply for available jobs and internships.</p>
            <a href="view_jobs.php" class="btn btn-warning">View Opportunities</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title">Discussion Forum</h5>
            <p class="card-text">Participate in discussions with alumni.</p>
            <a href="forum.php" class="btn btn-dark">Join Forum</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

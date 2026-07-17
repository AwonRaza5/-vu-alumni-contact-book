<?php

include ('db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>VU Alumni-Student ConnectBook</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background Image */
        body {
            background: url('bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            font-family: 'Arial', sans-serif;
        }

        /* Overlay effect for better readability */
        .overlay {
            background: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Centered content */
        .content-box {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 100px 20px;
        }

        .welcome-text {
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .lead {
            font-size: 1.3rem;
            margin-top: 10px;
        }

        .btn-custom {
            background: #ffcc00;
            color: #000;
            font-size: 1.2rem;
            padding: 12px 25px;
            border-radius: 25px;
            transition: 0.3s;
            text-decoration: none;
        }

        .btn-custom:hover {
            background: #ffaa00;
            color: #000;
        }
    </style>
</head>
<body>

<div class="overlay"></div>

<?php include 'navbar.php'; ?>

<div class="container text-center content-box">
    <h2 class="welcome-text">VU Alumni-Student ConnectBook</h2>
    <p class="lead">A place where students and alumni connect, share experiences, and grow together.</p>
    <a href="register.php" class="btn btn-custom mt-4">Join Now</a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">ConnectBook</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_type'])) {

    /* ===== Student Links ===== */
    if ($_SESSION['user_type'] === 'student') {
        echo '
            <li class="nav-item"><a class="nav-link" href="student_dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="edit_profile.php">Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="student_events.php">Events</a></li>
            <li class="nav-item"><a class="nav-link" href="view_jobs.php">Jobs/Internship</a></li>
            <li class="nav-item"><a class="nav-link" href="request_mentorship.php">Mentorship</a></li>
            <li class="nav-item"><a class="nav-link" href="forum.php">Forums</a></li>

            
            <li class="nav-item"><a class="nav-link" href="view_news.php">News</a></li>

            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        ';
    }

    /* ===== Alumni Links ===== */
    elseif ($_SESSION['user_type'] === 'alumni') {
        echo '
            <li class="nav-item"><a class="nav-link" href="alumni_dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="edit_profile.php">Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="post_job.php">Jobs</a></li>
            <li class="nav-item"><a class="nav-link" href="view_applications.php">Application</a></li>
            <li class="nav-item"><a class="nav-link" href="mentorship_panel.php">Mentorship</a></li>

           
            <li class="nav-item"><a class="nav-link" href="view_news.php">News</a></li>

            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        ';
    }

} else {
    /* ===== Visitor Links ===== */
    echo '
        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_login.php">Admin Login</a></li>
    ';
}
?>
            </ul>
        </div>
    </div>
</nav>

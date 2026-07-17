<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="admin_dashboard.php">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <!-- User Management -->
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">Manage Users</a>
                </li>

                <!-- Event Management -->
                <li class="nav-item">
                    <a class="nav-link" href="manage_events.php">Manage Events</a>
                </li>

                <!-- Job & Internship -->
                <li class="nav-item">
                    <a class="nav-link" href="manage_jobs.php">Job & Internship Opportunities</a>
                </li>

                <!-- Reward System -->
                <li class="nav-item">
                    <a class="nav-link" href="manage_rewards.php">Reward System</a>
                </li>

                <!-- Forum Moderation -->
                <li class="nav-item">
                    <a class="nav-link" href="manage_forum.php">Moderate Forum</a>
                </li>

                <!-- Analytics -->
                <li class="nav-item">
                    <a class="nav-link" href="admin_analytics.php">Data Analytics</a>
                </li>

                <!-- News -->
                <li class="nav-item">
                    <a class="nav-link" href="manage_news.php">News Management</a>
                </li>

            </ul>
            <div class="d-flex">
                <a href="admin_logout.php" class="btn btn-danger ms-3">Logout</a>
            </div>
        </div>
    </div>
</nav>

<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id']) ) {
    header("Location: login.php");
    exit();
}

include("db.php");
include("navbar.php");
// Fetch events from the database
$query = "SELECT * FROM events ORDER BY created_at DESC";
$events_result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlumniS Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Upcoming Events</h2>

    <?php if ($events_result->num_rows > 0): ?>
        <?php while ($event = $events_result->fetch_assoc()): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($event['title']) ?></h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                    <p class="text-muted">Date: <?= htmlspecialchars($event['date']) ?> | Time: <?= htmlspecialchars($event['time']) ?></p>
                    <p class="text-muted">Location: <?= htmlspecialchars($event['location']) ?></p>

                    <!-- Participate Button -->
                    <a href="participate_event.php?id=<?= $event['id'] ?>" class="btn btn-success">Participate</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info">No upcoming events at the moment.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

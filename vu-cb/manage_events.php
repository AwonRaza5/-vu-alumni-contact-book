<?php
include('db.php');
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Add Event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    mysqli_query($conn, "INSERT INTO events (title, description, date, time, location) 
                         VALUES ('$title', '$description', '$date', '$time', '$location')");
}

// Delete Event
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM events WHERE id = $id");
    header("Location: manage_events.php");
    exit();
}

// Fetch Events
$events = mysqli_query($conn, "SELECT * FROM events ORDER BY date ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Events | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'admin_navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center">Manage Events</h2>

    <div class="card my-4">
        <div class="card-header bg-primary text-white">Add New Event</div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label>Title:</label>
                    <input type="text" name="title" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Description:</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label>Date:</label>
                        <input type="date" name="date" class="form-control">
                    </div>
                    <div class="col">
                        <label>Time:</label>
                        <input type="time" name="time" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label>Location:</label>
                    <input type="text" name="location" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Add Event</button>
            </form>
        </div>
    </div>

    <h4>Upcoming Events</h4>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Date/Time</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($event = mysqli_fetch_assoc($events)): ?>
            <tr>
                <td><?= $event['id'] ?></td>
                <td><?= $event['title'] ?></td>
                <td><?= $event['date'].' '.$event['time'] ?></td>
                <td><?= $event['location'] ?></td>
                <td>
                    <a href="manage_events.php?delete=<?= $event['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this event?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

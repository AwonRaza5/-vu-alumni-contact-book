<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id']) ) {
    header("Location: login.php");
    exit();
}

include("db.php");
include("navbar.php");

$alumni_id = $_SESSION['user_id'];
$event_id = $_GET['id'] ?? null;

// If no event id is passed, redirect to events page
if (!$event_id) {
    header("Location: student_events.php");
    exit();
}

// Check if the alumni has already participated in this event
$query = "SELECT * FROM participations WHERE event_id = ? AND alumni_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $event_id, $alumni_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('You have already participated in this event.'); window.location='student_events.php';</script>";
    exit();
}

// Register the alumni for the event (participation)
$query = "INSERT INTO participations (event_id, alumni_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $event_id, $alumni_id);

if ($stmt->execute()) {
    echo "<script>alert('You have successfully participated in the event.'); window.location='student_events.php';</script>";
} else {
    echo "<script>alert('Error participating in the event.'); window.location='student_events.php';</script>";
}
?>

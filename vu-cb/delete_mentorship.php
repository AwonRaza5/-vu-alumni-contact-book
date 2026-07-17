<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumni') {
    header("Location: login.php");
    exit();
}

include("db.php");
$alumni_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM mentorships WHERE id = ? AND alumni_id = ?");
    $stmt->bind_param("ii", $id, $alumni_id);
    $stmt->execute();
}

header("Location: mentorship_panel.php"); // Redirect back to main page
exit();

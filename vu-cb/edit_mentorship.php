<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumni') {
    header("Location: login.php");
    exit();
}

include("db.php");

$alumni_id = (int)$_SESSION['user_id'];
$mentorship_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch existing mentorship offer
$stmt = $conn->prepare("SELECT * FROM mentorships WHERE id = ? AND alumni_id = ?");
$stmt->bind_param("ii", $mentorship_id, $alumni_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-danger'>Mentorship offer not found.</div>";
    exit();
}
$mentorship = $result->fetch_assoc();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $field = $conn->real_escape_string($_POST['field']);
    $description = $conn->real_escape_string($_POST['description']);

    // Optional new picture
    $picClause = "";
    if (!empty($_FILES['picture']['name'])) {
        $allowed = ['image/jpeg', 'image/png'];
        if (in_array($_FILES['picture']['type'], $allowed) && $_FILES['picture']['size'] < 2 * 1024 * 1024) {
            $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            $newName = uniqid("mentor_") . "." . $ext;
            $dest = "uploads/mentorships/" . $newName;
            if (move_uploaded_file($_FILES['picture']['tmp_name'], $dest)) {
                $picClause = ", picture = '$dest'";
            }
        }
    }

    $query = "UPDATE mentorships SET title = '$title', field = '$field', description = '$description' $picClause WHERE id = $mentorship_id AND alumni_id = $alumni_id";
    if ($conn->query($query)) {
        header("Location: mentorship_panel.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error updating mentorship.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Mentorship | ConnectBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Edit Mentorship Offer</h2>
    <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" class="form-control" required value="<?= htmlspecialchars($mentorship['title']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Field</label>
            <input name="field" class="form-control" required value="<?= htmlspecialchars($mentorship['field']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="4" class="form-control" required><?= htmlspecialchars($mentorship['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Change Picture (optional)</label>
            <input type="file" name="picture" accept=".jpg,.jpeg,.png" class="form-control">
            <?php if ($mentorship['picture']): ?>
                <p class="mt-2"><a href="<?= $mentorship['picture'] ?>" target="_blank">Current Picture</a></p>
            <?php endif; ?>
        </div>
        <button class="btn btn-success">Update</button>
        <a href="mentorship_panel.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>

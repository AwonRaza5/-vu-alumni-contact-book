<?php
/***** 1. session + role check *****/
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumni') {
    header("Location: login.php");
    exit();
}

include "db.php";
include "navbar.php";

$alumni_id = (int)$_SESSION['user_id'];

/***** 2. Handle Accept / Reject on incoming requests *****/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $request_id = (int)$_POST['request_id'];
    $newStatus  = $_POST['action'] === 'accept' ? 'Accepted' : 'Rejected';
    $stmt = $conn->prepare(
        "UPDATE mentorship_requests SET status = ? WHERE id = ? AND alumni_id = ?"
    );
    $stmt->bind_param("sii", $newStatus, $request_id, $alumni_id);
    $stmt->execute();
}

/***** 3. Handle new mentorship offer *****/
if (isset($_POST['new_offer'])) {
    $title       = $conn->real_escape_string($_POST['title']);
    $field       = $conn->real_escape_string($_POST['field']);
    $description = $conn->real_escape_string($_POST['description']);

    // Picture upload handling (optional)
    $picPath = null;
    if (!empty($_FILES['picture']['name'])) {
        $allowed = ['image/jpeg','image/png'];
        if (in_array($_FILES['picture']['type'], $allowed) && $_FILES['picture']['size'] < 2*1024*1024) {
            $ext      = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            $newName  = uniqid("mentor_").".".$ext;
            $dest     = "uploads/mentorships/".$newName;
            if (move_uploaded_file($_FILES['picture']['tmp_name'], $dest)) {
                $picPath = $dest;
            }
        }
    }

    $stmt = $conn->prepare(
        "INSERT INTO mentorships (alumni_id, title, field, description, picture)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("issss", $alumni_id, $title, $field, $description, $picPath);
    $stmt->execute();
}

/***** 4. Fetch data *****/
$pendings = $conn->query("
    SELECT mr.*, u.name AS student_name, u.email
    FROM mentorship_requests mr
    JOIN users u ON u.id = mr.student_id
    WHERE mr.alumni_id = $alumni_id
    ORDER BY mr.created_at DESC
");

$offers = $conn->query("
    SELECT * FROM mentorships
    WHERE alumni_id = $alumni_id
    ORDER BY created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mentorship Panel | ConnectBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .mentor-img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .mentor-card {
            max-width: 100%;
            max-height: 100%;
        }
        .more-description {
            display: none;
        }
    </style>
</head>
<body>
<div class="container mt-5">

    <!-- ==================== Create Mentorship Offer =================== -->
    <h2 class="mb-3 text-center">Create a Mentorship Offer</h2>
    <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm mb-5">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Title</label>
                <input name="title" class="form-control" required placeholder="e.g. Front‑End Development Mentorship">
            </div>
            <div class="col-md-6">
                <label class="form-label">Field</label>
                <input name="field" class="form-control" required placeholder="e.g. Web Development">
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Picture (optional, JPG/PNG ≤ 2 MB)</label>
                <input type="file" name="picture" accept=".jpg,.jpeg,.png" class="form-control">
            </div>
            <div class="col-12 text-end">
                <button name="new_offer" class="btn btn-primary">Publish Offer</button>
            </div>
        </div>
    </form>

    <!-- ==================== Your Mentorship Offers =================== -->
    <h3 class="mb-3">My Mentorship Offers</h3>
    <?php if ($offers->num_rows): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        <?php while ($off = $offers->fetch_assoc()): ?>
            <div class="col">
                <div class="card mentor-card shadow-sm">
                    <?php if ($off['picture']): ?>
                        <img src="<?= $off['picture'] ?>" class="mentor-img card-img-top" alt="Mentorship Picture">
                    <?php else: ?>
                        <div class="mentor-img d-flex align-items-center justify-content-center bg-light text-muted">No Image</div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($off['title']) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($off['field']) ?></h6>
                        <p class="card-text short-desc"><?= substr(htmlspecialchars($off['description']), 0, 100) ?>...</p>
                        <p class="card-text more-description"><?= nl2br(htmlspecialchars($off['description'])) ?></p>
                        <p class="text-muted mb-2">Posted on <?= date('d-M-Y', strtotime($off['created_at'])) ?></p>
                        <button class="btn btn-link p-0 mb-2" onclick="this.previousElementSibling.style.display='block'; this.style.display='none';">More Details</button>

                        <div class="d-flex justify-content-between">
                            <a href="edit_mentorship.php?id=<?= $off['id'] ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                            <form method="POST" action="delete_mentorship.php" onsubmit="return confirm('Are you sure you want to delete this offer?');">
                                <input type="hidden" name="id" value="<?= $off['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info mb-5">You haven’t published any mentorship offers yet.</div>
    <?php endif; ?>

    <!-- ==================== Incoming Requests =================== -->
    <h3 class="mb-3">Incoming Mentorship Requests</h3>
    <?php if ($pendings->num_rows): ?>
        <?php while ($row = $pendings->fetch_assoc()): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <?= htmlspecialchars($row['student_name']) ?>
                        (<a href="mailto:<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></a>)
                    </h5>
                    <h6 class="card-subtitle mb-2 text-muted">Field: <?= htmlspecialchars($row['field']) ?></h6>
                    <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($row['message'])) ?></p>
                    <p><strong>Status:</strong> <?= $row['status'] ?></p>

                    <?php if ($row['status'] === 'Pending'): ?>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info">No mentorship requests yet.</div>
    <?php endif; ?>
</div>
</body>
</html>

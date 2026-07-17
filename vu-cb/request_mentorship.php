<?php
session_start();
if (!isset($_SESSION['user_id']) ) {
    header("Location: login.php");
    exit();
}

include "db.php";
include "navbar.php";


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['user_id'];
    $alumni_id = $_POST['alumni_id'];
    $field = $_POST['field'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO mentorship_requests (student_id, alumni_id, field, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $student_id, $alumni_id, $field, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Mentorship request sent successfully!'); window.location='request_mentorship.php';</script>";
    } else {
        echo "<script>alert('Error sending request.');</script>";
    }
}

// Fetch alumni list
$alumni_query = "SELECT id, name FROM users";
$alumni_result = $conn->query($alumni_query);

$myReq = $conn->query("
    SELECT mr.*, u.name AS alumni_name
    FROM mentorship_requests mr
    JOIN users u ON u.id = mr.alumni_id
    WHERE mr.student_id = {$_SESSION['user_id']}
    ORDER BY mr.created_at DESC
");

$offers = $conn->query("
    SELECT m.*, u.name AS alumni_name, u.email
    FROM mentorships m
    JOIN users u ON u.id = m.alumni_id
    ORDER BY m.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Mentorship</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Request Mentorship</h2>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="alumni_id" class="form-label">Select Alumni</label>
            <select class="form-select" name="alumni_id" id="alumni_id" required>
                <option value="">-- Choose Alumni --</option>
                <?php while ($row = $alumni_result->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="field" class="form-label">Field of Mentorship</label>
            <input type="text" class="form-control" name="field" id="field" required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message (Optional)</label>
            <textarea class="form-control" name="message" id="message" rows="4"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Send Request</button>
    </form>

     <!-- ===== Your Requests List ===== -->
    <h3 class="mt-5 mb-3">My Mentorship Requests</h3>
    <?php if ($myReq->num_rows): ?>
        <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Alumni</th>
                <th>Field</th>
                <th>Message</th>
                <th>Status</th>
                <th>Requested On</th>
              </tr>
            </thead>
            <tbody>
            <?php $i=1; while ($r = $myReq->fetch_assoc()): ?>
              <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($r['alumni_name']) ?></td>
                <td><?= htmlspecialchars($r['field']) ?></td>
                <td><?= htmlspecialchars($r['message']) ?: '—' ?></td>
                <td><span class="badge bg-secondary"><?= $r['status'] ?></span></td>
                <td><?= date('d‑M‑Y', strtotime($r['created_at'])) ?></td>
              </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">You haven’t submitted any mentorship requests yet.</div>
    <?php endif; ?>

    <!-- ===== Available Mentorship Offers ===== -->
<h3 class="mt-5 mb-3">Available Mentorship Offers</h3>
<?php if ($offers->num_rows): ?>
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php while ($off = $offers->fetch_assoc()): ?>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <?php if ($off['picture']): ?>
            <img src="<?= $off['picture'] ?>" class="card-img-top" style="height:150px; object-fit:cover;">
          <?php endif; ?>
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($off['title']) ?></h5>
            <h6 class="card-subtitle text-muted mb-2"><?= htmlspecialchars($off['field']) ?></h6>
            <p class="card-text mb-3" style="font-size:14px;">
              <?= strlen($off['description']) > 120
                  ? htmlspecialchars(substr($off['description'],0,120)).'...'
                  : htmlspecialchars($off['description']) ?>
            </p>
            <div class="mt-auto">
              <!-- Fills the main request form via JS -->
              <button
                class="btn btn-sm btn-primary w-100"
                onclick="
                  document.getElementById('alumni_id').value = '<?= $off['alumni_id'] ?>';
                  document.getElementById('field').value = '<?= htmlspecialchars($off['field'],ENT_QUOTES) ?>';
                  window.scrollTo({top:0, behavior:'smooth'});
                "
              >
                Request Mentorship
              </button>
            </div>
          </div>
          <div class="card-footer text-muted" style="font-size:12px;">
            Mentor: <?= htmlspecialchars($off['alumni_name']) ?>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
<?php else: ?>
  <div class="alert alert-info">No mentorship offers available at the moment.</div>
<?php endif; ?>

</div>
</body>
</html>

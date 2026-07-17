<?php
include("db.php");
session_start();

// 1.  Authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    header("Location: login.php");
    exit();
}
$student_id = (int)$_SESSION['user_id'];

// 2.  Job ID validate
if (!isset($_GET['id'])) {
    header("Location: view_jobs.php");
    exit();
}
$job_id = (int)$_GET['id'];

// 3.  Job details
$stmtJob = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
$stmtJob->bind_param("i", $job_id);
$stmtJob->execute();
$job = $stmtJob->get_result()->fetch_assoc();
if (!$job) {
    echo "<script>alert('Job not found.'); window.location='view_jobs.php';</script>";
    exit();
}

// 4.  Already‑applied check (re‑use twice, so run once!)
$stmtCheck = $conn->prepare(
    "SELECT id, applied_at 
     FROM job_applications 
     WHERE job_id = ? AND student_id = ?"
);
$stmtCheck->bind_param("ii", $job_id, $student_id);
$stmtCheck->execute();
$appInfo = $stmtCheck->get_result()->fetch_assoc();
$alreadyApplied = (bool)$appInfo;

// 5.  Handle **new** application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$alreadyApplied) {
    $stmtInsert = $conn->prepare(
        "INSERT INTO job_applications (job_id, student_id) VALUES (?, ?)"
    );
    $stmtInsert->bind_param("ii", $job_id, $student_id);
    if ($stmtInsert->execute()) {
        // refresh to avoid form‑resubmit & update $alreadyApplied
        header("Location: apply_job.php?id=".$job_id);
        exit();
    } else {
        $err = "There was an error submitting your application.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Apply for Job | ConnectBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body        {background:#f5f5f5;}
        .job-detail {border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.1);padding:20px;}
        .badge-job          {background:#198754;}
        .badge-internship   {background:#0d6efd;}
    </style>
</head>
<body>
<?php include("navbar.php"); ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">Job Details – <?= htmlspecialchars($job['title']) ?></h2>

    <div class="card job-detail mx-auto" style="max-width:760px">
        <div class="card-body">
            <h5 class="card-title">
                <?= htmlspecialchars($job['title']) ?>
                <span class="badge <?= $job['type']=='Job' ? 'badge-job':'badge-internship' ?>">
                    <?= $job['type'] ?>
                </span>
            </h5>

            <p class="card-text"><?= nl2br(htmlspecialchars($job['description'])) ?></p>

            <ul class="list-unstyled text-muted">
                <li>Deadline: <?= htmlspecialchars($job['deadline']) ?></li>
                <li>Posted on: <?= date("M d, Y", strtotime($job['created_at'])) ?></li>
            </ul>

            <!-- 6.  Apply / Already‑applied message -->
            <?php if ($alreadyApplied): ?>
                <div class="alert alert-success d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    You already applied on <?= date("d‑M‑Y h:i A", strtotime($appInfo['applied_at'])) ?>.
                </div>
            <?php else: ?>
                <?php if (!empty($err)): ?>
                    <div class="alert alert-danger"><?= $err ?></div>
                <?php endif; ?>
                <form method="POST">
                    <button type="submit" class="btn btn-primary" name="apply_job">
                        Apply for this <?= $job['type'] ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- 7.  Application snapshot section (visible only if applied) -->
  <?php if ($alreadyApplied): ?>
    <div class="card mt-4 mx-auto" style="max-width:760px">
        <div class="card-header fw-bold bg-light">My Application Details</div>
        <div class="card-body">

            <p><strong>Job Title:</strong> <?= htmlspecialchars($job['title']) ?></p>

            <p><strong>Type:</strong>
                <span class="badge <?= $job['type'] == 'Job' ? 'badge-job' : 'badge-internship' ?>">
                    <?= $job['type'] ?>
                </span>
            </p>

            <p><strong>Description:</strong><br>
                <?= nl2br(htmlspecialchars($job['description'])) ?>
            </p>

            <p><strong>Application Deadline:</strong>
                <?= date("d-M-Y", strtotime($job['deadline'])) ?>
            </p>

            <p><strong>Applied At:</strong>
                <?= date("d-M-Y h:i A", strtotime($appInfo['applied_at'])) ?>
            </p>

            <p><strong>Status:</strong>
                <span class="badge bg-info text-dark">
                    <?= $appInfo['status'] ?? 'Pending' ?>
                </span>
            </p>

         

        </div>
    </div>
<?php endif; ?>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- optional icons for success message -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>

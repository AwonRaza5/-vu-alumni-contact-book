<?php

session_start();
include 'db.php';
include 'navbar.php';

// Fetch latest news (newest first)
$news = $conn->query("SELECT * FROM news ORDER BY date_posted DESC, id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Latest News | ConnectBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .news-img-placeholder {
            width: 100%;
            height: 140px;
            background: #f1f3f5;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#adb5bd;
            font-size: 24px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Campus & Community News</h2>

    <?php if ($news->num_rows): ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php while ($n = $news->fetch_assoc()): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <!-- Optional image placeholder -->
                    <div class="news-img-placeholder">News</div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($n['title']) ?></h5>
                        <p class="card-text" style="font-size:14px;">
                            <?= strlen($n['content']) > 120
                                ? htmlspecialchars(substr($n['content'],0,120)).'…'
                                : htmlspecialchars($n['content']) ?>
                        </p>
                        <span class="text-muted mt-auto" style="font-size:12px;">
                            Posted <?= date('d M Y', strtotime($n['date_posted'])) ?>
                        </span>
                        <!-- Offcanvas trigger -->
                        <button class="btn btn-link p-0 mt-2" data-bs-toggle="offcanvas" data-bs-target="#news<?= $n['id'] ?>">Read More</button>
                    </div>
                </div>
            </div>

            <!-- Offcanvas full content -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="news<?= $n['id'] ?>">
              <div class="offcanvas-header">
                <h5 class="offcanvas-title"><?= htmlspecialchars($n['title']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
              </div>
              <div class="offcanvas-body">
                <p style="white-space:pre-line;"><?= htmlspecialchars($n['content']) ?></p>
                <hr>
                <small class="text-muted">Posted on <?= date('d M Y', strtotime($n['date_posted'])) ?></small>
              </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">No news posted yet.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
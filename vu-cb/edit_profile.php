<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch current user details
$query = "SELECT name, email, current_occupation, contact_details, current_course, year_of_study, interests 
          FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $current_occupation = $_POST['current_occupation'];
    $contact_details = $_POST['contact_details'];
    $current_course = $_POST['current_course'];
    $year_of_study = $_POST['year_of_study'];
    $interests = $_POST['interests'];

    // Update user details
    $update_query = "UPDATE users SET 
                        name='$name', 
                        email='$email', 
                        current_occupation='$current_occupation', 
                        contact_details='$contact_details', 
                        current_course='$current_course', 
                        year_of_study='$year_of_study', 
                        interests='$interests' 
                    WHERE id=$user_id";

    if (mysqli_query($conn, $update_query)) {
        $message = "Profile updated successfully!";
        // Refresh user data
        $user['name'] = $name;
        $user['email'] = $email;
        $user['current_occupation'] = $current_occupation;
        $user['contact_details'] = $contact_details;
        $user['current_course'] = $current_course;
        $user['year_of_study'] = $year_of_study;
        $user['interests'] = $interests;
    } else {
        $message = "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Profile | ConnectBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2>Edit Profile</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Occupation:</label>
            <input type="text" name="current_occupation" class="form-control" value="<?php echo $user['current_occupation']; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Contact Details:</label>
            <input type="text" name="contact_details" class="form-control" value="<?php echo $user['contact_details']; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Current Course:</label>
            <input type="text" name="current_course" class="form-control" value="<?php echo $user['current_course']; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Year of Study:</label>
            <input type="number" name="year_of_study" class="form-control" value="<?php echo $user['year_of_study']; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Interests:</label>
            <textarea name="interests" class="form-control"><?php echo $user['interests']; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

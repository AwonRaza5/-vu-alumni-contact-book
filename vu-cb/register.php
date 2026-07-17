<?php
include ('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];
    
    if ($user_type == 'student') {
        $student_id = $_POST['student_id'];
        $current_course = $_POST['current_course'];
        $year_of_study = $_POST['year_of_study'];
        $interests = $_POST['interests'];
        $query = "INSERT INTO users (name, email, password, user_type, student_id, current_course, year_of_study, interests) 
                  VALUES ('$name', '$email', '$password', '$user_type', '$student_id', '$current_course', '$year_of_study', '$interests')";
    } else {
        $graduation_year = $_POST['graduation_year'];
        $degree = $_POST['degree'];
        $current_occupation = $_POST['current_occupation'];
        $contact_details = $_POST['contact_details'];
        $query = "INSERT INTO users (name, email, password, user_type, graduation_year, degree, current_occupation, contact_details) 
                  VALUES ('$name', '$email', '$password', '$user_type', '$graduation_year', '$degree', '$current_occupation', '$contact_details')";
    }

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Registration successful! Wait for admin approval.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Register</h2>
            <form method="POST" class="p-4 border rounded bg-light">
                <div class="mb-3">
                    <label class="form-label">Name:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">User Type:</label>
                    <select name="user_type" class="form-select" onchange="toggleFields()" required>
                        <option value="student">Student</option>
                        <option value="alumni">Alumni</option>
                    </select>
                </div>

                <!-- Student Fields -->
                <div id="studentFields">
                    <div class="mb-3">
                        <label class="form-label">Student ID:</label>
                        <input type="text" name="student_id" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Course:</label>
                        <input type="text" name="current_course" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Year of Study:</label>
                        <input type="number" name="year_of_study" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Interests:</label>
                        <textarea name="interests" class="form-control"></textarea>
                    </div>
                </div>

                <!-- Alumni Fields -->
                <div id="alumniFields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Graduation Year:</label>
                        <input type="number" name="graduation_year" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Degree:</label>
                        <input type="text" name="degree" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Occupation:</label>
                        <input type="text" name="current_occupation" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Details:</label>
                        <input type="text" name="contact_details" class="form-control">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function toggleFields() {
        let userType = document.querySelector('select[name="user_type"]').value;
        document.getElementById('studentFields').style.display = userType === 'student' ? 'block' : 'none';
        document.getElementById('alumniFields').style.display = userType === 'alumni' ? 'block' : 'none';
    }
</script>

</body>
</html>

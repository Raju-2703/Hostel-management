<?php
include 'db_connect.php';

// Get the student ID from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) {
    die("Invalid student ID.");
}

// Fetch the current record
$result = $conn->query("SELECT * FROM students WHERE student_id = $id LIMIT 1");
if ($result->num_rows === 0) {
    die("Student not found.");
}
$student = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Edit Student</h1>

    <form action="update_student.php" method="POST" class="form">
        <input type="hidden" name="id" value="<?= $student['student_id'] ?>">

        <label>First Name</label>
        <input type="text" name="first_name" value="<?= $student['first_name'] ?>" required>

        <label>Last Name</label>
        <input type="text" name="last_name" value="<?= $student['last_name'] ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= $student['email'] ?>" required>

        <label>Date of Birth</label>
        <input type="date" name="dob" value="<?= $student['date_of_birth'] ?>">

        <label>Contact Number</label>
        <input type="text" name="contact_number" value="<?= $student['contact_number'] ?>">

        <label>Home Address</label>
        <textarea name="home_address"><?= $student['home_address'] ?></textarea>

        <label>Emergency Contact Name</label>
        <input type="text" name="emergency_contact_name" value="<?= $student['emergency_contact_name'] ?>">

        <label>Emergency Contact Number</label>
        <input type="text" name="emergency_contact_number" value="<?= $student['emergency_contact_number'] ?>">

        <label>Gender</label>
        <select name="gender" required>
            <option value="Male" <?= $student['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $student['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= $student['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>

        <button type="submit" class="btn">Save Changes</button>
    </form>

    <p><a href="index.php">← Back to List</a></p>
</body>
</html>
<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Add New Student</h1>

    <form action="insert_student.php" method="POST" class="form">
        <label>First Name</label>
        <input type="text" name="first_name" required>

        <label>Last Name</label>
        <input type="text" name="last_name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Date of Birth</label>
        <input type="date" name="dob">

        <label>Contact Number</label>
        <input type="text" name="contact_number">

        <label>Home Address</label>
        <textarea name="home_address"></textarea>

        <label>Emergency Contact Name</label>
        <input type="text" name="emergency_contact_name">

        <label>Emergency Contact Number</label>
        <input type="text" name="emergency_contact_number">

        <label>Gender</label>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <button type="submit" class="btn">Add Student</button>
    </form>

    <p><a href="index.php">← Back to List</a></p>
</body>
</html>
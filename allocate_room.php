<?php
include 'db_connect.php';

// Fetch students without active allocations
$students_result = $conn->query("
    SELECT * FROM students 
    WHERE student_id NOT IN (
        SELECT student_id FROM allocations WHERE status = 'Active'
    )
");

// Fetch available rooms
$rooms_result = $conn->query("
    SELECT r.*, h.name as hostel_name 
    FROM rooms r 
    JOIN hostels h ON r.hostel_id = h.hostel_id 
    WHERE r.status = 'Available' AND r.current_occupancy < r.capacity
");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Allocate Room</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Allocate Room to Student</h1>

    <form action="insert_allocation.php" method="POST" class="form">
        <label>Select Student</label>
        <select name="student_id" required>
            <option value="">Select a Student</option>
            <?php while ($student = $students_result->fetch_assoc()): ?>
                <option value="<?= $student['student_id'] ?>">
                    <?= $student['first_name'] . ' ' . $student['last_name'] ?> (<?= $student['email'] ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <label>Select Room</label>
        <select name="room_id" required>
            <option value="">Select a Room</option>
            <?php while ($room = $rooms_result->fetch_assoc()): ?>
                <option value="<?= $room['room_id'] ?>">
                    <?= $room['hostel_name'] ?> - Room <?= $room['room_number'] ?> 
                    (<?= $room['room_type'] ?>, Capacity: <?= $room['capacity'] ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <label>Check-in Date</label>
        <input type="date" name="check_in_date" required value="<?= date('Y-m-d') ?>">

        <button type="submit" class="btn">Allocate Room</button>
    </form>

    <p><a href="index.php">← Back to List</a></p>
</body>
</html>
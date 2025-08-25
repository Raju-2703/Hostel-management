<?php
include 'db_connect.php';

// Get the student ID from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) {
    die("Invalid student ID.");
}

// Fetch the student details
$student_result = $conn->query("SELECT * FROM students WHERE student_id = $id LIMIT 1");
if ($student_result->num_rows === 0) {
    die("Student not found.");
}
$student = $student_result->fetch_assoc();

// Fetch allocation details
$allocation_result = $conn->query("
    SELECT a.*, h.name as hostel_name, r.room_number, r.room_type
    FROM allocations a
    JOIN hostels h ON a.hostel_id = h.hostel_id
    JOIN rooms r ON a.room_id = r.room_id
    WHERE a.student_id = $id AND a.status = 'Active'
    LIMIT 1
");

// Fetch payment history
$payments_result = $conn->query("
    SELECT * FROM payments 
    WHERE student_id = $id 
    ORDER BY payment_date DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>View Student</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Student Details</h1>
    
    <div class="actions">
        <a href="index.php" class="btn">← Back to List</a>
        <a href="edit_student.php?id=<?= $student['student_id'] ?>" class="btn">✏️ Edit</a>
    </div>

    <div class="form">
        <h2>Personal Information</h2>
        <p><strong>Name:</strong> <?= $student['first_name'] . ' ' . $student['last_name'] ?></p>
        <p><strong>Email:</strong> <?= $student['email'] ?></p>
        <p><strong>Date of Birth:</strong> <?= $student['date_of_birth'] ?></p>
        <p><strong>Contact Number:</strong> <?= $student['contact_number'] ?></p>
        <p><strong>Gender:</strong> <?= $student['gender'] ?></p>
        <p><strong>Home Address:</strong> <?= $student['home_address'] ?></p>
        <p><strong>Emergency Contact:</strong> <?= $student['emergency_contact_name'] ?> (<?= $student['emergency_contact_number'] ?>)</p>
    </div>

    <?php if ($allocation_result->num_rows > 0): ?>
        <?php $allocation = $allocation_result->fetch_assoc(); ?>
        <div class="form">
            <h2>Current Allocation</h2>
            <p><strong>Hostel:</strong> <?= $allocation['hostel_name'] ?></p>
            <p><strong>Room:</strong> <?= $allocation['room_number'] ?> (<?= $allocation['room_type'] ?>)</p>
            <p><strong>Check-in Date:</strong> <?= $allocation['check_in_date'] ?></p>
            <p><strong>Status:</strong> <?= $allocation['status'] ?></p>
        </div>
    <?php else: ?>
        <div class="form">
            <h2>Current Allocation</h2>
            <p>No active allocation found.</p>
        </div>
    <?php endif; ?>

    <div class="form">
        <h2>Payment History</h2>
        <?php if ($payments_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th><th>Amount</th><th>Due Date</th><th>Method</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($payment = $payments_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $payment['payment_date'] ?></td>
                            <td><?= $payment['amount'] ?></td>
                            <td><?= $payment['due_date'] ?></td>
                            <td><?= $payment['payment_method'] ?></td>
                            <td><?= $payment['status'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No payment history found.</p>
        <?php endif; ?>
    </div>

    <p><a href="index.php">← Back to List</a></p>
</body>
</html>
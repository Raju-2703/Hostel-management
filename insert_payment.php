<?php
include 'db_connect.php';

// Fetch students with active allocations
$students_result = $conn->query("
    SELECT s.student_id, s.first_name, s.last_name, a.allocation_id, h.name as hostel_name
    FROM students s
    JOIN allocations a ON s.student_id = a.student_id
    JOIN hostels h ON a.hostel_id = h.hostel_id
    WHERE a.status = 'Active'
");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Payment</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Add New Payment</h1>

    <form action="process_payment.php" method="POST" class="form">
        <label>Select Student</label>
        <select name="student_id" required>
            <option value="">Select a Student</option>
            <?php while ($student = $students_result->fetch_assoc()): ?>
                <option value="<?= $student['student_id'] ?>">
                    <?= $student['first_name'] . ' ' . $student['last_name'] ?> (<?= $student['hostel_name'] ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <label>Amount</label>
        <input type="number" name="amount" step="0.01" required>

        <label>Payment Date</label>
        <input type="date" name="payment_date" required value="<?= date('Y-m-d') ?>">

        <label>Due Date</label>
        <input type="date" name="due_date" required value="<?= date('Y-m-d', strtotime('+1 month')) ?>">

        <label>Payment Method</label>
        <select name="payment_method" required>
            <option value="Cash">Cash</option>
            <option value="Card">Card</option>
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="Online">Online</option>
        </select>

        <label>Status</label>
        <select name="status" required>
            <option value="Paid">Paid</option>
            <option value="Pending">Pending</option>
            <option value="Overdue">Overdue</option>
        </select>

        <label>Description</label>
        <textarea name="description" placeholder="e.g., Monthly Hostel Fee"></textarea>

        <button type="submit" class="btn">Add Payment</button>
    </form>

    <p><a href="manage_payments.php">← Back to Payments</a></p>
</body>
</html>
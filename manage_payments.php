<?php
include 'db_connect.php';

// Fetch all payments with student details
$result = $conn->query("
    SELECT p.*, s.first_name, s.last_name, s.email, a.room_id, h.name as hostel_name
    FROM payments p
    JOIN students s ON p.student_id = s.student_id
    LEFT JOIN allocations a ON p.allocation_id = a.allocation_id
    LEFT JOIN hostels h ON a.hostel_id = h.hostel_id
    ORDER BY p.payment_date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Payments</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Payment Management</h1>

    <div class="actions">
        <a href="index.php" class="btn">← Back to Students</a>
        <a href="insert_payment.php" class="btn">➕ Add Payment</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Student</th><th>Amount</th><th>Date</th><th>Due Date</th><th>Status</th><th>Method</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['payment_id'] ?></td>
                    <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                    <td><?= $row['amount'] ?></td>
                    <td><?= $row['payment_date'] ?></td>
                    <td><?= $row['due_date'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['payment_method'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" class="center">No payments found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php
include 'db_connect.php';

// Fetch all students with their allocation details
$result = $conn->query("
    SELECT s.*, h.name as hostel_name, r.room_number, a.status as allocation_status 
    FROM students s 
    LEFT JOIN allocations a ON s.student_id = a.student_id 
    LEFT JOIN hostels h ON a.hostel_id = h.hostel_id 
    LEFT JOIN rooms r ON a.room_id = r.room_id 
    ORDER BY s.student_id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Hostel Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Student Hostel Management System</h1>

    <div class="actions">
        <a href="add_student.php" class="btn">➕ Add Student</a>
        <a href="allocate_room.php" class="btn">🏠 Allocate Room</a>
        <a href="manage_payments.php" class="btn">💰 Manage Payments</a>
        <a href="db_inspector.php" class="btn">🗄️ DB Inspector</a>
        <a href="index.php" class="btn secondary">⟳ Refresh</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Hostel</th><th>Room</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['student_id'] ?></td>
                    <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['hostel_name'] ?? 'Not allocated' ?></td>
                    <td><?= $row['room_number'] ?? '-' ?></td>
                    <td><?= $row['allocation_status'] ?? 'Not allocated' ?></td>
                    <td class="center">
                        <a href="edit_student.php?id=<?= $row['student_id'] ?>">✏️ Edit</a> |
                        <a href="view_student.php?id=<?= $row['student_id'] ?>">👁️ View</a> |
                        <a href="delete_student.php?id=<?= $row['student_id'] ?>" 
                           onclick="return confirm('Delete this student?');">🗑️ Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" class="center">No students found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
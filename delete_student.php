<?php
include 'db_connect.php';

// Get ID from URL and delete the record
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) {
    die("Invalid student ID.");
}

$sql = "DELETE FROM students WHERE student_id = $id";
if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
    exit();
} else {
    echo "❌ Error deleting record: " . $conn->error;
}
?>
<?php
include 'db_connect.php';

// Grab form fields
$student_id = intval($_POST['student_id']);
$amount = floatval($_POST['amount']);
$payment_date = $conn->real_escape_string($_POST['payment_date']);
$due_date = $conn->real_escape_string($_POST['due_date']);
$payment_method = $conn->real_escape_string($_POST['payment_method']);
$status = $conn->real_escape_string($_POST['status']);
$description = $conn->real_escape_string($_POST['description']);

// Get allocation_id for the student
$allocation_result = $conn->query("
    SELECT allocation_id FROM allocations 
    WHERE student_id = $student_id AND status = 'Active'
    LIMIT 1
");

if ($allocation_result->num_rows === 0) {
    die("No active allocation found for this student.");
}

$allocation = $allocation_result->fetch_assoc();
$allocation_id = $allocation['allocation_id'];

// Insert payment
$sql = "INSERT INTO payments 
        (allocation_id, student_id, amount, payment_date, due_date, payment_method, status, description)
        VALUES ($allocation_id, $student_id, $amount, '$payment_date', '$due_date', '$payment_method', '$status', '$description')";

if ($conn->query($sql) === TRUE) {
    header("Location: manage_payments.php");
    exit();
} else {
    echo "❌ Error: " . $conn->error;
}
?>
<?php
include 'db_connect.php';

// Grab form fields
$student_id = intval($_POST['student_id']);
$room_id = intval($_POST['room_id']);
$check_in_date = $conn->real_escape_string($_POST['check_in_date']);

// Get hostel_id from room
$room_result = $conn->query("SELECT hostel_id FROM rooms WHERE room_id = $room_id");
if ($room_result->num_rows === 0) {
    die("Invalid room selected.");
}
$room = $room_result->fetch_assoc();
$hostel_id = $room['hostel_id'];

// Insert allocation
$sql = "INSERT INTO allocations (student_id, room_id, hostel_id, check_in_date, status)
        VALUES ($student_id, $room_id, $hostel_id, '$check_in_date', 'Active')";

if ($conn->query($sql) === TRUE) {
    // Update room occupancy
    $conn->query("UPDATE rooms SET current_occupancy = current_occupancy + 1, status = 
        CASE WHEN current_occupancy + 1 >= capacity THEN 'Occupied' ELSE 'Available' END 
        WHERE room_id = $room_id");
    
    header("Location: index.php");
    exit();
} else {
    echo "❌ Error: " . $conn->error;
}
?>
<?php
include 'db_connect.php';

// Gather POST data safely
$id = intval($_POST['id']);
$first = $conn->real_escape_string($_POST['first_name']);
$last = $conn->real_escape_string($_POST['last_name']);
$email = $conn->real_escape_string($_POST['email']);
$dob = $conn->real_escape_string($_POST['dob']);
$contact = $conn->real_escape_string($_POST['contact_number']);
$address = $conn->real_escape_string($_POST['home_address']);
$emergency_name = $conn->real_escape_string($_POST['emergency_contact_name']);
$emergency_number = $conn->real_escape_string($_POST['emergency_contact_number']);
$gender = $conn->real_escape_string($_POST['gender']);

$sql = "UPDATE students SET
            first_name = '$first',
            last_name = '$last',
            email = '$email',
            date_of_birth = '$dob',
            contact_number = '$contact',
            home_address = '$address',
            emergency_contact_name = '$emergency_name',
            emergency_contact_number = '$emergency_number',
            gender = '$gender'
        WHERE student_id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
    exit();
} else {
    echo "❌ Error updating record: " . $conn->error;
}
?>
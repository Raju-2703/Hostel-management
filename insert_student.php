<?php
include 'db_connect.php';

// Grab form fields
$first = $conn->real_escape_string($_POST['first_name']);
$last = $conn->real_escape_string($_POST['last_name']);
$email = $conn->real_escape_string($_POST['email']);
$dob = $conn->real_escape_string($_POST['dob']);
$contact = $conn->real_escape_string($_POST['contact_number']);
$address = $conn->real_escape_string($_POST['home_address']);
$emergency_name = $conn->real_escape_string($_POST['emergency_contact_name']);
$emergency_number = $conn->real_escape_string($_POST['emergency_contact_number']);
$gender = $conn->real_escape_string($_POST['gender']);

// Insert into DB
$sql = "INSERT INTO students 
        (first_name, last_name, email, date_of_birth, contact_number, home_address, emergency_contact_name, emergency_contact_number, gender)
        VALUES ('$first', '$last', '$email', '$dob', '$contact', '$address', '$emergency_name', '$emergency_number', '$gender')";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");   // back to list
    exit();
} else {
    echo "❌ Error: " . $conn->error;
}
?>
<?php
include 'config.php'; // Include your database connection

session_start();

// Check if the username session variable is set
if (!isset($_SESSION['username'])) {
    die("User is not logged in.");
}

$username = $_SESSION['username'];

// Get the form data
$name = $_POST['name'];
$id_number = $_POST['idnumber'];
$gender = $_POST['gender'];
$birthday = $_POST['birthday'];
$address = $_POST['address'];
$profile_picture = null;

// Handle profile picture upload if a file is provided
if (isset($_FILES['profilepicture']) && $_FILES['profilepicture']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($_FILES['profilepicture']['name']);
    if (move_uploaded_file($_FILES['profilepicture']['tmp_name'], $uploadFile)) {
        $profile_picture = $uploadFile;
    }
}

// Update user profile data in the database
if ($profile_picture) {
    $sql = "UPDATE users SET name = ?, id_number = ?, gender = ?, birthday = ?, address = ?, profile_picture = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $name, $id_number, $gender, $birthday, $address, $profile_picture, $username);
} else {
    $sql = "UPDATE users SET name = ?, id_number = ?, gender = ?, birthday = ?, address = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $id_number, $gender, $birthday, $address, $username);
}

if ($stmt->execute()) {
    // Redirect back to profile page
    header("Location: profile.php");
    exit();
} else {
    echo "Error updating profile: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>


<?php
include 'config.php'; // Include your database connection

session_start();

// Check if the username session variable is set
if (!isset($_SESSION['username'])) {
    die("User is not logged in.");
}

$username = $_SESSION['username'];

// Debugging: Check if the session username is set
if (empty($username)) {
    die("Username is not set in session.");
}

// Query to fetch user profile data
$sql = "SELECT username, name, id_number, gender, birthday, address FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

// Debugging: Check if statement preparation succeeded
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($username, $name, $id_number, $gender, $birthday, $address);
$stmt->fetch();
$stmt->close();

// Debugging: Check if any data was fetched
if (empty($username)) {
    die("No data found for the user.");
}

// Create a profile object or associative array
$profile = (object) [
    'username' => $username,
    'name' => $name,
    'idnumber' => $id_number,
    'gender' => $gender,
    'birthday' => $birthday,
    'address' => $address
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesprofile.css">
    <title>Profile</title>
</head>
<body>
    <div class="container">
        <h2>Profile</h2>
        <p>Username: <?php echo htmlspecialchars($profile->username); ?></p>
        <p>Name: <?php echo htmlspecialchars($profile->name); ?></p>
        <p>ID Number: <?php echo htmlspecialchars($profile->idnumber); ?></p>
        <p>Gender: <?php echo htmlspecialchars($profile->gender); ?></p>
        <p>Birthday: <?php echo htmlspecialchars($profile->birthday); ?></p>
        <p>Address: <?php echo htmlspecialchars($profile->address); ?></p>
        <a href="edit_profile.php">Edit Profile</a>
        <a href="auth.php?action=logout">Logout</a>
    </div>
</body>
</html>

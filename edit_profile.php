<?php
include 'config.php'; // Include your database connection

session_start();

// Check if the username session variable is set
if (!isset($_SESSION['username'])) {
    die("User is not logged in.");
}

$username = $_SESSION['username'];

// Query to fetch user profile data
$sql = "SELECT username, name, id_number, gender, birthday, address, profile_picture FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

// Debugging: Check if statement preparation succeeded
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($username, $name, $id_number, $gender, $birthday, $address, $profile_picture);
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
    'address' => $address,
    'profilepicture' => $profile_picture
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesprofile.css">
    <title>Edit Profile</title>
</head>
<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <form action="update_profile.php" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($profile->name); ?>" required>
            <br>

            <label for="idnumber">ID Number:</label>
            <input type="text" id="idnumber" name="idnumber" value="<?php echo htmlspecialchars($profile->idnumber); ?>" required>
            <br>

            <label for="gender">Gender:</label>
            <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($profile->gender); ?>" required>
            <br>

            <label for="birthday">Birthday:</label>
            <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($profile->birthday); ?>" required>
            <br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($profile->address); ?>" required>
            <br>

            <label for="profilepicture">Profile Picture:</label>
            <input type="file" id="profilepicture" name="profilepicture">
            <br>

            <button type="submit">Save</button>
        </form>
        <a href="profile.php">Back to Profile</a>
    </div>
</body>
</html>

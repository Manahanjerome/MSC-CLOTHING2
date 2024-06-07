<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username already exists
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='container'><p class='error'>Username already exists. <a href='signup.html'>Try again</a></p></div>";
    } else {
        // Insert new user into the database
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            header('Location: login.html');
        } else {
            echo "<div class='container'><p class='error'>Error: Could not sign up. <a href='signup.html'>Try again</a></p></div>";
        }
    }

    $stmt->close();
}

$conn->close();
?>

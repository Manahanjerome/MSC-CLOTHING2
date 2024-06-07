<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user from the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            header('Location: home.html');
        } else {
            echo "<div class='container'><p class='error'>Invalid password. <a href='login.html'>Try again</a></p></div>";
        }
    } else {
        echo "<div class='container'><p class='error'>User not found. <a href='login.html'>Try again</a></p></div>";
    }

    $stmt->close();
}

$conn->close();
?>

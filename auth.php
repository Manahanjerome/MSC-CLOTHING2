<?php
session_start();

function loadUserData() {
    return simplexml_load_file('users.xml');
}

function saveUserData($xml) {
    $xml->asXML('users.xml');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $action = $_POST['action'];

    $xml = loadUserData();

    if ($action === 'signup') {
        foreach ($xml->user as $user) {
            if ($user->username == $username) {
                echo "Username already exists!";
                exit();
            }
        }

        $newUser = $xml->addChild('user');
        $newUser->addChild('username', $username);
        $newUser->addChild('password', password_hash($password, PASSWORD_DEFAULT));
        $newUser->addChild('name', '');
        $newUser->addChild('idnumber', '');
        $newUser->addChild('gender', '');
        $newUser->addChild('birthday', '');
        $newUser->addChild('address', '');
        $newUser->addChild('profilepicture', '');

        saveUserData($xml);
        echo "Signup successful!";
    } elseif ($action === 'login') {
        foreach ($xml->user as $user) {
            if ($user->username == $username && password_verify($password, $user->password)) {
                $_SESSION['username'] = $username;
                header('Location: profile.php');
                exit();
            }
        }
        echo "Invalid username or password!";
    }
} elseif (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: index.html');
    exit();
}

<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teachers_companion";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL statement to fetch user by email
    $sql = "SELECT * FROM sign_up_tb WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct, proceed with authentication
            $_SESSION['user'] = $row; // Store user details in session
            header("Location: index.php");
            exit();
        } else {
            // Password is incorrect
            $_SESSION['error'] = "Invalid password";
        }
    } else {
        // No user found with that email
        $_SESSION['error'] = "No user found with that email";
    }
}

$conn->close();
?>
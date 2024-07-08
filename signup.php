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
    $name = $_POST['name'];
    $dept = $_POST['dept'];
    $address = $_POST['address'];
    $mobile_no = $_POST['mobile_no'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $designation = $_POST['designation'];
    $qualification = $_POST['qualification'];
    $date = $_POST['date'];

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute SQL statement to insert user data
    $sql = "INSERT INTO sign_up_tb (name, dept, address, mobile_no, email, password, designation, qualification, date)
            VALUES ('$name', '$dept', '$address', '$mobile_no', '$email', '$hashed_password', '$designation', '$qualification', '$date')";

    if ($conn->query($sql) === TRUE) {
        // Registration successful
        $_SESSION['success'] = "Registration successful! Please sign in.";
        header("Location: signin.html"); // Redirect to signin page
        exit();
    } else {
        // Registration failed
        $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
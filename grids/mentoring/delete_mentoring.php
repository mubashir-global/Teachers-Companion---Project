<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: signin.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teachers_companion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

$sql = "DELETE FROM mentoring_tb WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    header("Location: mentoring.php");
    exit();
} else {
    echo "Error: " . $conn->error;
}
?>
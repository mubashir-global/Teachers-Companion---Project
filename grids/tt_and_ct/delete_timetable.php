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
    $timetable_id = $_POST['timetable_id'];

    $sql_delete = "DELETE FROM timetable WHERE id='$timetable_id'";
    if ($conn->query($sql_delete) === TRUE) {
        header("Location: timetable_course.php"); // Redirect to timetable course page
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
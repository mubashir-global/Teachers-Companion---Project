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
    $day = $_POST['day'];
    $period = $_POST['period'];
    $subject = $_POST['subject'];

    $sql_update = "UPDATE timetable SET day='$day', period='$period', subject='$subject' WHERE id='$timetable_id'";
    if ($conn->query($sql_update) === TRUE) {
        header("Location: timetable_course.php"); // Redirect to timetable course page
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
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
    $course_taught_id = $_POST['course_taught_id'];
    $programme = $_POST['programme'];
    $course_taught = $_POST['course_taught'];
    $hours = $_POST['hours'];

    $sql_update = "UPDATE course_taught SET programme='$programme', course_taught='$course_taught', hours='$hours' WHERE id='$course_taught_id'";
    if ($conn->query($sql_update) === TRUE) {
        header("Location: timetable_course.php"); // Redirect to timetable course page
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
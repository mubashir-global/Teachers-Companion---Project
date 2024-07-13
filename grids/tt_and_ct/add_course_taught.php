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

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: signin.html"); // Redirect to signin page if not logged in
    exit();
}

// Fetch department from session
$teacher_dept = $_SESSION['user']['dept'];

// Process form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $programme = $_POST['programme'];
    $course_taught = $_POST['course_taught'];
    $hours = $_POST['hours'];
    $semester = 'odd'; // Assuming this is predefined or selected from UI

    // SQL insert query
    $sql = "INSERT INTO course_taught (programme, course_taught, hours, semester, dept) 
            VALUES ('$programme', '$course_taught', '$hours', '$semester', '$teacher_dept')";

    if ($conn->query($sql) === TRUE) {
        header("Location: timetable_course.php?semester=$semester");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
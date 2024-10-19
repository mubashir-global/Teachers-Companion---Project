<?php
include ('../db_connection.php'); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_name = $_POST['teacher_name'];
    $student_id = $_POST['student_id'];
    $date = $_POST['date'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO latecomers (teacher_name, date, student_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $teacher_name, $date, $student_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New late registration added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

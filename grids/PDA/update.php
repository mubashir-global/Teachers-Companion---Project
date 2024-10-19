<?php
// Database connection settings
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'nazalprojectdb'; // Your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST data is set
if (isset($_POST['id'], $_POST['date'], $_POST['conductingAgency'], $_POST['subject'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $date = $conn->real_escape_string($_POST['date']);
    $agency = $conn->real_escape_string($_POST['conductingAgency']);
    $subject = $conn->real_escape_string($_POST['subject']);

    // SQL query to update the selected row in the database
    $sql = "UPDATE pda SET date='$date', conductingAgency='$agency', subject='$subject' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "success"; // Return success if the query was executed
    } else {
        echo "Error: " . $conn->error; // Return the error message
    }
} else {
    echo "Invalid data provided"; // Return error if POST data is missing
}

// Close connection
$conn->close();
?>

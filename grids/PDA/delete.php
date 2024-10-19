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

// Check if the id is set from the AJAX request
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // SQL query to delete the row with the specified id
    $sql = "DELETE FROM pda WHERE id = ?";

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    // Execute the query
    if ($stmt->execute()) {
        // If successful, send a success response back to the JavaScript
        echo "success";
    } else {
        // If an error occurred, send an error message back
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "No id provided.";
}

// Close the connection
$conn->close();
?>

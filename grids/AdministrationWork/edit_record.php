<?php
// Connect to the database
$servername = "localhost"; // Change if your server is different
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "nazalprojectdb"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the record to edit
if (isset($_GET['id'])) {
    $idToEdit = intval($_GET['id']);
    $sql = "SELECT id, date, details, under_principal_or_iqac FROM administration_work WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idToEdit);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();
    $stmt->close();
} else {
    die("Invalid ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link rel="stylesheet" href="aw.css">
</head>
<body>
    <div class="form-container">
        <h2>Edit Administration Work Record</h2>
        <form action="view_records.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($record['date']); ?>" required>

            <label for="details">Details:</label>
            <textarea id="details" name="details" required><?php echo htmlspecialchars($record['details']); ?></textarea>

            <label for="under_principal_or_iqac">Initials of Principal/HOD:</label>
            <input type="text" id="under_principal_or_iqac" name="under_principal_or_iqac" value="<?php echo htmlspecialchars($record['under_principal_or_iqac']); ?>" required>

            <input type="submit" name="update" value="Update">
        </form>
    </div>
</body>
</html>

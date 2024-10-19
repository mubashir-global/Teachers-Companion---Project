<?php
include ('../db_connection.php'); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $teacher_name = $_POST['teacher_name'];
    $student_id = $_POST['student_id'];
    $date = $_POST['date'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE latecomers SET teacher_name = ?, date = ?, student_id = ? WHERE id = ?");
    $stmt->bind_param("ssii", $teacher_name, $date, $student_id, $id);

    if ($stmt->execute()) {
        echo "Latecomer updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM latecomers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $latecomer = $result->fetch_assoc();

    // Fetch students for dropdown
    $students = $conn->query("SELECT id, name FROM students");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Latecomer</title>
</head>
<body>
    <h1>Edit Latecomer</h1>
    <form action="edit_latecomer.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $latecomer['id']; ?>">
        
        <label for="teacher_name">Teacher Name:</label>
        <input type="text" id="teacher_name" name="teacher_name" value="<?php echo htmlspecialchars($latecomer['teacher_name']); ?>" required><br><br>

        <label for="student_id">Select Student:</label>
        <select id="student_id" name="student_id" required>
            <?php
            while ($row = $students->fetch_assoc()) {
                $selected = ($row['id'] == $latecomer['student_id']) ? 'selected' : '';
                echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['name']) . "</option>";
            }
            ?>
        </select><br><br>

        <label for="date">Date and Time:</label>
        <input type="datetime-local" id="date" name="date" value="<?php echo date('Y-m-d\TH:i', strtotime($latecomer['date'])); ?>" required><br><br>

        <input type="submit" value="Update">
    </form>
    <a href="manage_latecomers.php">Back to Latecomers List</a>
</body>
</html>

<?php
$conn->close();
?>

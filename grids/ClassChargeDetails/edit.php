<?php
include ('../db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $sql = "SELECT * FROM classcharge_tb WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $name = $_POST['name'];
    $strength = $_POST['strength'];
    $semester = $_POST['semester']; // Changed from 'year' to 'semester'
    $p_date = $_POST['p_date'];
    $a_date = $_POST['a_date'];
    $firstInternal = $_POST['firstInternal'];
    $model_Exam = $_POST['model_Exam'];

    $sql = "UPDATE classcharge_tb SET name = ?, strength = ?, semester = ?, p_date = ?, a_date = ?, firstInternal = ?, model_Exam = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $name, $strength, $semester, $p_date, $a_date, $firstInternal, $model_Exam, $id);

    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: classchage.php"); // Redirect back to the main page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link rel="stylesheet" href="classcharge.css">
</head>
<body>
    <h1>Edit Record</h1>
    <form action="edit.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
        
        <label>Strength:</label>
        <input type="text" name="strength" value="<?php echo htmlspecialchars($row['strength']); ?>" required>
        
        <label>Semester:</label> <!-- Changed from Year to Semester -->
        <select name="semester" required>
            <option value="">Select Semester</option>
            <option value="1st Sem" <?php if ($row['semester'] == '1st Sem') echo 'selected'; ?>>1st Sem</option>
            <option value="2nd Sem" <?php if ($row['semester'] == '2nd Sem') echo 'selected'; ?>>2nd Sem</option>
            <option value="3rd Sem" <?php if ($row['semester'] == '3rd Sem') echo 'selected'; ?>>3rd Sem</option>
            <option value="4th Sem" <?php if ($row['semester'] == '4th Sem') echo 'selected'; ?>>4th Sem</option>
            <option value="5th Sem" <?php if ($row['semester'] == '5th Sem') echo 'selected'; ?>>5th Sem</option>
            <option value="6th Sem" <?php if ($row['semester'] == '6th Sem') echo 'selected'; ?>>6th Sem</option>
        </select>
        
        <label>Presentation Date:</label>
        <input type="date" name="p_date" value="<?php echo $row['p_date']; ?>" required>
        
        <label>Assessment Date:</label>
        <input type="date" name="a_date" value="<?php echo $row['a_date']; ?>" required>
        
        <label>First Internal:</label>
        <input type="text" name="firstInternal" value="<?php echo htmlspecialchars($row['firstInternal']); ?>" required>
        
        <label>Model Exam:</label>
        <input type="text" name="model_Exam" value="<?php echo htmlspecialchars($row['model_Exam']); ?>" required>
        
        <input type="submit" name="update" value="Update">
    </form>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Class Charge Management</title>
<link rel="stylesheet" href="classcharge.css">

</head>

    <?php 
        include '../../header_nav_footer.php';

    ?>
<h1>Class Charge Management</h1>
<form action="./process.php" method="POST">
    <div class="form-row">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div class="form-row">
        <label for="strength">Strength:</label>
        <input type="text" id="strength" name="strength" required>
        <label for="semester">Semester:</label>
        <select id="semester" name="semester" required>
            <option value="">Select Semester</option>
            <option value="1st Sem">1st Sem</option>
            <option value="2nd Sem">2nd Sem</option>
            <option value="3rd Sem">3rd Sem</option>
            <option value="4th Sem">4th Sem</option>
            <option value="5th Sem">5th Sem</option>
            <option value="6th Sem">6th Sem</option>
        </select>
    </div>
    <div class="form-row">
        <label for="p_date">Presentation Date:</label>
        <input type="date" id="p_date" name="p_date" required>
        <label for="a_date">Assessment Date:</label>
        <input type="date" id="a_date" name="a_date" required>
    </div>
    <div class="form-row">
        <label for="firstInternal">First Internal:</label>
        <input type="text" id="firstInternal" name="firstInternal" required>
        <label for="model_Exam">Model Exam:</label>
        <input type="text" id="model_Exam" name="model_Exam" required>
    </div>
    <input type="submit" value="Submit">
    <input type="reset" value="Reset">
</form>

<h2>Existing Records</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Strength</th>
        <th>Semester</th> <!-- Changed from Year to Semester -->
        <th>Presentation Date</th>
        <th>Assessment Date</th>
        <th>First Internal</th>
        <th>Model Exam</th>
        <th>Actions</th>
    </tr>
    <?php
    include ('../../db_connection.php');

    $sql = "SELECT * FROM classcharge_tb";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['strength']) . "</td>
                    <td>" . (isset($row['semester']) ? htmlspecialchars($row['semester']) : 'N/A') . "</td>
                    <td>" . htmlspecialchars($row['p_date']) . "</td>
                    <td>" . htmlspecialchars($row['a_date']) . "</td>
                    <td>" . htmlspecialchars($row['firstInternal']) . "</td>
                    <td>" . htmlspecialchars($row['model_Exam']) . "</td>
                    <td>
                        <form action='./edit.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <input type='submit' value='Edit'>
                        </form>
                        <form action='./delete.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <input type='submit' value='Delete' onclick='return confirm(\"Are you sure?\");'>
                        </form>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No records found</td></tr>";
    }
    
    $conn->close();
    ?>
</table>
</body>
</html>

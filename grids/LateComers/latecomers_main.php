<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Late Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #4CAF50; /* Green */
        }
        form {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px 50px;
            max-width: 400px;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="datetime-local"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50; /* Green */
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049; /* Darker green */
        }
        input[type="reset"] {
            background-color: #A9A9A9; /* Green */
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="reset"]:hover {
            background-color: #45a049; /* Darker green */
        }
    </style>
</head>
<body>
    <?php
        include '../../header_nav_footer.php';

    ?>
    <h1>Late Registration Form</h1>
    <form action="latecomers.php" method="POST">
        <label for="teacher_name">Teacher Name:</label>
        <input type="text" id="teacher_name" name="teacher_name" required>

        <label for="student_id">Select Student:</label>
        <select id="student_id" name="student_id" required>
            <?php
          include ('../../db_connection.php'); 


            $sql = "SELECT id, name FROM students";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                }
            } else {
                echo "<option value=''>No students found</option>";
            }
            $conn->close();
            ?>
        </select>

        <label for="date">Date and Time:</label>
        <input type="datetime-local" id="date" name="date" required>

        <input type="submit" value="Submit">
        <input type="reset" value="Reset">

    </form>
</body>
</html>
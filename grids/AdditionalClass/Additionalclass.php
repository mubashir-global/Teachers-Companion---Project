<?php
session_start();

// Database connection settings
$servername = "localhost"; 
$username = "root"; 
$password = "Junu123#"; 
$dbname = "teachers_companion"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
include '../../header_nav_footer.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_id'])) {
        // Handle edit submission
        $edit_id = intval($_POST['edit_id']);
        $Teacher_name = $_POST['teacherName'] ?? '';
        $class_name = $_POST['className'] ?? '';
        $Date = $_POST['date'] ?? '';
        $Hour = $_POST['hours'] ?? '';

        if (!empty($Teacher_name) && !empty($class_name) && !empty($Date) && !empty($Hour)) {
            $update_query = "UPDATE additional_classes SET teacher_name='$Teacher_name', class_name='$class_name', date='$Date', hours='$Hour' WHERE id=$edit_id";
            if ($conn->query($update_query) === TRUE) {
                echo "<script>alert('Update successful!');</script>";
            } else {
                echo "<script>alert('Update failed: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('All fields are required.');</script>";
        }

        // Refresh the page to show the updated data
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else if (isset($_POST['delete_id'])) {
        // Handle delete request
        $delete_id = intval($_POST['delete_id']);
        $delete_query = "DELETE FROM additional_classes WHERE id=$delete_id";
        if ($conn->query($delete_query) === TRUE) {
            echo "<script>alert('Delete successful!');</script>";
        } else {
            echo "<script>alert('Delete failed: " . $conn->error . "');</script>";
        }

        // Refresh the page to show the updated data
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Handle new entry submission
        $Teacher_name = $_POST['teacherName'] ?? '';
        $class_name = $_POST['className'] ?? '';
        $Date = $_POST['date'] ?? '';
        $Hour = $_POST['hours'] ?? '';

        if (!empty($Teacher_name) && !empty($class_name) && !empty($Date) && !empty($Hour)) {
            $insert_query = "INSERT INTO additional_classes (teacher_name, class_name, date, hours) VALUES ('$Teacher_name', '$class_name', '$Date', '$Hour')";
            if ($conn->query($insert_query) === TRUE) {
                echo "<script>alert('Insert successful!');</script>";
            } else {
                echo "<script>alert('Insert failed: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('All fields are required.');</script>";
        }

        // Refresh the page to show the new data
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Additional Class Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 40%;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: black;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin: 10px 0 5px;
            color: black;
        }
        input[type="text"], input[type="date"], input[type="number"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"], .view-register button {
            padding: 10px 20px;
            border: none;
            background-color: #4CAF50;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            width: 30%; /* Make buttons the same width */
        }
        input[type="submit"]:hover, .view-register button:hover {
            background-color: #45a049;
        }
        .view-register {
            margin-top: 20px;
            text-align: center;
        }
        #registerData {
            margin-top: 20px;
            display: none; /* Initially hidden */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .delete-button {
            background-color: red;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .delete-button:hover {
            background-color: darkred;
        }
        .edit-button {
            background-color: #45a049;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .edit-button:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Additional Class Register</h2>
        <form action="" method="post">
            <label for="teacherName">Teacher Name:</label>
            <input type="text" id="teacherName" name="teacherName" required>

            <label for="className">Class Name:</label>
            <input type="text" id="className" name="className" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="hours">Hours:</label>
            <input type="number" id="hours" name="hours" min="1" required>

            <center><input type="submit" value="Submit"></center>
        </form>

        <div class="view-register">
            <button id="toggleButton" type="button" onclick="toggleRegisterData()">View Register</button>
        </div>

        <div id="registerData">
            <?php
            // Fetch and display data
            $stmt = $conn->query("SELECT id, teacher_name, class_name, date, hours FROM additional_classes");
            if ($stmt->num_rows > 0) {
                echo '<h2>Additional Classes Register:</h2>';
                echo '<table>';
                echo '<thead><tr><th>Teacher Name</th>
                <th>Class Name</th>
                <th>Date</th>
                <th>Hours</th>
                <th>Settings</th>
                </tr></thead>';
                echo '<tbody>';
                while ($row = $stmt->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['teacher_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['class_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['date']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['hours']) . '</td>';
                    echo '<td>
                            <button class="edit-button" onclick="editEntry(' . $row['id'] . ', \'' . htmlspecialchars($row['teacher_name']) . '\', \'' . htmlspecialchars($row['class_name']) . '\', \'' . htmlspecialchars($row['date']) . '\', ' . $row['hours'] . ')">Edit</button>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="delete_id" value="' . $row['id'] . '">
                                <button type="submit" class="delete-button" onclick="return confirm(\'Are you sure you want to delete this class?\');">Delete</button>
                            </form>
                          </td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<h2>No classes registered yet.</h2>';
            }
            ?>
        </div>
    </div>

    <script>
        function toggleRegisterData() {
            const registerData = document.getElementById('registerData');
            const toggleButton = document.getElementById('toggleButton');

            if (registerData.style.display === 'none' || registerData.style.display === '') {
                registerData.style.display = 'block';
                toggleButton.textContent = 'Hide Register'; // Change to 'Hide Register'
            } else {
                registerData.style.display = 'none';
                toggleButton.textContent = 'View Register'; // Change back to 'View Register'
            }
        }

        function editEntry(id, teacherName, className, date, hours) {
            const container = document.querySelector('.container');
            container.innerHTML = `
                <h2>Edit Class</h2>
                <form action="" method="post">
                    <input type="hidden" name="edit_id" value="${id}">
                    <label for="teacherName">Teacher Name:</label>
                    <input type="text" id="teacherName" name="teacherName" value="${teacherName}" required>

                    <label for="className">Class Name:</label>
                    <input type="text" id="className" name="className" value="${className}" required>

                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" value="${date}" required>

                    <label for="hours">Hours:</label>
                    <input type="number" id="hours" name="hours" min="1" value="${hours}" required>

                    <input type="submit"       <center>  value="Update">  </center>
                </form>
                <div class="view-register">
                    <button type="button" onclick="toggleRegisterData()">View Register</button>
                </div>
            `;
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
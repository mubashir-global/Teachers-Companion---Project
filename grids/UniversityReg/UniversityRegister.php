<?php
// session_start();
$servername = "localhost";
$username = "root";
$password = "Junu123#";
$dbname = "teachers_companion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding or editing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $professor_name = $_POST['professor_name'];
    $department = $_POST['department'];
    $exam_date = $_POST['exam_date'];
    $duty_time = $_POST['duty_time'];
    $contact_number = $_POST['contact_number'];
    $action = $_POST['action'] ?? 'add'; // 'add' or 'edit'
    $id = $_POST['id'] ?? null;

    if ($action === 'edit' && $id) {
        // Update existing record
        $sql = "UPDATE universityduty_tb SET professor_name='$professor_name', department='$department', exam_date='$exam_date', duty_time='$duty_time', contact_number='$contact_number' WHERE id=$id";
    } else {
        // Insert new record
        $sql = "INSERT INTO universityduty_tb (professor_name, department, exam_date, duty_time, contact_number) VALUES ('$professor_name', '$department', '$exam_date', '$duty_time', '$contact_number')";
    }
    
    $conn->query($sql);

    // Redirect to the same page to refresh the form
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM universityduty_tb WHERE id=$id");
}

// Fetch data from the database
$sql = "SELECT * FROM universityduty_tb";
$result = $conn->query($sql);

// Toggle view state
$view_register = isset($_SESSION['view_register']) ? $_SESSION['view_register'] : false;

if (isset($_GET['toggle'])) {
    $view_register = !$view_register; // Toggle the view state
    $_SESSION['view_register'] = $view_register; // Save the state in the session
}

// Handle edit request
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM universityduty_tb WHERE id=$id");
    $edit_data = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Exam Duty Register</title>
    <link rel="stylesheet" href="UniversityExam.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        input[type="submit"], .view-button {
            display: block;
            width: 30%;
            padding: 10px 20px;
            border: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
            margin-top: 20px;
        }
        input[type="submit"]:hover, .view-button:hover {
            background-color: #45a049;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="date"], input[type="time"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
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
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 align="center">University Exam Duty Register</h2>

        <!-- Duty Submission Form -->
        <form action="" method="POST" id="dutyForm">
            <label for="professorName">Professor Name:</label>
            <input type="text" id="professorName" name="professor_name" value="<?php echo $edit_data['professor_name'] ?? ''; ?>" required>
            
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" value="<?php echo $edit_data['department'] ?? ''; ?>" required>
            
            <label for="examDate">Exam Date:</label>
            <input type="date" id="examDate" name="exam_date" value="<?php echo $edit_data['exam_date'] ?? ''; ?>" required>
            
            <label for="dutyTime">Duty Time:</label>
            <input type="text" id="dutyTime" name="duty_time" value="<?php echo $edit_data['duty_time'] ?? ''; ?>" required>
            
            <label for="contactNumber">Contact Number:</label>
            <input type="text" id="contactNumber" name="contact_number" value="<?php echo $edit_data['contact_number'] ?? ''; ?>" required>
            
            <input type="hidden" name="action" value="<?php echo $edit_data ? 'edit' : 'add'; ?>">
            <input type="hidden" name="id" value="<?php echo $edit_data['id'] ?? ''; ?>">
         <center>   <input type="submit" value="<?php echo $edit_data ? 'Update' : 'Submit'; ?>"></center>
        </form>

        
        <center>
            <button class="view-button" onclick="toggleView()"><?php echo $view_register ? 'Hide Register' : 'View Register'; ?></button>
        </center>

        <?php if ($view_register): ?>
            <!-- Duty Register Table -->
            <h2 align="center">University Exam Duty Register</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Exam Date</th>
                    <th>Duty Time</th>
                    <th>Contact Number</th>
                    <th>Settings</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['professor_name']}</td>
                                <td>{$row['department']}</td>
                                <td>{$row['exam_date']}</td>
                                <td>{$row['duty_time']}</td>
                                <td>{$row['contact_number']}</td>
                                <td>
                                    <div class='action-buttons'>
                                        <a href='?edit={$row['id']}' class='edit-button'>Edit</a>
                                        <a href='?delete={$row['id']}' class='delete-button' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>
                                    </div>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' align='center'>No records found.</td></tr>";
                }
                ?>
            </table>
        <?php endif; ?>
    </div>

    <script>
        function toggleView() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('toggle', '1');
            window.location.href = currentUrl.toString();
        }

        // Check if the form was submitted successfully
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            alert('Submitted successfully!');
        <?php endif; ?>
    </script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
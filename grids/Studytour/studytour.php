<?php
// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "teachers_companion"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle form submission for study tour registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $teacher_name = $_POST['teacher_name'];
        $student_id = $_POST['student_id'];
        $department = $_POST['department'];
        $semester = $_POST['semester'];
        $starting_date = $_POST['starting_date'];
        $ending_date = $_POST['ending_date'];
        $designation = $_POST['designation'];
        $amount = $_POST['amount'];

        $sql = "INSERT INTO study_tour_register 
                (teacher_name, student_id, department, semester, starting_date, ending_date, designation, amount) 
                VALUES ('$teacher_name', '$student_id', '$department', '$semester', '$starting_date', '$ending_date', '$designation', '$amount')";

        if ($conn->query($sql) === TRUE) {
            $message = "<div class='alert success'>New record created successfully</div>";
        } else {
            $message = "<div class='alert error'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $teacher_name = $_POST['teacher_name'];
        $student_id = $_POST['student_id'];
        $department = $_POST['department'];
        $semester = $_POST['semester'];
        $starting_date = $_POST['starting_date'];
        $ending_date = $_POST['ending_date'];
        $designation = $_POST['designation'];
        $amount = $_POST['amount'];

        $update_sql = "UPDATE study_tour_register SET 
                       teacher_name='$teacher_name', student_id='$student_id', department='$department', 
                       semester='$semester', starting_date='$starting_date', ending_date='$ending_date', 
                       designation='$designation', amount='$amount' 
                       WHERE id=$id";

        if ($conn->query($update_sql) === TRUE) {
            $message = "<div class='alert success'>Record updated successfully</div>";
        } else {
            $message = "<div class='alert error'>Error updating record: " . $conn->error . "</div>";
        }
    }
}

// Handle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM study_tour_register WHERE id=$delete_id";

    if ($conn->query($delete_sql) === TRUE) {
        $message = "<div class='alert success'>Record deleted successfully</div>";
    } else {
        $message = "<div class='alert error'>Error deleting record: " . $conn->error . "</div>";
    }
}

// Handle editing
$edit_record = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_sql = "SELECT * FROM study_tour_register WHERE id=$edit_id";
    $edit_result = $conn->query($edit_sql);
    $edit_record = $edit_result->fetch_assoc();
}

// Fetching registered study tours
$sql = "SELECT * FROM study_tour_register";
$result = $conn->query($sql);

// Handle CSV download
if (isset($_POST['download'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="study_tour_records.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Sl No', 'Teacher Name', 'Student Id', 'Department', 'Semester', 'Starting Date', 'Ending Date', 'Designation', 'Amount']);
    $sl_no = 1;
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $sl_no,
            $row['teacher_name'],
            $row['student_id'],
            $row['department'],
            $row['semester'],
            $row['starting_date'],
            $row['ending_date'],
            $row['designation'],
            $row['amount']
        ]);
        $sl_no++;
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Tour Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f4f8;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 1000px;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 30px;
        }
        .form-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .form-row > div {
            width: 48%;
        }
        .form-row label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .alert {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .alert.success {
            background-color: #d4edda;
            color: #155724;
        }
        .actions {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .edit-button, .delete-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 5px;
        }
        .delete-button {
            background-color: #dc3545;
        }
        .edit-button:hover, .delete-button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Study Tour Registration</h2>
    <?= $message ?? ''; ?>
    
    <form action="studytour.php" method="POST">
        <div class="form-row">
            <div>
                <label for="teacher_name">Teacher Name:</label>
                <input type="text" id="teacher_name" name="teacher_name" placeholder="Please Enter Your Name" required value="<?= $edit_record['teacher_name'] ?? ''; ?>">
            </div>
            <div>
                <label for="student_id">Student Id:</label>
                <input type="text" id="student_id" name="student_id" required value="<?= $edit_record['student_id'] ?? ''; ?>">
            </div>
        </div>
        <div class="form-row">
            <div>
                <label for="department">Department:</label>
                <select id="department" name="department" required>
                    <option value="">Select Department</option>
                    <option value="English" <?= (isset($edit_record['department']) && $edit_record['department'] == 'English') ? 'selected' : ''; ?>>English</option>
                    <option value="Psychology" <?= (isset($edit_record['department']) && $edit_record['department'] == 'Psychology') ? 'selected' : ''; ?>>Psychology</option>
                    <option value="BCOM" <?= (isset($edit_record['department']) && $edit_record['department'] == 'BCOM') ? 'selected' : ''; ?>>BCOM</option>
                </select>
            </div>
            <div>
                <label for="semester">Semester:</label>
                <input type="text" id="semester" name="semester" required value="<?= $edit_record['semester'] ?? ''; ?>">
            </div>
        </div>
        <div class="form-row">
            <div>
                <label for="starting_date">Starting Date:</label>
                <input type="date" id="starting_date" name="starting_date" required value="<?= $edit_record['starting_date'] ?? ''; ?>">
            </div>
            <div>
                <label for="ending_date">Ending Date:</label>
                <input type="date" id="ending_date" name="ending_date" required value="<?= $edit_record['ending_date'] ?? ''; ?>">
            </div>
        </div>
        <div class="form-row">
            <div>
                <label for="designation">Designation:</label>
                <input type="text" id="designation" name="designation" required value="<?= $edit_record['designation'] ?? ''; ?>">
            </div>
            <div>
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" required value="<?= $edit_record['amount'] ?? ''; ?>">
            </div>
        </div>
        <?php if (isset($edit_record)): ?>
            <input type="hidden" name="id" value="<?= $edit_record['id']; ?>">
            <input type="submit" name="edit" value="Update Record">
        <?php else: ?>
            <input type="submit" name="register" value="Register">
        <?php endif; ?>
    </form>

    <table>
        <tr>
            <th>Sl No</th>
            <th>Teacher Name</th>
            <th>Student Id</th>
            <th>Department</th>
            <th>Semester</th>
            <th>Starting Date</th>
            <th>Ending Date</th>
            <th>Designation</th>
            <th>Amount</th>
            <th>Actions</th>
        </tr>
        <?php
        $sl_no = 1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$sl_no}</td>
                        <td>{$row['teacher_name']}</td>
                        <td>{$row['student_id']}</td>
                        <td>{$row['department']}</td>
                        <td>{$row['semester']}</td>
                        <td>{$row['starting_date']}</td>
                        <td>{$row['ending_date']}</td>
                        <td>{$row['designation']}</td>
                        <td>{$row['amount']}</td>
                        <td class='actions'>
                            <a href='studytour.php?edit_id={$row['id']}' class='edit-button'>Edit</a>
                            <a href='studytour.php?delete_id={$row['id']}' class='delete-button' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>
                        </td>
                    </tr>";
                $sl_no++;
            }
        } else {
            echo "<tr><td colspan='10'>No records found</td></tr>";
        }
        ?>
    </table>

    <form action="studytour.php" method="POST">
        <input type="submit" name="download" value="Download Records as CSV" style="margin-top: 20px; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
    </form>
</div>

</body>
</html>

<?php $conn->close(); ?>
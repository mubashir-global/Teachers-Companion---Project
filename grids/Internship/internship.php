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

// Handle form submission for registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $teacher_name = $_POST['teacher_name'];
    $department = $_POST['department'];
    $semester = $_POST['semester'];
    $num_students = $_POST['num_students'];
    $starting_date = $_POST['starting_date'];
    $ending_date = $_POST['ending_date'];
   
    $company_name = $_POST['company_name'];
    $company_address = $_POST['company_address'];

    $sql = "INSERT INTO internship_register 
            (teacher_name, department, semester, num_students,starting_date, ending_date,  company_name, company_address) 
            VALUES ('$teacher_name', '$department', '$semester', '$num_students','$starting_date', '$ending_date',  '$company_name', '$company_address')";

    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert success'>New record created successfully</div>";
    } else {
        $message = "<div class='alert error'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// Handle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM internship_register WHERE id=$delete_id";

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
    $edit_sql = "SELECT * FROM internship_register WHERE id=$edit_id";
    $edit_result = $conn->query($edit_sql);
    $edit_record = $edit_result->fetch_assoc();
}

// Handle form submission for editing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $id = $_POST['id'];
    $teacher_name = $_POST['teacher_name'];
    $department = $_POST['department'];
    $semester = $_POST['semester'];
    $num_students = $_POST['num_students'];
    $starting_date = $_POST['starting_date'];
    $ending_date = $_POST['ending_date'];
    
    $company_name = $_POST['company_name'];
    $company_address = $_POST['company_address'];
    

    $update_sql = "UPDATE internship_register SET 
                   teacher_name='$teacher_name', department='$department', semester='$semester',  num_students='$num_students',
                   starting_date='$starting_date', ending_date='$ending_date', 
                   company_name='$company_name', company_address='$company_address'
                   WHERE id=$id";

    if ($conn->query($update_sql) === TRUE) {
        $message = "<div class='alert success'>Record updated successfully</div>";
    } else {
        $message = "<div class='alert error'>Error updating record: " . $conn->error . "</div>";
    }
}

// Fetching registered internships
$sql = "SELECT * FROM internship_register";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Register</title>
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
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Prevent box size issues */
        }
        input[type="submit"] {
            width: 100%;
            background-color: #28a745; /* Green color */
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #218838; /* Darker green */
        }
        button {
            width: 30%;
            background-color: #007bff; /* Blue color */
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        button:hover {
            background-color: #0056b3; /* Darker blue */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden; /* Prevent table from exceeding rounded corners */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: none; /* Initially hide the table */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #218838;
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
        .search-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
}

.search-container input[type="text"] {
    width: 70%; /* Set width as required */
    padding: 10px; /* Increased padding for a better appearance */
    border: 1px solid #ccc;
    border-radius: 4px;
    height: 40px; /* Set a fixed height for alignment */
    box-sizing: border-box; /* Include padding and border in the total width */
}

.search-container button {
    width: 25%; /* Adjust button width to be responsive */
    height: 40px; /* Set a fixed height to match the input */
    background-color: #007bff; /* Blue color */
    padding: 10px; /* Adjust padding */
    border: none;
    border-radius: 4px;
    cursor: pointer;
    color: white;
    font-size: 16px; /* Ensure font size is consistent */
}

.search-container button:hover {
    background-color: #0056b3; /* Darker blue */
}

    </style>
</head>
<body>

<div class="container">
    <h2>Internship Registration</h2>
    <?= $message ?? ''; ?>
    
    <form action="internship.php" method="POST">
        <div class="form-row">
            <div>
                <label for="teacher_name">Teacher Name:</label>
                <input type="text" id="teacher_name" name="teacher_name" placeholder="Please Enter Your Name" required value="<?= $edit_record['teacher_name'] ?? ''; ?>">
            </div>
            <div>
    <label for="department">Department:</label>
    <select id="department" name="department" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
        <option value="">Select Department</option>
        <option value="English" <?= (isset($edit_record['department']) && $edit_record['department'] == 'English') ? 'selected' : ''; ?>>English</option>
        <option value="Phychology" <?= (isset($edit_record['department']) && $edit_record['department'] == ' Phychology') ? 'selected' : ''; ?>>Phychology</option>
        <option value=" Economics" <?= (isset($edit_record['department']) && $edit_record['department'] == ' Economics') ? 'selected' : ''; ?>> Economics</option>
        <option value="Computer Science" <?= (isset($edit_record['department']) && $edit_record['department'] == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
        <option value="BBA " <?= (isset($edit_record['department']) && $edit_record['department'] == ' BBA') ? 'selected' : ''; ?>>BBA </option>
        <option value="BCOM " <?= (isset($edit_record['department']) && $edit_record['department'] == 'BCOM ') ? 'selected' : ''; ?>>BCOM </option>
        <option value="BTHM " <?= (isset($edit_record['department']) && $edit_record['department'] == ' BTHM') ? 'selected' : ''; ?>>BTHM </option>
        <option value="Maths and Physics " <?= (isset($edit_record['department']) && $edit_record['department'] == ' Maths and Physics') ? 'selected' : ''; ?>>Maths and Physics </option>
        <option value="BVOC Mobile Application Development " <?= (isset($edit_record['department']) && $edit_record['department'] == ' BVOC Mobile Application Development') ? 'selected' : ''; ?>>BVOC Mobile Application Development </option>
        <option value="BVOC Logistics Managmenet " <?= (isset($edit_record['department']) && $edit_record['department'] == ' BVOC Logistics Managmenet') ? 'selected' : ''; ?>>BVOC Logistics Managmenet </option>
        <option value="BVOC Hotel Managmenet " <?= (isset($edit_record['department']) && $edit_record['department'] == ' BVOC Hotel Managmenet') ? 'selected' : ''; ?>>BVOC Hotel Managmenet </option>
        <!-- Add more departments as needed -->
    </select>
</div>

        </div>
        <div class="form-row">
        <div>
    <label for="semester">Semester:</label>
    <select id="semester" name="semester" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
        <option value="">Select Semester</option>
        <option value="1" <?= (isset($edit_record['semester']) && $edit_record['semester'] == '1') ? 'selected' : ''; ?>>1</option>
        <option value="2" <?= (isset($edit_record['semester']) && $edit_record['semester'] == '2') ? 'selected' : ''; ?>>2</option>
        <option value="3" <?= (isset($edit_record['semester']) && $edit_record['semester'] == '3') ? 'selected' : ''; ?>>3</option>
        <option value="4" <?= (isset($edit_record['semester']) && $edit_record['semester'] == '4') ? 'selected' : ''; ?>>4</option>
        <option value="5" <?= (isset($edit_record['semester']) && $edit_record['semester'] == '5') ? 'selected' : ''; ?>>5</option>
        <option value="6" <?= (isset($edit_record['semester']) && $edit_record['semester'] == '6') ? 'selected' : ''; ?>>6</option>
        <option value="7" <?= (isset($edit_record['semester']) && $edit_record['semester'] == '7') ? 'selected' : ''; ?>>7</option>
        <option value="8" <?= (isset($edit_record['semester']) && $edit_record['semester'] == '8') ? 'selected' : ''; ?>>8</option>
    </select>
</div>


            <div>
                <label for="num_students">Number of Students:</label>
                <input type="number" id="num_students" name="num_students" required value="<?= $edit_record['num_students'] ?? ''; ?>">
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
                <label for="company_name">Company Name:</label>
                <input type="text" id="company_name" name="company_name" required value="<?= $edit_record['company_name'] ?? ''; ?>">
            </div>
            <div>
                <label for="company_address">Company Address:</label>
                <input type="text" id="company_address" name="company_address" required value="<?= $edit_record['company_address'] ?? ''; ?>">
            </div>
        </div>
        <?php if ($edit_record): ?>
            <input type="hidden" name="id" value="<?= $edit_record['id']; ?>">
            <input type="submit" name="edit" value="Update Record">
        <?php else: ?>
            <input type="submit" name="register" value="Register Internship">
        <?php endif; ?>
    </form>

    <center><button id="view-button">View Registered Internships</button></center>

    <div class="search-container" id="search-container" style="display:none;">
        <input type="text" id="search-input" placeholder="Search by Teacher Name...">
        <button id="download-button">Download</button>
    </div>

    <table id="internship-table">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Teacher Name</th>
                <th>Department</th>
                <th>Semester</th>
                <th>Starting Date</th>
                <th>Ending Date</th>
                <th>Num Students</th>
                <th>Company Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="internship-tbody">
            <?php
            $sl_no = 1; // Initialize serial number
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>$sl_no</td>
                        <td>{$row['teacher_name']}</td>
                        <td>{$row['department']}</td>
                        <td>{$row['semester']}</td>
                        <td>{$row['starting_date']}</td>
                        <td>{$row['ending_date']}</td>
                        <td>{$row['num_students']}</td>
                        <td>{$row['company_name']}</td>
                        <td class='actions'>
                            <a class='edit-button' href='internship.php?edit_id={$row['id']}'>Edit</a>
                            <a class='delete-button' href='internship.php?delete_id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>
                        </td>
                    </tr>";
                $sl_no++; // Increment serial number
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('view-button').addEventListener('click', function() {
        const table = document.getElementById('internship-table');
        const searchContainer = document.getElementById('search-container');
        if (table.style.display === 'none' || table.style.display === '') {
            table.style.display = 'table'; // Show the table
            searchContainer.style.display = 'flex'; // Show the search container
            this.innerText = 'Hide Registered Internships'; // Change button text
        } else {
            table.style.display = 'none'; // Hide the table
            searchContainer.style.display = 'none'; // Hide the search container
            this.innerText = 'View Registered Internships'; // Change button text
        }
    });

    document.getElementById('search-input').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#internship-tbody tr');
        rows.forEach(row => {
            const teacherName = row.cells[1].textContent.toLowerCase();
            row.style.display = teacherName.includes(filter) ? '' : 'none';
        });
    });

    document.getElementById('download-button').addEventListener('click', function() {
        const rows = document.querySelectorAll('#internship-tbody tr');
        let csvContent = "data:text/csv;charset=utf-8,";
        rows.forEach(row => {
            const cols = row.querySelectorAll('td');
            const data = Array.from(cols).map(col => col.innerText).join(",");
            csvContent += data + "\r\n";
        });
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "internships.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
</script>

</body>
</html>

<?php
$conn->close();
?>

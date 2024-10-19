<?php
// Database connection settings
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = "Junu123#"; // Your database password (empty)
$dbname = "teachers_companion"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
include '../../header_nav_footer.php';

// Initialize variables
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Define the target directory
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["finalReport"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is a valid PDF
    if ($fileType !== "pdf") {
        $message = "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check file size (5MB limit)
    if ($_FILES["finalReport"]["size"] > 5000000) {
        $message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // If no errors, prepare to save data
    if ($uploadOk == 1) {
        $registerNumber = $_POST['registerNumber'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $supervisor = $_POST['supervisor'];
        $invigilator = $_POST['invigilator'];
        $duration = $_POST['duration'];
        $amount = $_POST['amount'];
        $department = $_POST['department'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];

        // Attempt to move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["finalReport"]["tmp_name"], $targetFile)) {
            // Insert data into the database
            $stmt = $conn->prepare("INSERT INTO projects (register_number, title, description, supervisor, invigilator, duration, amount, department, start_date, end_date, final_report) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssissssss", $registerNumber, $title, $description, $supervisor, $invigilator, $duration, $amount, $department, $startDate, $endDate, $targetFile);

            if ($stmt->execute()) {
                // Redirect to the same page after successful submission
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->bind_param("i", $deleteId);

    // Attempt to delete the entry from the database
    if ($stmt->execute()) {
        // Redirect to the same page after deletion
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $message = "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch existing submissions
$result = $conn->query("SELECT * FROM projects");
$submissions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $submissions[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Project Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            font-size: 2.5em;
            font-weight: bold;
            color: black;
        }
        .form-group {
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            color: red;
            text-align: center;
        }
        .submission-table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        .submission-table th {
            color: white;
            background-color: green;
            padding: 10px;
        }
        .submission-table th, .submission-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = "block";
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal(event.target.id);
            }
        }
    </script>
</head>
<body>

    <h2>Research Project Management System</h2>

    <div class="message"><?php echo $message; ?></div>

    <?php if (!empty($submissions)): ?>
        <table class="submission-table">
            <thead>
                <tr>
                    <th>Register Number</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Supervisor</th>
                    <th>Invigilator</th>
                    <th>Duration</th>
                    <th>Amount</th>
                    <th>Department</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Final Report</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $submission): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($submission['register_number']); ?></td>
                        <td><?php echo htmlspecialchars($submission['title']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($submission['description'])); ?></td>
                        <td><?php echo htmlspecialchars($submission['supervisor']); ?></td>
                        <td><?php echo htmlspecialchars($submission['invigilator']); ?></td>
                        <td><?php echo htmlspecialchars($submission['duration']); ?></td>
                        <td><?php echo htmlspecialchars($submission['amount']); ?></td>
                        <td><?php echo htmlspecialchars($submission['department']); ?></td>
                        <td><?php echo htmlspecialchars($submission['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($submission['end_date']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($submission['final_report']); ?>" target="_blank">View Report</a></td>
                        <td>
                            <button onclick="openModal('deleteModal<?php echo $submission['id']; ?>')">Delete</button>
                        </td>
                    </tr>
                    <!-- Delete Confirmation Modal -->
                    <div id="deleteModal<?php echo $submission['id']; ?>" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal('deleteModal<?php echo $submission['id']; ?>')">&times;</span>
                            <h2>Delete Confirmation</h2>
                            <p>Are you sure you want to delete the project "<?php echo htmlspecialchars($submission['title']); ?>"?</p>
                            <form action="" method="get">
                                <input type="hidden" name="delete_id" value="<?php echo $submission['id']; ?>">
                                <button type="submit">Yes, Delete</button>
                                <button type="button" onclick="closeModal('deleteModal<?php echo $submission['id']; ?>')">Cancel</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <button onclick="openModal('projectForm')">Add New Project</button>

    <div id="projectForm" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('projectForm')">&times;</span>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="registerNumber">Candidate Register Number</label>
                    <input type="text" id="registerNumber" name="registerNumber" required>
                </div>
                <div class="form-group">
                    <label for="title">Project Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="supervisor">Research Supervisor</label>
                    <input type="text" id="supervisor" name="supervisor" required>
                </div>
                <div class="form-group">
                    <label for="invigilator">Principal Invigilator</label>
                    <input type="text" id="invigilator" name="invigilator" required>
                </div>
                <div class="form-group">
                    <label for="duration">Duration (Years)</label>
                    <input type="number" id="duration" name="duration" required>
                </div>
                <div class="form-group">
                    <label for="amount">Amount of Project</label>
                    <input type="number" id="amount" name="amount" required>
                </div>
                <div class="form-group">
                    <label for="department">Department</label>
                    <input type="text" id="department" name="department" required>
                </div>
                <div class="form-group">
                    <label for="startDate">Start Date</label>
                    <input type="date" id="startDate" name="startDate" required>
                </div>
                <div class="form-group">
                    <label for="endDate">End Date</label>
                    <input type="date" id="endDate" name="endDate">
                </div>
                <div class="form-group">
                    <label for="finalReport">Final Report (PDF)</label>
                    <input type="file" id="finalReport" name="finalReport" accept=".pdf" required>
                </div>
                <button type="submit" name="submit">Submit</button>
            </form>
        </div>
    </div>

</body>
</html>

<?php
// Database connection settings
$host = 'localhost'; // Change as needed
$db = 'teachers_companion'; // Change to your database name
$user = 'root'; // Change to your database username
$pass = 'Junu123#'; // Change to your database password

// Create connection
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
include '../../header_nav_footer.php';

// Create the database if it doesn't exist
$conn->query("CREATE DATABASE IF NOT EXISTS $db");
$conn->select_db($db);

// Create tables for each semester if they don't exist
for ($semester = 1; $semester <= 6; $semester++) {
    $table = "project_works_semester" . $semester;
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        roll_number VARCHAR(20) NOT NULL,
        student_id VARCHAR(20) NOT NULL,
        student_name VARCHAR(100) NOT NULL,
        project_work VARCHAR(100) NOT NULL
    )";
    $conn->query($sql);
}

// Start session
session_start();

// Get the selected semester, default to 1 if not set
$selectedSemester = $_POST['semester'] ?? $_GET['semester'] ?? 1; 

// Handle form submission for add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit') {
    $rollNumber = $_POST['rollNumber'];
    $studentId = $_POST['studentId'];
    $studentName = $_POST['studentName'];
    $projectWork = $_POST['projectWork'];
    $id = $_POST['id'] ?? null; // Check for edit id

    // Determine the table based on the selected semester
    $table = "project_works_semester" . $selectedSemester;

    if ($id) {
        // Update existing project work
        $stmt = $conn->prepare("UPDATE $table SET roll_number = ?, student_id = ?, student_name = ?, project_work = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $rollNumber, $studentId, $studentName, $projectWork, $id);
    } else {
        // Insert new project work
        $stmt = $conn->prepare("INSERT INTO $table (roll_number, student_id, student_name, project_work) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $rollNumber, $studentId, $studentName, $projectWork);
    }

    // Execute and check for errors
    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error; // Debugging line
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF'] . "?semester=$selectedSemester"); // Redirect to the same page
    exit;
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $table = "project_works_semester" . $selectedSemester;

    $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF'] . "?semester=$selectedSemester"); // Redirect to the same page
    exit;
}

// Fetch all project works for the selected semester
function fetchProjects($semester, $conn) {
    $table = "project_works_semester" . $semester;
    $result = $conn->query("SELECT * FROM $table");
    return $result->fetch_all(MYSQLI_ASSOC);
}

$projects = fetchProjects($selectedSemester, $conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Works</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f9f9f9; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); min-height: 600px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: green; color: white; padding: 15px; text-align: left; }
        td { border: 1px solid #ddd; padding: 15px; text-align: left; }
        .form-row { display: flex; gap: 10px; margin-bottom: 20px; }
        .form-row label { flex: 1; }
        .form-row input { flex: 2; }
        .project-work-container { display: flex; align-items: flex-start; gap: 10px; width: 100%; margin-top: 20px; }
        #projectWork { flex: 2; height: 60px; resize: vertical; }
        .semester-select { margin-left: 10px; }
        button { width: 150px; height: 45px; margin-top: 10px; background-color: green; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: darkgreen; }
        .action-buttons { display: flex; gap: 10px; }
        .action-buttons a { color: black; background-color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px; border: 1px solid black; }
        .action-buttons a:hover { background-color: #f0f0f0; }
        .add-project-container { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h1><center>Project Works</center></h1>

    <form id="projectForm" action="" method="POST">
        <div class="form-row">
            <label for="rollNumber">Roll Number:</label>
            <input type="text" id="rollNumber" name="rollNumber" required>
            
            <label for="studentId">Student ID:</label>
            <input type="text" id="studentId" name="studentId" required>
            
            <label for="studentName">Student Name:</label>
            <input type="text" id="studentName" name="studentName" required>
        </div>
        <div class="project-work-container">
            <label for="projectWork">Project Work:</label>
            <textarea id="projectWork" name="projectWork" required maxlength="100"></textarea>
            <div class="semester-select">
                <select id="semester" name="semester" required onchange="loadProjects()">
                    <option value="">Select Semester</option>
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo $selectedSemester == $i ? 'selected' : ''; ?>>Semester <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <div class="add-project-container">
            <input type="hidden" name="id" id="projectId" value="">
            <button type="submit" name="action" value="submit">Add Student Project</button>
        </div>
    </form>

    <table id="projectsTable">
        <thead>
            <tr>
                <th>Roll Number</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Semester</th>
                <th>Project Work</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?php echo htmlspecialchars($project['roll_number']); ?></td>
                    <td><?php echo htmlspecialchars($project['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($project['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($selectedSemester); ?></td>
                    <td><?php echo htmlspecialchars($project['project_work']); ?></td>
                    <td class="action-buttons">
                        <a href="#" onclick="editProject(<?php echo htmlspecialchars(json_encode($project)); ?>)">Edit</a>
                        <a href="?delete=<?php echo $project['id']; ?>&semester=<?php echo $selectedSemester; ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function loadProjects() {
    const semester = document.getElementById('semester').value;
    if (semester) {
        window.location.href = "?semester=" + semester;
    }
}

function editProject(project) {
    document.getElementById('projectId').value = project.id;
    document.getElementById('rollNumber').value = project.roll_number;
    document.getElementById('studentId').value = project.student_id;
    document.getElementById('studentName').value = project.student_name;
    document.getElementById('projectWork').value = project.project_work;
}
</script>

</body>
</html>

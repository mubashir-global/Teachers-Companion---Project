<?php
session_start();

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
// include '../../header_nav_footer.php';


// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: signin.html");
    exit();
}

// usr details from session
$user = $_SESSION['user'];
$teacher_id = $user['id'];

$student = null;
$students = [];

// Function to get student details by admission number
function getStudentByAdmissionNumber($conn, $admissionnumber) {
    $sql = "SELECT * FROM students WHERE admissionNumber='$admissionnumber'";
    return $conn->query($sql)->fetch_assoc();
}

function getAllStudents($conn) {
    $sql = "SELECT students.*, departments.name AS department_name 
            FROM students 
            LEFT JOIN departments ON students.department_id = departments.id";
    $result = $conn->query($sql);
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    return $students;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admissionnumber = $_POST['admissionnumber'];
    $student = getStudentByAdmissionNumber($conn, $admissionnumber);
}

$students = getAllStudents($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher - Student Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
        
            color: #333;
        }

        h1 {
            text-align: center;
            color: #4CAF50; /* Dark Green */
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ccc;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #4CAF50;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"], .back-button {
            background-color: #8B4513; /* Brown */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin: 10px 0;
        }

        button[type="submit"]:hover, .back-button:hover {
            background-color: #A0522D; /* Lighter Brown */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ccc;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50; /* Dark Green */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        p {
            color: #8b0000;
            text-align: center;
        }
        /* App Bar Styling */
        .app-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #4CAF50; /* Old Green Color */
            color: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Left Side of App Bar */
        .app-bar-left {
            display: flex;
            align-items: center;
        }

        /* College Logo Styling */
        .college-logo {
            width: 50px;
            height: auto;
            margin-right: 15px;
        }

        /* Welcome Message Styling */
        .welcome-message {
            font-size: 1.5rem;
            font-weight: bold;
        }

        /* Navigation Bar Styling */
        .navbar ul {
            list-style-type: none;
            display: flex;
        }

        .navbar ul li {
            margin-left: 20px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar ul li a:hover,
        .navbar ul li a.active {
            background-color: #388E3C;
        }
         /* Footer Styling */
         .footer {
            padding: 15px 30px;
            background-color: #4CAF50; /* Old Green Color */
            color: #fff;
            text-align: center;
            border-radius: 0 0 10px 10px;
            margin-top: auto;
        }

        .footer p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
<header class="app-bar">
            <div class="app-bar-left">
                <img src="../../amallogo.jpeg" alt="College Logo" class="college-logo">
                <span class="welcome-message">Welcome, <?php echo $user['name']; ?></span>
            </div>
            <nav class="navbar">
                <ul>
                    <li><a href="/Teachers-Companion---Project/index.php" class="active">Home</a></li>
                    <li><a href="/Teachers-Companion---Project/profile.php">Profile</a></li>
                    <li><a href="/Teachers-Companion---Project/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>
    <h1> Student Search</h1>
    <form method="POST">
        <label for="admissionnumber">Admission Number:</label>
        <input type="text" id="admissionnumber" name="admissionnumber" required>
        <button type="submit">Search</button>
    </form>

    <?php if ($student): ?>
    <h2 style="text-align:center; color: #4CAF50;">Student Details</h2>
    <table>
        <tr><th>Name</th><td><?php echo htmlspecialchars($student['name']); ?></td></tr>
        <tr><th>Admission Number</th><td><?php echo htmlspecialchars($student['admissionNumber']); ?></td></tr>
        <tr><th>Programme</th><td><?php echo htmlspecialchars($student['programme']); ?></td></tr>
        <tr><th>Category</th><td><?php echo htmlspecialchars($student['category']); ?></td></tr>
        <tr><th>Date of Birth</th><td><?php echo htmlspecialchars($student['dob']); ?></td></tr>
        <tr><th>Phone Number</th><td><?php echo htmlspecialchars($student['phoneNumber']); ?></td></tr>
        <tr><th>Email</th><td><?php echo htmlspecialchars($student['email']); ?></td></tr>
        <tr><th>Ambition</th><td><?php echo htmlspecialchars($student['ambition']); ?></td></tr>
        <tr><th>Strength</th><td><?php echo htmlspecialchars($student['strength']); ?></td></tr>
        <tr><th>Classroom Performance</th><td><?php echo htmlspecialchars($student['classroomPerformance']); ?></td></tr>
        <tr><th>Potential Identified</th><td><?php echo htmlspecialchars($student['potentialIdentified']); ?></td></tr>
    </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
    <p>No student found with the given admission number.</p>
    <?php endif; ?>

    <h2 style="text-align:center; color: #4CAF50;">All Students</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Admission Number</th>
                <th>Name</th>
                <th>Programme</th>
                <th>Department</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $counter = 1; 
            foreach ($students as $stu): ?>
            <tr>
                <td><?php echo $counter++; ?></td> 
                <td><?php echo htmlspecialchars($stu['admissionNumber']); ?></td>
                <td><?php echo htmlspecialchars($stu['name']); ?></td>
                <td><?php echo htmlspecialchars($stu['programme']); ?></td>
                <td><?php echo htmlspecialchars($stu['department_name']); ?></td>
                <td><?php echo htmlspecialchars($stu['email']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <footer class="footer">
            <div class="college-info">
                <p>Amal College</p>
                <p>&copy; <?php echo date("Y"); ?> All rights reserved.</p>
            </div>
        </footer>
</body>

</html>

<?php $conn->close(); ?>

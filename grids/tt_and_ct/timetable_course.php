<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teachers_companion";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: signin.html"); // Redirect to signin page if not logged in
    exit();
}

// User details from session
$user = $_SESSION['user'];

// Fetch department from session
$teacher_dept = $user['dept'];

// Fetch timetable based on department and semester
$view_semester = isset($_GET['semester']) ? $_GET['semester'] : 'odd';
$sql_timetable = "SELECT day, period, subject FROM timetable WHERE semester='$view_semester' AND dept='$teacher_dept' ORDER BY period, FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')";
$sql_course_taught = "SELECT * FROM course_taught WHERE semester='$view_semester' AND dept='$teacher_dept'";

$result_timetable = $conn->query($sql_timetable);
$result_course_taught = $conn->query($sql_course_taught);

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable & Courses Taught - Teachers Companion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-... (integrity hash) ..." crossorigin="anonymous" />

    <link rel="stylesheet" href="styles.css"> <!-- Link to your external CSS file -->
    <style>
        /* Internal CSS for Timetable & Courses Taught Page */

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
        }

        .app-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .app-bar .app-bar-left {
            display: flex;
            align-items: center;
        }

        .app-bar .app-bar-left .college-logo {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }

        .app-bar .app-bar-left .welcome-message {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .navbar ul {
            list-style-type: none;
            display: flex;
        }

        .navbar ul li {
            margin-left: 15px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar ul li a:hover {
            background-color: #45a049;
        }

        .navbar ul li a.active {
            background-color: #45a049;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: #fff;
        }

        .semester-select {
            margin-bottom: 20px;
        }

        .semester-select button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .semester-select button:hover {
            background-color: #45a049;
        }

        .footer {
            margin-top: 20px;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            text-align: center;
            border-radius: 5px;
        }

        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h3 {
            margin-bottom: 20px;
        }

        .form-container form {
            margin-bottom: 20px;
        }

        .form-container form label {
            display: block;
            margin-bottom: 5px;
        }

        .form-container form input[type="text"],
        .form-container form input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-container form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        .form-container form input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <header class="app-bar">
        <div class="app-bar-left">
            <img src="assets/img/amallogo.jpeg" alt="College Logo" class="college-logo">
            <span class="welcome-message">Welcome, <?php echo $user['name']; ?></span>
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Timetable & Courses Taught</h2>

        <div class="semester-select">
            <a href="?semester=odd"><button>View Odd Semester</button></a>
            <a href="?semester=even"><button>View Even Semester</button></a>
        </div>

        <h3>Timetable</h3>
        <table>
            <thead>
                <tr>
                    <th>Period / Day</th>
                    <?php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                    foreach ($days as $day) {
                        echo "<th>$day</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $periods = [];
                while ($row = $result_timetable->fetch_assoc()) {
                    $periods[$row['period']][$row['day']] = $row['subject'];
                }

                // Iterate through periods
                foreach ($periods as $period => $daySubjects) {
                    echo "<tr>";
                    echo "<td>$period</td>";
                    // Iterate through days
                    foreach ($days as $day) {
                        echo "<td>";
                        echo isset($daySubjects[$day]) ? $daySubjects[$day] : "-";
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>Courses Taught</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Programme</th>
                    <th>Course Taught</th>
                    <th>Hours</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_course_taught->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['programme']; ?></td>
                        <td><?php echo $row['course_taught']; ?></td>
                        <td><?php echo $row['hours']; ?></td>
                        <td>
                            <form action="edit_course_taught.php" method="post" style="display: inline-block;">
                                <input type="hidden" name="course_taught_id" value="<?php echo $row['id']; ?>">
                                <input type="submit" value="Edit">
                            </form>
                            <form action="delete_course_taught.php" method="post" style="display: inline-block;">
                                <input type="hidden" name="course_taught_id" value="<?php echo $row['id']; ?>">
                                <input type="submit" value="Delete">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="form-container">
            <h3>Add Timetable Entry</h3>
            <form action="add_timetable.php" method="post">
                <label for="day">Day:</label>
                <input type="text" id="day" name="day" required>

                <label for="period">Period:</label>
                <input type="text" id="period" name="period" required>

                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>

                <input type="submit" value="Add Timetable Entry">
            </form>
        </div>

        <div class="form-container">
            <h3>Add Course Taught</h3>
            <form action="add_course_taught.php" method="post">
                <label for="programme">Programme:</label>
                <input type="text" id="programme" name="programme" required>

                <label for="course_taught">Course Taught:</label>
                <input type="text" id="course_taught" name="course_taught" required>

                <label for="hours">Hours:</label>
                <input type="text" id="hours" name="hours" required>

                <input type="submit" value="Add Course Taught">
            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="college-info">
            <p>Amal College</p>
            <p>&copy; <?php echo date("Y"); ?> All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
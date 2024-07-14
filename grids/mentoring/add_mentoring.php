<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: signin.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teachers_companion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name = $_POST['student_name'];
    $class = $_POST['class'];
    $mentoring_process = $_POST['mentoring_process'];

    $sql = "INSERT INTO mentoring_tb (student_name, class, mentoring_process) VALUES ('$student_name', '$class', '$mentoring_process')";
    if ($conn->query($sql) === TRUE) {
        header("Location: mentoring.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Mentoring Record</title>
    <style>
        /* Internal CSS */
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
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        .form-container {
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
            font-weight: bold;
        }

        .form-container form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container form input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .footer {
            margin-top: 20px;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <header class="app-bar">
        <div class="app-bar-left">
            <img src="assets/img/amallogo.jpeg" alt="College Logo" class="college-logo">
            <span class="welcome-message">Welcome, <?php echo $_SESSION['user']['name']; ?></span>
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="form-container">
            <h3>Add Mentoring Record</h3>
            <form action="add_mentoring.php" method="POST">
                <label for="student_name">Student Name:</label>
                <input type="text" id="student_name" name="student_name" required>

                <label for="class">Class:</label>
                <input type="text" id="class" name="class" required>

                <label for="mentoring_process">Mentoring Process:</label>
                <input type="text" id="mentoring_process" name="mentoring_process" required>

                <input type="submit" value="Add Record">
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
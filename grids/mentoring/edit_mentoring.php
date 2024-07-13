<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: signin.php");
    exit();
}

include 'db_connection.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name = $_POST['student_name'];
    $class = $_POST['class'];
    $mentoring_process = $_POST['mentoring_process'];

    $sql = "UPDATE mentoring_tb SET student_name='$student_name', class='$class', mentoring_process='$mentoring_process' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: mentoring.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    $sql = "SELECT * FROM mentoring_tb WHERE id='$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mentoring Record</title>
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
            <h3>Edit Mentoring Record</h3>
            <form action="edit_mentoring.php?id=<?php echo $id; ?>" method="POST">
                <label for="student_name">Student Name:</label>
                <input type="text" id="student_name" name="student_name" value="<?php echo $row['student_name']; ?>"
                    required>

                <label for="class">Class:</label>
                <input type="text" id="class" name="class" value="<?php echo $row['class']; ?>" required>

                <label for="mentoring_process">Mentoring Process:</label>
                <input type="text" id="mentoring_process" name="mentoring_process"
                    value="<?php echo $row['mentoring_process']; ?>" required>

                <input type="submit" value="Update Record">
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
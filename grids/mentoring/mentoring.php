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

$sql = "SELECT * FROM mentoring_tb";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentoring</title>
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

        .add-button {
            margin-bottom: 20px;
            display: inline-block;
        }

        .add-button button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-button button:hover {
            background-color: #45a049;
        }

        .actions a {
            text-decoration: none;
            color: #4CAF50;
            margin-right: 10px;
        }

        .actions a:hover {
            color: #45a049;
        }
    </style>
</head>

<body>
    <header class="app-bar">
        <div class="app-bar-left">
            <img src="assets\img\amallogo.jpeg" alt="College Logo" class="college-logo">
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
        <h2>Mentoring</h2>
        <div class="add-button">
            <a href="add_mentoring.php"><button>Add Mentoring Record</button></a>
        </div>
        <table>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Class</th>
                <th>Mentoring Process</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['student_name']; ?></td>
                    <td><?php echo $row['class']; ?></td>
                    <td><?php echo $row['mentoring_process']; ?></td>
                    <td class="actions">
                        <a href="edit_mentoring.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete_mentoring.php?id=<?php echo $row['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <footer class="footer">
        <div class="college-info">
            <p>Amal College</p>
            <p>&copy; <?php echo date("Y"); ?> All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
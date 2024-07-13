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
$teacher_id = $user['id'];
$grids = $conn->query("SELECT * FROM grids WHERE teacher_id=$teacher_id")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Teachers Companion</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your external CSS file -->
    <style>
        /* Internal CSS for Dashboard Page */

        /* Reset default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            min-height: 100vh;
            /* Ensure full height layout */
            display: flex;
            flex-direction: column;
        }

        .dashboard-container {
            flex: 1;
            /* Fill remaining vertical space */
            display: flex;
            flex-direction: column;
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

        main {
            flex: 1;
            /* Fill remaining vertical space */
            margin-top: 20px;
            padding: 0 20px;
            /* Add padding to main content */
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            cursor: pointer;
            text-decoration: none;
            /* Ensure links look like cards */
            color: #333;
            /* Text color */
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 16px;
            line-height: 1.6;
        }

        .footer {
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            margin-top: auto;
            /* Push footer to bottom */
        }

        .footer p {
            margin: 5px 0;
        }

        @media (max-width: 768px) {
            .app-bar {
                flex-direction: column;
                align-items: center;
                padding: 10px;
            }

            .app-bar .app-bar-left {
                margin-bottom: 10px;
            }

            .navbar ul {
                flex-direction: column;
                align-items: center;
            }

            .navbar ul li {
                margin-left: 0;
                margin-bottom: 10px;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <header class="app-bar">
            <div class="app-bar-left">
                <img src="assets/img/amallogo.jpeg" alt="College Logo" class="college-logo">
                <span class="welcome-message">Welcome, <?php echo $user['name']; ?></span>
            </div>
            <nav class="navbar">
                <ul>
                    <li><a href="#" class="active">Home</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>
        <main>
            <div class="grid">
                <?php foreach ($grids as $grid): ?>
                    <a href="<?= $grid['url'] ?>" class="card">
                        <h3><?= $grid['title'] ?></h3>
                        <p><?= $grid['description'] ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </main>
        <footer class="footer">
            <div class="college-info">
                <p>Amal College</p>
                <p>&copy; <?php echo date("Y"); ?> All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>

</html>
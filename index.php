<?php
session_start();
include ('db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: signin.html");
    exit();
}

// usr details from session
$user = $_SESSION['user'];
$teacher_id = $user['id'];
$grids = $conn->query("SELECT * FROM grids WHERE teacher_id=$teacher_id")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Teachers Companion</title>
    <style>
        /* General Reset and Box-Sizing */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f0f0;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Container for Dashboard */
        .dashboard-container {
            flex: 1;
            display: flex;
            flex-direction: column;
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

        /* Main Content Styling */
        main {
            flex: 1;
            margin-top: 20px;
            padding: 0 30px;
        }

        /* Grid Styling */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            padding-bottom: 20px;
        }

        /* Card Styling */
        .card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            cursor: pointer;
            text-decoration: none;
            color: #333;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            font-size: 22px;
            margin-bottom: 15px;
        }

        .card p {
            font-size: 16px;
            line-height: 1.6;
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

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .app-bar {
                flex-direction: column;
                align-items: center;
                padding: 10px;
            }

            .app-bar-left {
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
                    <li><a href="index.php" class="active">Home</a></li>
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

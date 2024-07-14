<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
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

$teachers = $conn->query("SELECT * FROM sign_up_tb")->fetch_all(MYSQLI_ASSOC);
$grids = $conn->query("SELECT * FROM grids")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add_grid') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $url = $_POST['url'];
        $teacher_id = $_POST['teacher_id'];

        $sql = "INSERT INTO grids (title, description, url, teacher_id) VALUES ('$title', '$description', '$url', $teacher_id)";
        $conn->query($sql);
        header("Location: admin_dashboard.php");
        exit();
    } elseif ($_POST['action'] === 'delete_grid') {
        $grid_id = $_POST['grid_id'];

        $sql = "DELETE FROM grids WHERE id=$grid_id";
        $conn->query($sql);
        header("Location: admin_dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Teachers Companion</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1200px;
            margin-top: 20px;
        }

        h2,
        h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table td {
            background-color: #f9f9f9;
        }

        table tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .actions form {
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <h3>Teachers</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $teacher): ?>
                    <tr>
                        <td><?= $teacher['id'] ?></td>
                        <td><?= $teacher['name'] ?></td>
                        <td><?= $teacher['dept'] ?></td>
                        <td>
                            <!-- You can add more actions here if needed -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Add Grid</h3>
        <form method="POST" action="admin_dashboard.php">
            <input type="hidden" name="action" value="add_grid">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            <label for="url">URL:</label>
            <input type="text" id="url" name="url" required>
            <label for="teacher_id">Assign to Teacher:</label>
            <select id="teacher_id" name="teacher_id" required>
                <?php foreach ($teachers as $teacher): ?>
                    <option value="<?= $teacher['id'] ?>"><?= $teacher['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Add Grid</button>
        </form>

        <h3>Existing Grids</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>URL</th>
                    <th>Teacher</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grids as $grid): ?>
                    <tr>
                        <td><?= $grid['id'] ?></td>
                        <td><?= $grid['title'] ?></td>
                        <td><?= $grid['description'] ?></td>
                        <td><?= $grid['url'] ?></td>
                        <td><?= $grid['teacher_id'] ?></td>
                        <td class="actions">
                            <form method="POST" action="admin_dashboard.php">
                                <input type="hidden" name="action" value="delete_grid">
                                <input type="hidden" name="grid_id" value="<?= $grid['id'] ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
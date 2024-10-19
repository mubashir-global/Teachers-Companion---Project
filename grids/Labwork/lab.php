<?php
// Database configuration
$host = 'localhost';
$dbname = 'teachers_companion';
$username = 'root';
$password = 'Junu123#';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
include '../../header_nav_footer.php';

// Set default semester and handle form submissions
$selectedSemester = isset($_POST['semester']) ? $_POST['semester'] : 1; // Default to semester 1
$tableName = "lab_records_semester_" . $selectedSemester;

// Initialize variables for editing
$editRecord = null;

// Handle form submission to add or update a record
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $entryTime = $_POST['entryTime'];
        $exitTime = $_POST['exitTime'];
        $date = $_POST['date'];
        $works = $_POST['works'];

        // Insert record into the database
        $stmt = $pdo->prepare("INSERT INTO $tableName (entry_time, exit_time, date, works) VALUES (?, ?, ?, ?)");
        $stmt->execute([$entryTime, $exitTime, $date, $works]);
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id'];

        // Delete record from the database
        $stmt = $pdo->prepare("DELETE FROM $tableName WHERE id = ?");
        $stmt->execute([$id]);
    } elseif (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $id = $_POST['id'];

        // Fetch the record to edit
        $stmt = $pdo->prepare("SELECT * FROM $tableName WHERE id = ?");
        $stmt->execute([$id]);
        $editRecord = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = $_POST['id'];
        $entryTime = $_POST['entryTime'];
        $exitTime = $_POST['exitTime'];
        $date = $_POST['date'];
        $works = $_POST['works'];

        // Update record in the database
        $stmt = $pdo->prepare("UPDATE $tableName SET entry_time = ?, exit_time = ?, date = ?, works = ? WHERE id = ?");
        $stmt->execute([$entryTime, $exitTime, $date, $works, $id]);
    }
}

// Fetch records from the selected semester table
$records = $pdo->query("SELECT * FROM $tableName")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
        }
        input, button {
            margin: 10px 5px 10px 0;
            font-weight: bold;
        }
        label {
            font-weight: bold;
        }
        .input-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 16px;
            text-align: left;
            background-color: #004d00;
            color: white;
        }
        tr:nth-child(even) td {
            background-color: #006400;
        }
        h1 {
            color: #004d00;
            font-weight: bold;
        }
        #addWorkSection {
            display: none;
        }
    </style>
</head>
<body>
<center>
    <h1>Lab Works</h1>
</center>

<h2>Lab Records</h2>

<div class="input-group">
    <label for="semesterSelect">Select Semester:</label>
    <form method="POST">
        <select id="semesterSelect" name="semester" onchange="this.form.submit()">
            <option value="" disabled>Select semester</option>
            <option value="1" <?= $selectedSemester == 1 ? 'selected' : '' ?>>Semester 1</option>
            <option value="2" <?= $selectedSemester == 2 ? 'selected' : '' ?>>Semester 2</option>
            <option value="3" <?= $selectedSemester == 3 ? 'selected' : '' ?>>Semester 3</option>
            <option value="4" <?= $selectedSemester == 4 ? 'selected' : '' ?>>Semester 4</option>
            <option value="5" <?= $selectedSemester == 5 ? 'selected' : '' ?>>Semester 5</option>
            <option value="6" <?= $selectedSemester == 6 ? 'selected' : '' ?>>Semester 6</option>
        </select>
    </form>
</div>

<table id="recordsTable">
    <thead>
        <tr>
            <th>Entry Time</th>
            <th>Exit Time</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $record): ?>
            <tr>
                <td><?= htmlspecialchars($record['entry_time']) ?></td>
                <td><?= htmlspecialchars($record['exit_time']) ?></td>
                <td><?= htmlspecialchars($record['date']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $record['id'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit">Delete</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $record['id'] ?>">
                        <input type="hidden" name="action" value="edit">
                        <button type="submit">Edit</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<button id="addLabWorkBtn">Add Lab Work</button>

<div id="addWorkSection" style="display:<?= $editRecord ? 'block' : 'none' ?>;">
    <form method="POST">
        <input type="hidden" name="action" value="<?= $editRecord ? 'update' : 'add' ?>">
        <input type="hidden" name="id" value="<?= $editRecord['id'] ?? '' ?>">
        <input type="hidden" name="semester" value="<?= $selectedSemester ?>">
        <div class="input-group">
            <label for="entryTime">Entry Time:</label>
            <input type="time" name="entryTime" required value="<?= $editRecord['entry_time'] ?? '' ?>">
            
            <label for="exitTime">Exit Time:</label>
            <input type="time" name="exitTime" required value="<?= $editRecord['exit_time'] ?? '' ?>">
            
            <label for="date">Date:</label>
            <input type="date" name="date" required value="<?= $editRecord['date'] ?? '' ?>">
            
            <label for="works">Works:</label>
            <input type="text" name="works" placeholder="Enter work" required value="<?= $editRecord['works'] ?? '' ?>">
            
            <button type="submit"><?= $editRecord ? 'Update Record' : 'Add Record' ?></button>
        </div>
    </form>
</div>

<script>
    document.getElementById('addLabWorkBtn').addEventListener('click', function() {
        const addWorkSection = document.getElementById('addWorkSection');
        addWorkSection.style.display = addWorkSection.style.display === 'none' ? 'block' : 'none';
    });
</script>

</body>
</html>

<?php
// Close the connection
$pdo = null;
?>

<?php
// db.php - Database connection
$host = 'localhost';
$db = 'field based activities'; // Correct format, no spaces in DB name
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Handle form submission for adding activities
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];

    $stmt = $pdo->prepare("INSERT INTO activities (title, description, date, location) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $description, $date, $location]);
}

// Handle deletion of an activity
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ?");
    $stmt->execute([$id]);
}

// Handle editing of an activity
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $activity = $pdo->prepare("SELECT * FROM activities WHERE id = ?");
    $activity->execute([$id]);
    $activity = $activity->fetch();
}

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];

    // Ensure the ID is set to avoid errors
    if ($id) {
        $stmt = $pdo->prepare("UPDATE activities SET title = ?, description = ?, date = ?, location = ? WHERE id = ?");
        $stmt->execute([$title, $description, $date, $location, $id]);
    } else {
        echo "Error: Missing ID.";
    }
}

// Retrieve all activities
$activities = $pdo->query("SELECT * FROM activities")->fetchAll();

// Download functionality
if (isset($_GET['download'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="activities.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Title', 'Description', 'Date', 'Location']);

    foreach ($activities as $activity) {
        fputcsv($output, [$activity['title'], $activity['description'], $activity['date'], $activity['location']]);
    }
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field-Based Activities</title>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #2C2C2C;
    color: #E0E0E0;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

h1, h2 {
    color: #F5F5F5;
    text-align: center;
    margin: 20px 0;
}

h1 {
    font-size: 2.5rem;
}

h2 {
    font-size: 1.8rem;
    margin-top: 40px;
}

a {
    text-decoration: none;
    color: #62A1F3;
}

a:hover {
    color: #ffcc00;
}

.download {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #62A1F3;
    border-radius: 4px;
    font-weight: bold;
    color: #FFF;
    display: inline-block;
}

.download:hover {
    background-color: #ffcc00;
    color: #333;
}

/* Form Styling */
form {
    background-color: #3B3B3B;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    max-width: 400px;
    width: 100%;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #FFD700;
}

input[type="text"], input[type="date"], textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #5A5A5A;
    border-radius: 4px;
    background-color: #4A4A4A;
    color: #FFF;
    margin-bottom: 15px;
    font-size: 1rem;
}

input[type="text"]:focus, input[type="date"]:focus, textarea:focus {
    outline: none;
    border-color: #FFD700;
}

textarea {
    resize: vertical;
    height: 100px;
}

button[type="submit"] {
    background-color: #62A1F3;
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 1rem;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}

button[type="submit"]:hover {
    background-color: #ffcc00;
    color: #333;
}

/* Activities List Styling */
.activities-container {
    margin-top: 20px;
    width: 100%;
    max-width: 800px;
}

ul {
    list-style: none;
    padding: 0;
}

.activity-item {
    background-color: #424242;
    border-radius: 8px;
    margin-bottom: 15px;
    padding: 20px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
    color: #FFF;
}

.activity-details strong {
    font-size: 1.2rem;
    color: #FFD700;
}

.activity-details em {
    color: #CCCCCC;
}

.activity-actions {
    margin-top: 10px;
}

.activity-actions a {
    color: #62A1F3;
    font-weight: bold;
    margin-right: 15px;
}

.activity-actions a:hover {
    color: #FFD700;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-container, .activities-container {
        width: 90%;
    }

    form {
        padding: 15px;
    }

    h1 {
        font-size: 2rem;
    }

    h2 {
        font-size: 1.5rem;
    }
}
    </style>
</head>
<body>
    <h1>Field-Based Activities</h1>

    <form method="POST">
        <!-- Include hidden ID input when editing an activity -->
        <?php if (isset($activity)): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($activity['id']); ?>">
        <?php endif; ?>

        <label for="title">Activity Title</label>
        <input type="text" id="title" name="title" required value="<?php echo isset($activity) ? htmlspecialchars($activity['title']) : ''; ?>">

        <label for="description">Description</label>
        <textarea id="description" name="description"><?php echo isset($activity) ? htmlspecialchars($activity['description']) : ''; ?></textarea>

        <label for="date">Date</label>
        <input type="date" id="date" name="date" required value="<?php echo isset($activity) ? htmlspecialchars($activity['date']) : ''; ?>">

        <label for="location">Location</label>
        <input type="text" id="location" name="location" required value="<?php echo isset($activity) ? htmlspecialchars($activity['location']) : ''; ?>">

        <button type="submit" name="<?php echo isset($activity) ? 'update' : 'add'; ?>">
            <?php echo isset($activity) ? 'Update Activity' : 'Add Activity'; ?>
        </button>
    </form>

    <h2>Activities List</h2>
    <div class="activities-container">
        <ul>
            <?php foreach ($activities as $activity): ?>
                <li class="activity-item">
                    <div class="activity-details">
                        <strong><?php echo htmlspecialchars($activity['title']); ?></strong> (ID: <span class="activity-id"><?php echo htmlspecialchars($activity['id']); ?></span>) - <?php echo htmlspecialchars($activity['date']); ?><br>
                        <em><?php echo htmlspecialchars($activity['location']); ?></em><br>
                        <p><?php echo nl2br(htmlspecialchars($activity['description'])); ?></p>
                    </div>
                    <div class="activity-actions">
                        <a href="?edit=<?php echo $activity['id']; ?>">Edit</a>
                        <a href="?delete=<?php echo $activity['id']; ?>" onclick="return confirm('Are you sure you want to delete this activity?');">Delete</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <a class="download" href="?download=true">Download Activities as CSV</a>
</body>
</html>
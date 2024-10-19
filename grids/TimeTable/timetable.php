<?php
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

 include '../../header_nav_footer.php';


// Check if form is submitted for adding a timetable entry
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_entry'])) {
    // Get form data
    $day = $_POST['day'];
    $period = $_POST['period'];
    $subject = $_POST['subject'];
    $semester = $_POST['semester']; // Get semester input

    // SQL query to insert data into timetable
    $sql = "INSERT INTO timetable_tb (day, period, subject, semester) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $day, $period, $subject, $semester); // Added semester to binding

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['message'] = "New record inserted successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }
    $stmt->close();

    // Redirect to the same page to clear POST data
    header("Location: " . $_SERVER['PHP_SELF']);
    exit(); // Ensure no further code is executed
}

// Handle filtering of the timetable
$selected_semester = '';
if (isset($_POST['filter_semester'])) {
    $selected_semester = $_POST['filter_semester'];
}

// Prepare SQL query based on filter
if ($selected_semester) {
    $sql = "SELECT * FROM timetable_tb WHERE semester = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selected_semester);
} else {
    $sql = "SELECT * FROM timetable_tb";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

// Initialize an empty timetable array
$timetable = [
    '1st Hour' => ['Monday' => '', 'Tuesday' => '', 'Wednesday' => '', 'Thursday' => '', 'Friday' => ''],
    '2nd Hour' => ['Monday' => '', 'Tuesday' => '', 'Wednesday' => '', 'Thursday' => '', 'Friday' => ''],
    '3rd Hour' => ['Monday' => '', 'Tuesday' => '', 'Wednesday' => '', 'Thursday' => '', 'Friday' => ''],
    '4th Hour' => ['Monday' => '', 'Tuesday' => '', 'Wednesday' => '', 'Thursday' => '', 'Friday' => ''],
    '5th Hour' => ['Monday' => '', 'Tuesday' => '', 'Wednesday' => '', 'Thursday' => '', 'Friday' => '']
];

// Populate timetable with subjects from the database
while ($row = $result->fetch_assoc()) {
    $timetable[$row['period']][$row['day']] = $row['subject'] ;
}

// Close the statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable Management</title>
    <link rel="stylesheet" href="StyleG6/stylesr.css">
    <style>
    /* Styling for the filter label */
    .filter-label {
        font-size: 14px;
        font-weight: bold;
        color: #333;
        margin-right: 10px;
    }

    /* Enhanced dropdown styling */
    .dropdown {
        width: 220px;
        padding: 8px 12px;
        font-size: 14px;
        border-radius: 8px;
        border: 1px solid #ddd;
        background-color: #f4f4f4;
        color: #333;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Add focus effect to dropdown */
    .dropdown:focus {
        outline: none;
        border-color: #54a6f0;
        background-color: #fff;
        box-shadow: 0 4px 10px rgba(84, 166, 240, 0.3);
    }

    /* Stylish button */
    .btn-attractive {
        color: #ffffff;
        background: linear-gradient(135deg, #36a536, #4fbd4f);
        width: 100px;
        height: 35px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 20px;
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        margin-left: 15px;
        transition: all 0.3s ease;
    }

    /* Add hover effect to button */
    .btn-attractive:hover {
        background: linear-gradient(135deg, #2d8f2d, #3fa13f);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        transform: translateY(-2px); /* Lift the button slightly on hover */
    }

    /* Add focus effect to button */
    .btn-attractive:focus {
        outline: none;
        box-shadow: 0 0 8px rgba(54, 165, 54, 0.8);
    }
    .form{
        margin: 5px;
    }
    
</style>
</head>
<body>
    
    <section id="home">
        <div class="home">
            <h2>Timetable & Courses Taught</h2>
            <a href="#"class="btn-attractive">Timetable Entry</a>
            <a href="course.php" class="btn-attractive">Course Taught</a>
            <div class="form">
            <!-- Semester Filter Form -->
            <form method="POST" action="">
    <label for="filter_semester" class="filter-label">Select Semester:</label>
    <select name="filter_semester" id="filter_semester" class="dropdown">
        <option value="">Select Semesters</option>
        <option value="1 Semester" <?= ($selected_semester == '1 Semester') ? 'selected' : '' ?>>1 Semester</option>
        <option value="2 Semester" <?= ($selected_semester == '2 Semester') ? 'selected' : '' ?>>2 Semester</option>
        <option value="3 Semester" <?= ($selected_semester == '3 Semester') ? 'selected' : '' ?>>3 Semester</option>
        <option value="4 Semester" <?= ($selected_semester == '4 Semester') ? 'selected' : '' ?>>4 Semester</option>
        <option value="5 Semester" <?= ($selected_semester == '5 Semester') ? 'selected' : '' ?>>5 Semester</option>
        <option value="6 Semester" <?= ($selected_semester == '6 Semester') ? 'selected' : '' ?>>6 Semester</option>
    </select>
    <button type="submit">Filter</button>
</form>
</div>

            <h3>Timetable</h3>
            <table style="width: 90%;">
                <tr class="head">
                    <td>Period/Day</td>
                    <td>Monday</td>
                    <td>Tuesday</td>
                    <td>Wednesday</td>
                    <td>Thursday</td>
                    <td>Friday</td>
                </tr>
                <?php
                // Display the filtered timetable rows
                foreach ($timetable as $period => $days) {
                    echo "<tr>";
                    echo "<td>" . $period . "</td>";
                    echo "<td>" . $days['Monday'] . "</td>";
                    echo "<td>" . $days['Tuesday'] . "</td>";
                    echo "<td>" . $days['Wednesday'] . "</td>";
                    echo "<td>" . $days['Thursday'] . "</td>";
                    echo "<td>" . $days['Friday'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>

            <!-- Display message if available -->
            <?php if (isset($_SESSION['message'])): ?>
                <div id="message" style="color: green;"><?php echo $_SESSION['message']; ?></div>
                <script>
                    setTimeout(function() {
                        document.getElementById('message').style.display = 'none'; // Hide the message after 3 seconds
                    }, 3000);
                </script>
                <?php unset($_SESSION['message']); // Clear message after displaying ?>
            <?php endif; ?>

            <!-- Timetable Entry Form -->
            <div class="action">
                <h2>Add Timetable Entry</h2>
                <form method="POST" action="">
                    <table>
                        <tr>
                            <td>Day:</td>
                            <td>
                                <select class="input" name="day">
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Period:</td>
                            <td>
                                <select class="input" name="period">
                                    <option value="1st Hour">1st Hour</option>
                                    <option value="2nd Hour">2nd Hour</option>
                                    <option value="3rd Hour">3rd Hour</option>
                                    <option value="4th Hour">4th Hour</option>
                                    <option value="5th Hour">5th Hour</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Subject:</td>
                            <td><input class="input" type="text" name="subject" required></td>
                        </tr>
                        <tr>
                            <td>Semester:</td>
                            <td>
                                <select class="input" name="semester">
                                    <option value="1 Semester">1 Semester</option>
                                    <option value="2 Semester">2 Semester</option>
                                    <option value="3 Semester">3 Semester</option>
                                    <option value="4 Semester">4 Semester</option>
                                    <option value="5 Semester">5 Semester</option>
                                    <option value="6 Semester">6 Semester</option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td><button type="submit" name="add_entry" style="color: #000;  width: 98%; height: 30px; margin-bottom: 5px; border-radius: 5px; border: 0;">Add/edit Entry</button></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

       
    </section>
</body>
</html>

<?php
// Close the connection
mysqli_close($conn);
?>
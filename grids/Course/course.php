<?php
// Start session
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

// Check if form is submitted for adding, editing, or deleting Course Taught
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Add Course Taught Entry
    if (isset($_POST['add_course_taught'])) {
        $programme = $_POST['programme'];
        $course = $_POST['course'];
        $hours = $_POST['hours'];
        $year = $_POST['year'];

        // Validate required fields
        if (empty($programme) || empty($course) || empty($hours) || empty($year)) {
            $_SESSION['message'] = "All fields are required!";
        } else {
            // Insert new course into database
            $stmt = $conn->prepare("INSERT INTO courses (programme, course, hours, year) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $programme, $course, $hours, $year);

            if ($stmt->execute()) {
                $_SESSION['message'] = "New course taught entry added successfully!";
            } else {
                $_SESSION['message'] = "Error: " . $stmt->error;
            }
            $stmt->close();
        }

        // Redirect to clear POST data
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Edit Course Taught Entry
    if (isset($_POST['edit_course'])) {
        $id = $_POST['edit_course'];
        // Fetch course data for the selected ID
        $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $course_data = $result->fetch_assoc();
        $stmt->close();
    }

    // Save Edited Course Entry
    if (isset($_POST['save_course'])) {
        $id = $_POST['id'];
        $programme = $_POST['programme'];
        $course = $_POST['course'];
        $hours = $_POST['hours'];
        $year = $_POST['year'];

        // Validate required fields
        if (empty($programme) || empty($course) || empty($hours) || empty($year)) {
            $_SESSION['message'] = "All fields are required!";
        } else {
            // Update course data in the database
            $stmt = $conn->prepare("UPDATE courses SET programme = ?, course = ?, hours = ?, year = ? WHERE id = ?");
            $stmt->bind_param("ssisi", $programme, $course, $hours, $year, $id);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Course updated successfully!";
            } else {
                $_SESSION['message'] = "Error: " . $stmt->error;
            }
            $stmt->close();
        }

        // Redirect to clear POST data
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Delete Course Entry
    if (isset($_POST['delete_course'])) {
        $id = $_POST['delete_course'];
        // Delete the course with the given ID
        $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Course deleted successfully!";
        } else {
            $_SESSION['message'] = "Error: " . $stmt->error;
        }
        $stmt->close();

        // Redirect to clear POST data
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Fetch courses based on selected year for sorting
$sort_year = isset($_POST['sort_year']) ? $_POST['sort_year'] : '';
$sql_courses = "SELECT * FROM courses";
if (!empty($sort_year)) {
    $sql_courses .= " WHERE year = '$sort_year'";
}
$result_courses = mysqli_query($conn, $sql_courses);
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
   /* Sort container styles */
.sort-container {
    margin: 20px 0; /* Space above and below the sort container */
    padding: 10px; /* Padding around the container */
    border: 1px solid #ddd; /* Border around the container */
    border-radius: 5px; /* Rounded corners */
    background-color: #f8f9fa; /* Light background color */
    display: flex; /* Flexbox for alignment */
    align-items: center; /* Center vertically */
}
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
    

/* Label styles */
.sort-container label {
    margin-right: 10px; /* Space between label and select */
    font-weight: bold; /* Bold font */
}

/* Select styles */
.sort-container select {
    padding: 8px; /* Padding inside the select */
    border: 1px solid #007bff; /* Border color */
    border-radius: 4px; /* Rounded corners */
    background-color: #ffffff; /* White background */
    cursor: pointer; /* Pointer cursor on hover */
    transition: border-color 0.3s; /* Smooth transition for border color */
}

/* Hover effect on select */
.sort-container select:hover {
    border-color: #0056b3; /* Darker border color on hover */
}

/* Focus effect on select */
.sort-container select:focus {
    outline: none; /* Remove default outline */
    border-color: #0056b3; /* Darker border color on focus */
}

</style>

<script>
    document.getElementById('sort_year').addEventListener('change', function() {
        // You can add any additional JavaScript functionality here
        console.log('Selected year: ' + this.value); // Log the selected year
        // Optionally show a message or perform another action
    });
</script>

</script>

</head>
<body>
    <!-- <section id="nav">
        <div class="left">
            <ul>
                <li><img src="image/logo-removebg-preview.png"></li>
                <li>Welcome</li>
                <li>Name</li>
            </ul>
        </div>
        <div class="right">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="remedialcoaching.php">Remedialcoaching</a></li>

                <li><a href="#">Profile</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </div>
    </section> -->

    <section id="home">
        <div class="home">
            <h2>Timetable & Courses Taught</h2>
            <a href="index.php" class="btn-attractive">Timetable Entry</a>
            <a href="#"class="btn-attractive">Course Taught</a>

            <h3>Courses Taught</h3>

            <!-- Dropdown for sorting by year -->
           <!-- Dropdown for sorting by year -->
<!-- Dropdown for sorting by year -->
<div class="sort-container">
    <form method="POST" action="">
        <label for="sort_year">Sort by Semesters:</label>
        <select name="sort_year" id="sort_year" onchange="this.form.submit()">
            <option value="">All semesters</option>
            <option value="1" <?php if ($sort_year == '1') echo 'selected'; ?>>1 semester</option>
            <option value="2" <?php if ($sort_year == '2') echo 'selected'; ?>>2 semester</option>
            <option value="3" <?php if ($sort_year == '3') echo 'selected'; ?>>3 semester</option>
            <option value="4" <?php if ($sort_year == '4') echo 'selected'; ?>>4 semester</option>
            <option value="5" <?php if ($sort_year == '5') echo 'selected'; ?>>5 semester</option>
            <option value="6" <?php if ($sort_year == '6') echo 'selected'; ?>>6 semester</option>
            <!-- Add more options as needed -->
        </select>
    </form>
</div>
            <table style="width: 90%;">
                <tr class="head">
                    <td>ID</td>
                    <td>Programme</td>
                    <td>Courses Taught</td>
                    <td>Hours</td>
                    <td>Semester </td>
                    <td>Action</td>
                </tr>
                <?php
                // Display courses from the database
                while ($row = mysqli_fetch_assoc($result_courses)) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['programme']}</td>";
                    echo "<td>{$row['course']}</td>";
                    echo "<td>{$row['hours']}</td>";
                    echo "<td>{$row['year']}</td>";
                    echo "<td>";
                    // Edit button
                    echo "<form method='POST' action='' style='display:inline;'>";
                    echo "<input type='hidden' name='edit_course' value='{$row['id']}'>";
                    echo "<button type='submit'>Edit</button>";
                    echo "</form> ";
                    // Delete button
                    echo "<form method='POST' action='' style='display:inline;'>";
                    echo "<input type='hidden' name='delete_course' value='{$row['id']}'>";
                    echo "<button type='submit' onclick='return confirm(\"Are you sure you want to delete this course?\")'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
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

            <!-- Course Taught Entry Form -->
            <div class="action">
                <h2><?php echo isset($course_data) ? 'Edit Course Taught' : 'Add Course Taught'; ?></h2>
                <form method="POST" action="">
                    <table>
                        <tr>
                            <td>Programme:</td>
                            <td><input type="text" name="programme" value="<?php echo isset($course_data) ? $course_data['programme'] : ''; ?>" required></td>
                        </tr>
                        <tr>
                            <td>Course Taught:</td>
                            <td><input type="text" name="course" value="<?php echo isset($course_data) ? $course_data['course'] : ''; ?>" required></td>
                        </tr>
                        <tr>
                            <td>Hours:</td>
                            <td><input type="number" name="hours" value="<?php echo isset($course_data) ? $course_data['hours'] : ''; ?>" required></td>
                        </tr>
                        <tr>
                            <td>Semester :</td>
                            <td>
                                <select name="year" required>
                                    <option value="">Select semester </option>
                                    <option value="1" <?php echo (isset($course_data) && $course_data['year'] == '1') ? 'selected' : ''; ?>>1 semester </option>
                                    <option value="2" <?php echo (isset($course_data) && $course_data['year'] == '2') ? 'selected' : ''; ?>>2 semester </option>
                                    <option value="3" <?php echo (isset($course_data) && $course_data['year'] == '3') ? 'selected' : ''; ?>>3 semester </option>
                                    <option value="4" <?php echo (isset($course_data) && $course_data['year'] == '4') ? 'selected' : ''; ?>>4 semester </option>
                                    <option value="5" <?php echo (isset($course_data) && $course_data['year'] == '5') ? 'selected' : ''; ?>>5 semester </option>
                                    <option value="6" <?php echo (isset($course_data) && $course_data['year'] == '6') ? 'selected' : ''; ?>>6 semester </option>
                                    <!-- Add more options as needed -->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <?php if (isset($course_data)): ?>
                                    <input type="hidden" name="id" value="<?php echo $course_data['id']; ?>">
                                    <button type="submit" name="save_course"  style=" width: 98%; height: 30px; margin-bottom: 5px; border-radius: 5px; border: 0;">Save Changes</button>
                                <?php else: ?>
                                    <button type="submit" name="add_course_taught" style="color: #000; width: 98%; height: 30px; margin-bottom: 5px; border-radius: 5px; border: 0;">Add Course </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </section>
    
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
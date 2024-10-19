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

    
// Insert new record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $student_name = $_POST['student_name'];
    $subject = $_POST['subject'];
    $RE_TIME = $_POST['RE_TIME'];
    $RE_DATE = $_POST['RE_DATE'];

    $sql = "INSERT INTO remedialcoaching (student_name, subject, RE_TIME, RE_DATE) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $student_name, $subject, $RE_TIME, $RE_DATE);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Record added successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete record
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $sql = "DELETE FROM remedialcoaching WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Record deleted successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Update record
if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $student_name = $_POST['student_name'];
    $subject = $_POST['subject'];
    $RE_TIME = $_POST['RE_TIME'];
    $RE_DATE = $_POST['RE_DATE'];

    $sql = "UPDATE remedialcoaching SET student_name = ?, subject = ?, RE_TIME = ?, RE_DATE = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $student_name, $subject, $RE_TIME, $RE_DATE, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Record updated successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all records for display
$sql = "SELECT * FROM remedialcoaching";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remedial Coaching</title>
    <link rel="stylesheet" href="StyleG6/stylesr.css">
    <style>
    section#home div.action table, section#home div.action table tr td input, section#home div.action table tr td select, section#coaching div.action table tr td input {
        width: 98%;
        height: 30px;
        margin-bottom: 5px;
        border-radius: 5px;
        border: 0;
    }
    section#home div.action table tr td , section#coaching div.action table tr td {
        border: 0;
        text-align: left;
    }
    section#home div.action table tr td button.btn, section#coaching div.action table tr td button.btn {
        color: #ffffff;
        background-color: rgb(54, 165, 54);
    }
    .input:focus {
        border: 1px rgb(54, 165, 54);
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

    .form{
        margin: 5px;
    }
    
    </style>
</head>
<body>
    
    <!-- Display any session messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message">
            <p><?php echo $_SESSION['message']; ?></p>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Form to add new remedial coaching entry -->
    <section id="coaching">
        <div class="home">
            <div class="action">
                <h2>Remedial Coaching</h2>
                <table style="width: 90%;">
                    <form action="" method="post">
                        <tr>
                            <td>Student Name:</td>
                            <td><input class="input" type="text" name="student_name" required></td>
                        </tr>
                        <tr>
                            <td>Subject:</td>
                            <td><input class="input" type="text" name="subject" required></td>
                        </tr>
                        <tr>
                            <td>Time:</td>
                            <td><input class="input" type="time" name="RE_TIME" required></td>
                        </tr>
                        <tr>
                            <td>Date:</td>
                            <td><input class="input" type="date" name="RE_DATE" required></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><button  style="color: #000;"type="submit" name="submit"class="btn-attractive">Submit</button></td>
                        </tr>
                    </form>
                </table>
            </div>

            <!-- Display existing records -->
            <h3>Existing Remedial Coaching Records</h3>
            <table border="1" width="90%">
                <tr>
                    <th>Student Name</th>
                    <th>Subject</th>
                    <th>Time</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <?php if (isset($_POST['edit']) && $_POST['edit_id'] == $row['id']): ?>
                            <!-- Editable form when edit is clicked -->
                            <form method="POST" action="">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <td><input type="text" name="student_name" value="<?php echo $row['student_name']; ?>" required></td>
                                <td><input type="text" name="subject" value="<?php echo $row['subject']; ?>" required></td>
                                <td><input type="time" name="RE_TIME" value="<?php echo $row['RE_TIME']; ?>" required></td>
                                <td><input type="date" name="RE_DATE" value="<?php echo $row['RE_DATE']; ?>" required></td>
                                <td>
                                    <button type="submit" name="save">Save</button>
                                    <button type="submit" name="cancel">Cancel</button>
                                </td>
                            </form>
                        <?php else: ?>
                            <!-- Display form when not editing -->
                            <form method="POST" action="">
                                <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                <td><?php echo $row['student_name']; ?></td>
                                <td><?php echo $row['subject']; ?></td>
                                <td><?php echo $row['RE_TIME']; ?></td>
                                <td><?php echo $row['RE_DATE']; ?></td>
                                <td>
                                    <button type="submit"name="edit">Edit</button>
                                </form>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                </form>
                                </td>
                            <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </section>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
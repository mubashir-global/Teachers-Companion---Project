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

    
// Handle form submissions for insert/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entry_id = isset($_POST['entry_id']) ? intval($_POST['entry_id']) : 0;

    if ($entry_id > 0) {
        // Update entry
        $mentor_name = $_POST['mentor_name'];
        $mentor_subject = $_POST['mentor_subject'];
        $mentor_email = $_POST['mentor_email'];
        $student_name = $_POST['student_name'];
        $student_class = $_POST['student_class'];
        $mentoring_process = $_POST['mentoring_process'];

        $update_query = "UPDATE mentoring_tb SET mentor_name='$mentor_name', mentor_subject='$mentor_subject', mentor_email='$mentor_email', student_name='$student_name', student_class='$student_class', mentoring_process='$mentoring_process' WHERE id=$entry_id";

        if ($conn->query($update_query) === TRUE) {
            $_SESSION['message'] = 'Update successful!';
        } else {
            $_SESSION['message'] = 'Update failed: ' . $conn->error;
        }

    } else {
        // Insert new entry
        $mentor_name = $_POST['mentor_name'];
        $mentor_subject = $_POST['mentor_subject'];
        $mentor_email = $_POST['mentor_email'];
        $student_name = $_POST['student_name'];
        $student_class = $_POST['student_class'];
        $mentoring_process = $_POST['mentoring_process'];

        $insert_query = "INSERT INTO mentoring_tb (mentor_name, mentor_subject, mentor_email, student_name, student_class, mentoring_process) VALUES ('$mentor_name', '$mentor_subject', '$mentor_email', '$student_name', '$student_class', '$mentoring_process')";

        if ($conn->query($insert_query) === TRUE) {
            $_SESSION['message'] = 'Insert successful!';
        } else {
            $_SESSION['message'] = 'Insert failed: ' . $conn->error;
        }
    }

    // Redirect back to the mentoring page
    header("Location: Mentoring.php");
    exit();

} elseif (isset($_GET['delete_id'])) {
    // Handle delete request
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM mentoring_tb WHERE id=$delete_id";

    if ($conn->query($delete_query) === TRUE) {
        $_SESSION['message'] = 'Delete successful!';
    } else {
        $_SESSION['message'] = 'Delete failed: ' . $conn->error;
    }

    // Redirect back to the mentoring page
    header("Location: Mentoring.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mentoring Register</title>
    <script>
        function toggleDetails() {
            const details = document.getElementById('mentoringRegisterDetails');
            details.style.display = details.style.display === 'none' || details.style.display === '' ? 'block' : 'none';
        }

        function populateForm(id, mentor_name, mentor_subject, mentor_email, student_name, student_class, mentoring_process) {
            document.getElementById('mentor_name').value = mentor_name;
            document.getElementById('mentor_subject').value = mentor_subject;
            document.getElementById('mentor_email').value = mentor_email;
            document.getElementById('student_name').value = student_name;
            document.getElementById('student_class').value = student_class;
            document.getElementById('mentoring_process').value = mentoring_process;
            document.getElementById('entry_id').value = id; // Hidden input for ID
            
            // Change the button text to "Update"
            document.getElementById('submitButton').innerText = 'Update';
        }

        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this entry?")) {
                window.location.href = "Mentoring.php?delete_id=" + id;
            }
        }

        // Reset button text after form submission
        function resetButton() {
            document.getElementById('submitButton').innerText = 'Submit';
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 40px;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 850px;
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: black;
        }

        .form-row {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            /* font-weight: ; */
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        button {
            padding: 10px;
            border: none;
            background-color: #45a049;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #388e3c;
        }

        .delete-button {
            background-color: #dc3545; /* Red background for delete button */
        }

        .delete-button:hover {
            background-color: #c82333; /* Darker red on hover */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #45a049;
            color: white;
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 10px;
        }

        td div {
            display: flex;
            gap: 10px;
        }

        .button-container {
            text-align: center; /* Center buttons */
        }

        @media (max-width: 600px) {
            input[type="text"],
            input[type="email"],
            textarea {
                width: 100%;
            }

            button {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 align="center">Mentoring Register</h2>

        <?php
        if (isset($_SESSION['message'])) {
            echo "<script>alert('" . htmlspecialchars($_SESSION['message']) . "');</script>";
            unset($_SESSION['message']); // Clear the message after displaying
        }
        ?>

        <form action="Mentoring.php" method="post" onsubmit="resetButton()">
            <div class="form-row">
                <label for="mentor_name">Mentor Name:</label>
                <input type="text" id="mentor_name" name="mentor_name" required>
            </div>
            <div class="form-row">
                <label for="mentor_subject">Mentor Subject:</label>
                <input type="text" id="mentor_subject" name="mentor_subject" required>
            </div>
            <div class="form-row">
                <label for="mentor_email">Mentor Email:</label>
                <input type="email" id="mentor_email" name="mentor_email" required>
            </div>
            <div class="form-row">
                <label for="student_name">Student Name:</label>
                <input type="text" id="student_name" name="student_name" required>
            </div>
            <div class="form-row">
                <label for="student_class">Student Class:</label>
                <input type="text" id="student_class" name="student_class" required>
            </div>
            <div class="form-row">
                <label for="mentoring_process">Mentoring Process:</label>
                <textarea id="mentoring_process" name="mentoring_process" rows="4" required></textarea>
            </div>

            <input type="hidden" id="entry_id" name="entry_id" value="">

            <div class="button-container">
                <button type="submit" id="submitButton">Submit</button>
                <button type="button" id="viewRegisterButton" onclick="toggleDetails()">View Register</button>
            </div>
        </form>

        <div id="mentoringRegisterDetails" style="display: none;">
            <h2>Mentoring Register Details:</h2>
            <table>
                <tr>
                    <th>Sl. No.</th>
                    <th>Mentor Name</th>
                    <th>Mentor Subject</th>
                    <th>Mentor Email</th>
                    <th>Student Name</th>
                    <th>Student Class</th>
                    <th>Mentoring Process</th>
                    <th>Settings</th>
                </tr>
                <?php 
                $result = $conn->query("SELECT id, mentor_name, mentor_subject, mentor_email, student_name, student_class, mentoring_process FROM mentoring_tb");

                if ($result->num_rows > 0) {
                    $serial_no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$serial_no}</td>
                                <td>{$row['mentor_name']}</td>
                                <td>{$row['mentor_subject']}</td>
                                <td>{$row['mentor_email']}</td>
                                <td>{$row['student_name']}</td>
                                <td>{$row['student_class']}</td>
                                <td>{$row['mentoring_process']}</td>
                                <td>
                                    <div>
                                        <button onclick=\"populateForm('{$row['id']}', '{$row['mentor_name']}', '{$row['mentor_subject']}', '{$row['mentor_email']}', '{$row['student_name']}', '{$row['student_class']}', '{$row['mentoring_process']}')\">Edit</button>
                                        <button class='delete-button' onclick=\"confirmDelete({$row['id']})\">Delete</button>
                                    </div>
                                </td>
                              </tr>";
                        $serial_no++;
                    }
                } else {
                    echo "<tr><td colspan='8'>No entries found.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
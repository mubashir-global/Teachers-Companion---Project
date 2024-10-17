<?php
// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "teachers_companion"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding a new entry
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['action'])) {
    // Get the form data
    $date = $_POST['date'];
    $reason = $_POST['reason'];
    $days = (int)$_POST['days']; // Ensure days is an integer
    $teacherId = $_POST['teacherId'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO leave_register (date, reason, days, teacher_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $date, $reason, $days, $teacherId); // s = string, i = integer

    // Execute the statement
    if ($stmt->execute()) {
        $message = "Entry added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Handle edit and delete actions
if (isset($_POST['action'])) {
    if ($_POST['action'] === 'delete') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM leave_register WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($_POST['action'] === 'edit') {
        $id = (int)$_POST['id'];
        $date = $_POST['date'];
        $reason = $_POST['reason'];
        $days = (int)$_POST['days'];
        $teacherId = $_POST['teacherId'];

        $stmt = $conn->prepare("UPDATE leave_register SET date = ?, reason = ?, days = ?, teacher_id = ? WHERE id = ?");
        $stmt->bind_param("ssisi", $date, $reason, $days, $teacherId, $id);
        $stmt->execute();
        $stmt->close();
        $message = "Entry updated successfully!";
    }
}

// Fetch entries from the database
$result = $conn->query("SELECT * FROM leave_register");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Register</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; /* Light background */
            margin: 0;
            padding: 20px;
        }

        /* Container Styles */
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px; /* Set a max width for responsiveness */
            margin: auto; /* Center the container */
        }

        /* Header Styles */
        h1 {
            text-align: center;
            font-weight: bold;
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333; /* Darker text for contrast */
        }

        /* Form Styles */
        form {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px; /* Space between label and input */
            font-weight: bold;
        }

        input[type="text"], input[type="number"], input[type="date"] {
            padding: 10px;
            margin-bottom: 15px; /* Space between inputs */
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            background-color: #4CAF50; /* Green button */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s; /* Smooth transition */
        }

        button:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Controls Styles */
        .controls {
            display: flex;
            justify-content: space-between; /* Space between controls */
            align-items: center;
            margin-top: 20px;
        }

        .controls input[type="text"] {
            flex: 1;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Leave Register</h1>

    <form id="leaveForm" action="leave_register.php" method="post">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
        
        <label for="reason">Reason:</label>
        <input type="text" id="reason" name="reason" required>
        
        <label for="days">Days:</label>
        <input type="number" id="days" name="days" min="1" required>
        
        <label for="teacherId">Teacher ID:</label>
        <input type="text" id="teacherId" name="teacherId" required>
        
        <button type="submit">Add Entry</button>
    </form>

    <!-- Add a hidden form for editing -->
    <form id="editForm" action="leave_register.php" method="post" style="display: none;">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" id="editId">
        
        <label for="editDate">Date:</label>
        <input type="date" id="editDate" name="date" required>
        
        <label for="editReason">Reason:</label>
        <input type="text" id="editReason" name="reason" required>
        
        <label for="editDays">Days:</label>
        <input type="number" id="editDays" name="days" min="1" required>
        
        <label for="editTeacherId">Teacher ID:</label>
        <input type="text" id="editTeacherId" name="teacherId" required>
        
        <button type="submit">Save Changes</button>
    </form>

    <div class="controls">
        <input type="text" id="searchBar" placeholder="Search..." onkeyup="searchTable()">
        <button class="download-button" onclick="downloadTable()">Download</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Reason</th>
                <th>Days</th>
                <th>Teacher ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="leaveTableBody">
            <?php
            if ($result->num_rows > 0) {
                $entryCount = 0; // Initialize entry count
                while ($row = $result->fetch_assoc()) {
                    $entryCount++;
                    echo "<tr data-id='" . $row['id'] . "'>";
                    echo "<td>" . $entryCount . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['reason'] . "</td>";
                    echo "<td>" . $row['days'] . "</td>";
                    echo "<td>" . $row['teacher_id'] . "</td>";
                    echo "<td>
                        <button onclick='editEntry(" . json_encode($row) . ")'>Edit</button>
                        <form action='leave_register.php' method='post' style='display:inline;'>
                            <input type='hidden' name='action' value='delete'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <button type='submit' onclick='return confirm(\"Are you sure you want to delete this entry?\");'>Delete</button>
                        </form>
                    </td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function searchTable() {
        const input = document.getElementById('searchBar');
        const filter = input.value.toLowerCase();
        const tableBody = document.getElementById('leaveTableBody');
        const rows = tableBody.getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let found = false;

            for (let j = 0; j < cells.length; j++) {
                if (cells[j].innerText.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }

            rows[i].style.display = found ? "" : "none";
        }
    }

    function downloadTable() {
        const table = document.getElementById('leaveTableBody');
        let csvContent = "data:text/csv;charset=utf-8,";
        const rows = table.getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const cols = rows[i].getElementsByTagName('td');
            const rowData = Array.from(cols).map(col => col.innerText).join(",");
            csvContent += rowData + "\r\n";
        }

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "leave_register.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function editEntry(row) {
        document.getElementById('editId').value = row.id;
        document.getElementById('editDate').value = row.date;
        document.getElementById('editReason').value = row.reason;
        document.getElementById('editDays').value = row.days;
        document.getElementById('editTeacherId').value = row.teacher_id;

        // Show the edit form
        document.getElementById('editForm').style.display = 'block';
    }
</script>

</body>
</html>









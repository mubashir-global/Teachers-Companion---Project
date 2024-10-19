<?php
// Connect to the database
$servername = "localhost"; // Change if your server is different
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "nazalprojectdb"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the records
$sql = "SELECT id, date, details, under_principal_or_iqac FROM administration_work"; // Include 'id' for deletion
$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Handle deletion
if (isset($_POST['delete'])) {
    $idToDelete = intval($_POST['id']);
    $deleteSql = "DELETE FROM administration_work WHERE id = ?";
    
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $idToDelete);
    
    if ($stmt->execute()) {
        echo "<script>alert('Record deleted successfully!'); window.location.href='view_records.php';</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    
    $stmt->close();
}

// Handle the update
if (isset($_POST['update'])) {
    $idToUpdate = intval($_POST['id']);
    $date = $_POST['date'];
    $details = $_POST['details'];
    $under_principal_or_iqac = $_POST['under_principal_or_iqac'];

    $updateSql = "UPDATE administration_work SET date = ?, details = ?, under_principal_or_iqac = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sssi", $date, $details, $under_principal_or_iqac, $idToUpdate);
    
    if ($stmt->execute()) {
        echo "<script>alert('Record updated successfully!'); window.location.href='view_records.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recorded Data</title>
    <link rel="stylesheet" href="aw.css">
    <style>
        /* Simple CSS for the table */
        .table-container {
            width: 80%;
            margin: 20px auto;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .delete-btn, .edit-btn {
            padding: 5px 10px;
            cursor: pointer;
            border: none;
            border-radius: 4px; /* Rounded corners */
        }

        .delete-btn {
            background-color: #ff4d4d; /* Red color */
            color: white;
        }

        .edit-btn {
            background-color: #4CAF50; /* Green color */
            color: white;
        }

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            text-align: center;
        }

        .modal-header {
            background-color: #ff4d4d; /* Red header */
            color: white;
            padding: 10px;
        }

        .modal-footer {
            padding: 10px;
        }

        .modal-footer button {
            padding: 5px 10px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
        }

        .modal-footer .confirm {
            background-color: #ff4d4d; /* Red color for confirm */
            color: white;
        }

        .modal-footer .cancel {
            background-color: #ccc; /* Grey color for cancel */
            color: black;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <h2>Recorded Administration Works</h2>
        
        <!-- Display table only if there are records -->
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Details</th>
                        <th>Initials of Principal/HOD</th>
                        <th>Action</th> <!-- New column for action -->
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td><?php echo htmlspecialchars($row['details']); ?></td>
                            <td><?php echo htmlspecialchars($row['under_principal_or_iqac']); ?></td>
                            <td>
                                <form action="edit_record.php" method="GET" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="submit" name="edit" value="Edit" class="edit-btn">
                                </form>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="button" class="delete-btn" onclick="openModal(<?php echo $row['id']; ?>)">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No records found.</p>
        <?php endif; ?>
        
        <?php $conn->close(); ?>
    </div>

    <!-- Modal for delete confirmation -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirm Deletion</h2>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this record?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" action="" method="POST">
                    <input type="hidden" name="id" id="recordId">
                    <button type="button" class="cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" name="delete" class="confirm">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById('recordId').value = id;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>

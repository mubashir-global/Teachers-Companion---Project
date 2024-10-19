
<?php
// Database connection settings
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'nazalprojectdb'; // Your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data from the database
$sql = "SELECT id, date, conductingAgency, subject FROM pda"; // Adjust table name and include id for deletion
$result = $conn->query($sql);

// Check if query was successful
if ($result === false) {
    // Output an error message if the query fails
    echo "Error: " . $conn->error;
} else {
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View PDA Data</title>
        <link rel="stylesheet" href="pda.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
                background-color: #f4f4f4;
            }

            .data-container {
                max-width: 800px;
                margin: 0 auto;
                background-color: white;
                padding: 20px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            }

            h2 {
                text-align: center;
                color: #276749; /* Set the title color to #276749 */
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }

            table, th, td {
                border: 1px solid #ddd;
            }

            th, td {
                padding: 12px;
                text-align: left;
            }

            th {
                background-color: #276749; /* Set the column heading background color to #276749 */
                color: white; /* Set the column heading text color to white */
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            tr:hover {
                background-color: #f1f1f1;
            }

            button {
                background-color: #ff4d4d;
                color: white;
                border: none;
                padding: 5px 10px;
                cursor: pointer;
                border-radius: 3px;
            }

            button.edit-btn {
                background-color: #4CAF50;
            }

            button:hover {
                background-color: #ff1a1a;
            }

            button.edit-btn:hover {
                background-color: #45a049;
            }

            /* Responsive design for mobile devices */
            @media (max-width: 600px) {
                table, th, td {
                    display: block;
                    width: 100%;
                }

                th, td {
                    text-align: right;
                }

                th {
                    display: none;
                }

                td {
                    text-align: left;
                    padding-left: 50%;
                    position: relative;
                }

                td:before {
                    content: attr(data-label);
                    position: absolute;
                    left: 0;
                    width: 50%;
                    padding-left: 10px;
                    font-weight: bold;
                    text-align: left;
                }
            }
        </style>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // Function to handle row deletion using AJAX
            function deleteRow(rowId) {
                if (confirm("Are you sure you want to delete this row?")) {
                    $.ajax({
                        url: 'delete.php', // The PHP file that handles deletion
                        type: 'POST',
                        data: { id: rowId },
                        success: function(response) {
                            if (response === 'success') {
                                // Remove the row from the HTML table
                                document.getElementById("row_" + rowId).remove();
                            } else {
                                alert("Error deleting row: " + response);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log("AJAX Error: " + status + error);
                        }
                    });
                }
            }

            // Function to handle row editing
            function editRow(rowId) {
                var row = document.getElementById("row_" + rowId);
                var date = row.querySelector("[data-label='Date']").textContent;
                var agency = row.querySelector("[data-label='Conducting Agency']").textContent;
                var subject = row.querySelector("[data-label='Subject']").textContent;

                // Turn table data cells into input fields for editing
                row.querySelector("[data-label='Date']").innerHTML = "<input type='text' id='edit_date_" + rowId + "' value='" + date + "'>";
                row.querySelector("[data-label='Conducting Agency']").innerHTML = "<input type='text' id='edit_agency_" + rowId + "' value='" + agency + "'>";
                row.querySelector("[data-label='Subject']").innerHTML = "<input type='text' id='edit_subject_" + rowId + "' value='" + subject + "'>";

                // Change the Edit button to a Save button
                row.querySelector(".edit-btn").style.display = 'none';
                row.querySelector(".save-btn").style.display = 'inline-block';
            }

            // Function to save edited row
            function saveRow(rowId) {
                var date = document.getElementById("edit_date_" + rowId).value;
                var agency = document.getElementById("edit_agency_" + rowId).value;
                var subject = document.getElementById("edit_subject_" + rowId).value;

                // Send the updated data via AJAX
                $.ajax({
                    url: 'update.php', // The PHP file that handles updating
                    type: 'POST',
                    data: { 
                        id: rowId,
                        date: date,
                        conductingAgency: agency,
                        subject: subject
                    },
                    success: function(response) {
                        if (response === 'success') {
                            // Update the row with the new values
                            var row = document.getElementById("row_" + rowId);
                            row.querySelector("[data-label='Date']").textContent = date;
                            row.querySelector("[data-label='Conducting Agency']").textContent = agency;
                            row.querySelector("[data-label='Subject']").textContent = subject;

                            // Switch back to Edit mode
                            row.querySelector(".edit-btn").style.display = 'inline-block';
                            row.querySelector(".save-btn").style.display = 'none';
                        } else {
                            alert("Error saving row: " + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX Error: " + status + error);
                    }
                });
            }
        </script>
    </head>
    <body>
        <div class="data-container">
            <h2>Inserted Data</h2>

            <?php
            // Check if there are any results
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Date</th><th>Conducting Agency</th><th>Subject</th><th>Action</th></tr>";
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr id='row_" . $row['id'] . "'>"; // Use the row's id in the HTML id attribute
                    echo "<td data-label='Date'>" . $row['date'] . "</td>";
                    echo "<td data-label='Conducting Agency'>" . $row['conductingAgency'] . "</td>";
                    echo "<td data-label='Subject'>" . $row['subject'] . "</td>";
                    echo "<td data-label='Action'>";
                    echo "<button class='edit-btn' onclick='editRow(" . $row['id'] . ")'>Edit</button>";
                    echo "<button class='save-btn' onclick='saveRow(" . $row['id'] . ")' style='display:none;'>Save</button>";
                    echo "<button onclick='deleteRow(" . $row['id'] . ")'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No data found.";
            }
            ?>
        </div>
    </body>
    </html>

    <?php
}

// Close the connection
$conn->close();
?>

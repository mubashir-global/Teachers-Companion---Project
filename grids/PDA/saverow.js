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

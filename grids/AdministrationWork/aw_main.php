<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AW Form Submission</title>
    <link rel="stylesheet" href="aw.css">
</head>
<body>
    <?php
        include '../../header_nav_footer.php';
        ?>
    <div class="form-container">
        <h2>Administration Works</h2>
        <form action="aw.php" method="POST">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="details">Details:</label>
            <textarea id="details" name="details" placeholder="Enter activity details" required></textarea>

            <label for="initial">Initials of Principal/HOD:</label>
            <input type="text" id="initial" name="initial" placeholder="Enter subject" required>

            <input type="submit" value="Submit">
            <br><br>
            <input type="reset" value="Clear" class="reset-btn">
        </form>
        <br>

        <!-- Button to view recorded data -->
        <form action="view_records.php" method="GET">
            <input type="submit" value="View Recorded Data" class="view-btn">
        </form>
    </div>
</body>
</html>

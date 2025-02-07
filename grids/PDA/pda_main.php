<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FBA Form Submission</title>
    <link rel="stylesheet" href="pda.css">
</head>
<body>
    <?php 
            include '../../header_nav_footer.php';
            ?>
    <div class="form-container">
        <h2>Professional Development Activities</h2>
        <form action="pda.php" method="POST">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="conductingAgency">Conducting Agency:</label>
            <textarea id="conductingAgency" name="conductingAgency" placeholder="Enter agency details" required></textarea>

            <label for="subject">Subject:</label>
            <textarea id="subject" name="subject" placeholder="Enter subject details" required></textarea>

            <input type="submit" value="Submit">
            <br><br>
            <input type="reset" value="Clear" class="reset-btn">
        </form>

       <br>
        <form action="view.php" method="GET">
            <input type="submit" value="View " class="view-btn">
        </form>
    </div>
</body>
</html>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teachers_companion";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admissionnumber = $_POST['admissionnumber'];
    $student = getStudentByAdmissionNumber($conn, $admissionnumber);
}

function getStudentByAdmissionNumber($conn, $admissionnumber) {
    $sql = "SELECT * FROM student_tb WHERE admissionnumber='$admissionnumber'";
    return $conn->query($sql)->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher - Student Search</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Teacher - Student Search</h1>
    <form method="POST">
        <label for="admissionnumber">Admission Number:</label>
        <input type="text" id="admissionnumber" name="admissionnumber" required>
        <button type="submit">Search</button>
    </form>
    <?php if ($student): ?>
        <h2>Student Details</h2>
        <p>Name: <?php echo $student['name']; ?></p>
        <p>Admission Number: <?php echo $student['admissionnumber']; ?></p>
        <p>Second Language: <?php echo $student['secondlanguage']; ?></p>
        <p>Gender: <?php echo $student['gender']; ?></p>
        <p>DOB: <?php echo $student['dob']; ?></p>
        <p>Blood Group: <?php echo $student['bloodgroup']; ?></p>
        <p>Religion: <?php echo $student['religion']; ?></p>
        <p>Caste: <?php echo $student['caste']; ?></p>
        <p>House Name: <?php echo $student['housename']; ?></p>
        <p>Post: <?php echo $student['post']; ?></p>
        <p>PIN: <?php echo $student['pin']; ?></p>
        <p>Place: <?php echo $student['place']; ?></p>
        <p>District: <?php echo $student['district']; ?></p>
        <p>Mobile No: <?php echo $student['mobile_no']; ?></p>
        <p>Email: <?php echo $student['email']; ?></p>
        <p>Guardian Name: <?php echo $student['guardianname']; ?></p>
        <p>Admitted Date: <?php echo $student['admitteddate']; ?></p>
        <p>Department: <?php echo $student['dept']; ?></p>
        <p>Year: <?php echo $student['year']; ?></p>
    <?php endif; ?>
</body>
</html>

<?php $conn->close(); ?>

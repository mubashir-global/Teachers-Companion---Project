<?php
// include('C:\wamp64\www\nazalproject\db_connect.php');
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "nazalprojectdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $details = $_POST['details'];
    $under_principal_or_iqac = $_POST['initial'];


    $sql = "INSERT INTO administration_work
 (date, details, under_principal_or_iqac) VALUES ('$date', '$details', '$initialsofprincipal')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Record added successfully!</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}
$conn->close();


?>
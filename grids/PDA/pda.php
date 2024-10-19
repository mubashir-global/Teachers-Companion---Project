<?php
// include('C:\wamp64\www\nazalproject\db_connect.php');
$servername = "localhost"; 
$username = "root"; 
$password = "Junu123#"; 
$dbname = "teachers_companion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

echo "Connected successfully";
        include '../../header_nav_footer.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $conductingAgency= $_POST['conductingAgency'];
    $subject = $_POST['subject'];


    $sql = "INSERT INTO pda
 (date, conductingAgency, subject) VALUES ('$date', '$conductingAgency', '$subject')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Record added successfully!</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}
$conn->close();


?>
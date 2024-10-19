<?php
include ('../db_connection.php');
include '../../header_nav_footer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $strength = $_POST['strength'];
    $semester = $_POST['semester'];
    $p_date = $_POST['p_date'];
    $a_date = $_POST['a_date'];
    $firstInternal = $_POST['firstInternal'];
    $model_Exam = $_POST['model_Exam'];

    $sql = "INSERT INTO classcharge_tb (name, strength, semester, p_date, a_date, firstInternal, model_Exam) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sssssss", $name, $strength, $semester, $p_date, $a_date, $firstInternal, $model_Exam);
    
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: classchage.php");
    exit();
}
?>

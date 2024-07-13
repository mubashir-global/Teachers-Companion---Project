<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: signin.php");
    exit();
}

include 'db_connection.php';

$id = $_GET['id'];

$sql = "DELETE FROM mentoring_tb WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    header("Location: mentoring.php");
    exit();
} else {
    echo "Error: " . $conn->error;
}
?>
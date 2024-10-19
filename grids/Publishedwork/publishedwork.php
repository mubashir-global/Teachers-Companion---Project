<?php
$host = 'localhost';
$db = 'teachers_companion';
$user = 'root';
$pass = 'Junu123#';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
include '../../header_nav_footer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $uploadDir = 'C:/wamp64/www/published works/uploads/';
    $uploadFile = $uploadDir . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        $stmt = $conn->prepare("INSERT INTO uploaded_files (filename, filepath) VALUES (?, ?)");
        $stmt->bind_param("ss", $file['name'], $uploadFile);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "File upload failed.";
    }
}

// Delete file
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("SELECT filepath FROM uploaded_files WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($filepath);
    $stmt->fetch();
    $stmt->close();

    if (unlink($filepath)) {
        $stmt = $conn->prepare("DELETE FROM uploaded_files WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error deleting file.";
    }
}

// Fetch uploaded files
$result = $conn->query("SELECT * FROM uploaded_files");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Upload</title>
</head>
<body>
    <h1>Upload File</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>

    <h2>Uploaded Files</h2>
    <table border="1">
        <tr>
            <th>Filename</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['filename']); ?></td>
                <td>
                    <a href="<?php echo htmlspecialchars($row['filepath']); ?>" download>Download</a>
                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Remove</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <?php $conn->close(); ?>
</body>
</html>

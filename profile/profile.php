<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: signin.html"); // Redirect to signin page if not logged in
    exit();
}

// Database connection details
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

// Fetch user details from session
$user = $_SESSION['user'];

// Update profile details if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $user['id']; // Assuming 'id' is stored in session
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $mobile_no = $_POST['mobile_no'];
    $designation = $_POST['designation'];
    $qualification = $_POST['qualification'];
    $date_joined = $_POST['date'];

    // SQL update query
    $sql = "UPDATE sign_up_tb SET 
            name = '$name',
            email = '$email',
            address = '$address',
            mobile_no = '$mobile_no',
            designation = '$designation',
            qualification = '$qualification',
            date = '$date_joined'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Update session with new user details
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['address'] = $address;
        $_SESSION['user']['mobile_no'] = $mobile_no;
        $_SESSION['user']['designation'] = $designation;
        $_SESSION['user']['qualification'] = $qualification;
        $_SESSION['user']['date'] = $date_joined;

        $success_message = "Profile updated successfully!";
    } else {
        $error_message = "Error updating profile: " . $conn->error;
    }
}

// Fetch user details again in case of updates
$user = $_SESSION['user'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Teachers Companion</title>
    <link rel="stylesheet" href="styles.css"> <!-- Adjust path to your CSS file -->
    <style>
        /* Add any additional styles for the profile page */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
        }

        .app-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .app-bar .app-bar-left {
            display: flex;
            align-items: center;
        }

        .app-bar .app-bar-left .college-logo {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }

        .app-bar .app-bar-left .welcome-message {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .navbar ul {
            list-style-type: none;
            display: flex;
        }

        .navbar ul li {
            margin-left: 15px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar ul li a:hover {
            background-color: #45a049;
        }

        .navbar ul li a.active {
            background-color: #45a049;
        }

        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-form {
            margin-bottom: 20px;
        }

        .profile-form label {
            font-weight: bold;
        }

        .profile-form input[type="text"],
        .profile-form input[type="email"],
        .profile-form input[type="tel"],
        .profile-form input[type="date"],
        .profile-form textarea {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .profile-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .profile-form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .success {
            background-color: #dff0d8;
            border-color: #d6e9c6;
            color: #3c763d;
        }


        .error {
            background-color: #f2dede;
            border-color: #ebccd1;
            color: #a94442;
        }
    </style>
</head>

<body>

    <header class="app-bar">
        <div class="app-bar-left">
            <img src="assets/img/amallogo.jpeg" alt="College Logo" class="college-logo">
            <span class="welcome-message">Welcome, <?php echo $user['name']; ?></span>
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>

                <li><a href="profile.php" class="active">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <br>
    <div class="profile-container">
        <h2>Edit Profile</h2><br>

        <?php
        // Display success or error messages
        if (isset($success_message)) {
            echo '<div class="message success">' . $success_message . '</div>';
        } elseif (isset($error_message)) {
            echo '<div class="message error">' . $error_message . '</div>';
        }
        ?>

        <form class="profile-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>

            <label for="dept">Department:</label>
            <input type="text" id="dept" name="dept" value="<?php echo $user['dept']; ?>" readonly>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo $user['address']; ?>" required>

            <label for="mobile_no">Mobile No:</label>
            <input type="tel" id="mobile_no" name="mobile_no" value="<?php echo $user['mobile_no']; ?>" required>

            <label for="designation">Designation:</label>
            <input type="text" id="designation" name="designation" value="<?php echo $user['designation']; ?>" required>

            <label for="qualification">Qualification:</label>
            <input type="text" id="qualification" name="qualification" value="<?php echo $user['qualification']; ?>"
                required>

            <label for="date">Date of Joining:</label>
            <input type="date" id="date" name="date" value="<?php echo $user['date']; ?>" required>

            <input type="submit" value="Update Profile">
        </form>
    </div>

</body>

</html>
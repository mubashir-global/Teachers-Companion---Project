<?php
session_start();
include ('db_connection.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $dept = $_POST['dept'];
    $address = $_POST['address'];
    $mobile_no = $_POST['mobile_no'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $designation = $_POST['designation'];
    $qualification = $_POST['qualification'];
    $date = $_POST['date'];

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute SQL statement to insert user data
    $sql = "INSERT INTO sign_up_tb (name, dept, address, mobile_no, email, password, designation, qualification, date)
            VALUES ('$name', '$dept', '$address', '$mobile_no', '$email', '$hashed_password', '$designation', '$qualification', '$date')";

    if ($conn->query($sql) === TRUE) {
        // Registration successful
        $_SESSION['success'] = "Registration successful! Please sign in.";
        header("Location: signin.html"); // Redirect to signin page
        exit();
    } else {
        // Registration failed
        $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch departments from the database
$sql = "SELECT * FROM departments ORDER BY course_type, name";
$departments = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Teachers Companion</title>
    <style>
        /* Inline CSS for Sign Up Page */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* Set full height of viewport */
            background: url('assets\cllg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .signup-background {
            background-color: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            width: 100%;
            max-width: 600px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .signup-container {
            text-align: center;
            height: 100%;
            /* Fill the height of the parent (.signup-background) */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .college-header {
            margin-bottom: 20px;
        }

        .college-logo {
            width: 100px;
            height: auto;
        }

        .college-header h2 {
            font-size: 1.5rem;
            margin: 5px 0;
            color: #333;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .form-column {
            flex: 1;
            padding: 0 10px;
        }

        .form-column label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            text-align: left;
        }

        .form-column input[type="text"],
        .form-column input[type="email"],
        .form-column input[type="tel"],
        .form-column input[type="password"],
        .form-column input[type="date"],
        .form-column input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-column select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .signup-button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .signup-button:hover {
            background-color: #45a049;
        }

        .signup-button:focus {
            outline: none;
        }

        .signup-button:active {
            transform: translateY(2px);
        }

        .signin-link {
            margin-top: 20px;
        }

        .signin-link p {
            margin-bottom: 5px;
            color: #666;
        }

        .signin-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        .signin-link a:hover {
            color: #45a049;
        }
    </style>
</head>

<body>
    <div class="signup-background">
        <div class="signup-container">
            <div class="college-header">
                <img src="assets\amalcollegelogo.png" alt="College Logo" class="college-logo">
                <h2>Teachers Companion</h2>
                <h2>Amal College</h2>
            </div>
            <form id="signupForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-column">

                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>

                        <label for="dept">Department:</label>
                        <select id="dept" name="dept" required>
                            <option value="">Select Department</option>
                            <?php
                            $current_course_type = '';
                            while ($row = $departments->fetch_assoc()) {
                                if ($row['course_type'] !== $current_course_type) {
                                    if ($current_course_type !== '') {
                                        echo "</optgroup>";
                                    }
                                    $current_course_type = $row['course_type'];
                                    echo "<optgroup label='" . $current_course_type . "'>";
                                }
                                echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                            }
                            if ($current_course_type !== '') {
                                echo "</optgroup>";
                            }
                            ?>
                        </select>

                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" required>

                        <label for="mobile_no">Mobile No:</label>
                        <input type="tel" id="mobile_no" name="mobile_no" required>
                    </div>
                    <div class="form-column">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>

                        <label for="designation">Designation:</label>
                        <input type="text" id="designation" name="designation" required>

                        <label for="qualification">Qualification:</label>
                        <input type="text" id="qualification" name="qualification" required>

                        <label for="date">Date of Joining:</label>
                        <input type="date" id="date" name="date" required>

                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>

                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <input type="submit" value="Sign Up" class="signup-button">
            </form>
            <div class="signin-link">
                <p>Already have an account? <a href="signin.html">Sign in</a></p>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('signupForm').addEventListener('submit', function (event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                event.preventDefault();
            }
        });
    </script>
</body>

</html>
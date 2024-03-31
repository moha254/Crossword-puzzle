<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crossword_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

global $conn; // Make $conn global

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username, email, and password from form
    $Admin_Name = $_POST['AdminName']; // Change this line
    $Admin_password = $_POST['Adminpassword']; // Change this line

    // Prepare SQL statement to fetch admin data
    $sql = "SELECT * FROM `admin_login` WHERE `Admin_Name` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $Admin_Name); // Placeholder for username

    // Store result so we can check if account exists in a secure way.
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
      
        if ($admin['Admin_password'] == md5($Admin_password)) {
         
            $_SESSION['id'] = $admin['id'];
            header("Location: AdminPanel.php");
            exit;
        } else {
            // Password is incorrect
            $error = "Invalid password";
        }
    } else {
        // Username or email not found
        $error = "Username or email not found";
    }
    
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 400px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }

        .login-form h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 20px;
        }

        .btn-primary {
            border-radius: 20px;
            width: 100%;
        }

        .extra {
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="login-form">
                    <h2>ADMIN LOGIN PANEL</h2>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="AdminName" class="form-label">Admin Name</label>
                            <input type="text" class="form-control" id="AdminName" name="AdminName">
                        </div>
                        <div class="mb-3">
                            <label for="Adminpassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="Adminpassword" name="Adminpassword">
                        </div>
                        <button type="submit" class="btn btn-primary" name="Signin">Sign In</button>
                        

                        <div class="extra">
                            <a href="#">Forget Password?</a>
                            
                        
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

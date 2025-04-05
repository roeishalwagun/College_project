<?php
// Start the session
session_start();

// Include your database connection file
include 'config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Get the form data for registration
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password before saving to the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL to insert user data
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Create a prepared statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        if ($stmt->execute()) {
            // Redirect to login page after successful registration
            header("Location: #");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Get the form data for login
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL to get the user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit();
            } else {
                $login_error = "Invalid password!";
            }
        } else {
            $login_error = "No user found with this email!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            width: 400px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 10px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .toggle-btns {
            text-align: center;
            margin-top: 10px;
        }
        .toggle-btns a {
            text-decoration: none;
            color: #4CAF50;
        }
        .form-box {
            display: none;
        }
        .form-box.active {
            display: block;
        }
        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div id="registerForm" class="form-box active">
        <h2>Register</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="register">Register</button>
        </form>
        <p class="toggle-btns">Already have an account? <a href="#" onclick="toggleForm()">Login here</a></p>
    </div>

    <div id="loginForm" class="form-box">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="login">Login</button>
        </form>
        <p class="toggle-btns">Don't have an account? <a href="#" onclick="toggleForm()">Register here</a></p>
        <?php if (isset($login_error)) { echo "<p class='error-message'>$login_error</p>"; } ?>
    </div>
</div>

<script>
    function toggleForm() {
        document.getElementById('registerForm').classList.toggle('active');
        document.getElementById('loginForm').classList.toggle('active');
    }
</script>

</body>
</html>

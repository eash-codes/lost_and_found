<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // "s" stands for string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user record
        $user = $result->fetch_assoc();

        // The stored hashed password from the database
        $stored_hash = $user['password'];

        // Verify the password entered by the user
        if (password_verify($password, $stored_hash)) {
            // Successful login
            $_SESSION['email'] = $email;  // Store the email in session
            echo "Login successful! Redirecting to the main page...";
            header("refresh:2;url=main.php"); // Redirect to main page after 2 seconds
            exit();
        } else {
            // Invalid password
            echo "Invalid email or password.";
        }
    } else {
        // No user found with the provided email
        echo "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form class="form" method="POST" action="login.php">
        <p class="title">Welcome Back</p>
        <p class="message">Please log in to access the system.</p>
        <label>
            <input required  type="text" class="input" name="email">
            <span>College ID</span>
        </label>
        <label>
            <input required  type="password" class="input" name="password">
            <span>Password</span>
        </label>
        <button class="submit">Sign In</button>
        <p class="no-account">Don't have an account? <a href="signup.php">Sign Up</a></p>
    </form>
</body>
</html>

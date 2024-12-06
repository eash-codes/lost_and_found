<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $display_name = $_POST['display_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone_number = $_POST['phone_number'];  // New phone number field

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'lost_and_found');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert into database
        $sql = "INSERT INTO users (display_name, email, password, phone_number) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $display_name, $email, $hashed_password, $phone_number);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful!');</script>";
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form class="form" method="POST" action="signup.php">
        <p class="title">Register</p>
        <p class="message">Signup now and get full access to our app.</p>

        <label>
            <input required type="text" class="input" name="display_name">
            <span>Display Name</span>
        </label>

        <label>
            <input required type="text" class="input" name="email">
            <span>College ID</span>
        </label>

        <label>
            <input required type="password" class="input" name="password">
            <span>Password</span>
        </label>

        <label>
            <input required type="password" class="input" name="confirm_password">
            <span>Confirm Password</span>
        </label>

        <!-- New phone number field -->
        <label>
            <input required type="text" class="input" name="phone_number">
            <span>Phone Number</span>
        </label>

        <button class="submit" type="submit">Register</button>
        <p class="signin">Already have an account? <a href="login.php">Sign In</a></p>
    </form>
</body>
</html>

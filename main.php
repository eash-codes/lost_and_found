<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch user email from session
$user_email = $_SESSION['email'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="main-container">
        <h1>Welcome, <?php echo htmlspecialchars($user_email); ?>!</h1>
        <p>You are now logged in and can access all features of the app.</p>
        
        <!-- Example content: List of items from the Lost and Found database -->
        <h2>Lost and Found Items</h2>
        <!-- This is where you will query and display items -->
        <!-- For now, it's static content to demonstrate layout -->
        <ul class="lost-items">
            <li>Lost Phone (Found on 12th December)</li>
            <li>Lost Wallet (Found on 5th December)</li>
            <li>Lost Laptop Bag (Found on 1st December)</li>
        </ul>

        <!-- Add a Logout option -->
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>

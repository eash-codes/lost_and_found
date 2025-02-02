<?php
session_start();

// Get the success message from the URL
$success_message = isset($_GET['message']) ? $_GET['message'] : '';

// Redirect to login if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Lost Item - Success</title>
    <link rel="stylesheet" href="styles.css">
    <link href="http://fonts.googleapis.com/css?family=Corben:bold" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Nobile" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="landing-container">
        <div class="welcome-section">
            <h1>Success!</h1>
            <p class="intro-message"><?php echo htmlspecialchars($success_message); ?></p>
        </div>

        <!-- Buttons for navigation -->
        <div class="form-container">
            <a href="view_missing_items.php" class="card-btn">View Missing Items</a>
            <a href="landing.php" class="card-btn">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

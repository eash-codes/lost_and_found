<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch user email from session
$user_email = $_SESSION['email'];

// Include database connection
require_once "db_connection.php";

// Fetch the display name of the logged-in user
$display_name = ""; // Default value in case fetching fails
$sql_user = "SELECT display_name FROM users WHERE email = ?";
if ($stmt = $conn->prepare($sql_user)) {
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $stmt->bind_result($display_name);
    $stmt->fetch();
    $stmt->close();
}

// Fetch lost items from the database
$sql = "SELECT item_name, location_lost, date_lost FROM lost_items ORDER BY date_reported DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Lost and Found</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Main container -->
    <div class="landing-container">
        <div class="welcome-section">
            <h1>Welcome back, <?php echo htmlspecialchars($display_name); ?> !</h1>
            <p class="intro-message">Explore the Lost and Found system. Report and find lost items easily!</p>
        </div>

        <!-- Cards for navigation -->
        <div class="card-container">
            <div class="card">
                <h2>View Lost Items</h2>
                <p>Browse through the list of lost items reported by your fellow students.</p>
                <a href="view_lost_items.php" class="card-btn">View Items</a>
            </div>
            <div class="card">
                <h2>Report a Lost Item</h2>
                <p>Lost something? Let others know by reporting it here!</p>
                <a href="report_lost_item.php" class="card-btn">Report Lost Item</a>
            </div>
            <div class="card">
                <h2>Found an Item?</h2>
                <p>Found something that doesn't belong to you? Post it here and help others!</p>
                <a href="report_found_item.php" class="card-btn">Report Found Item</a>
            </div>
            <div class="card">
                <h2>Missing Items</h2>
                <p>View all currently missing items reported by others.</p>
                <a href="view_missing_items.php" class="card-btn">View Missing Items</a>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="logout-section">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>

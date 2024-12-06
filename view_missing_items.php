<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fetch user email from session
$user_email = $_SESSION['email'];

// Include database connection
require_once "db_connection.php";

// Fetch lost items from the database
$sql = "SELECT item_name, location_lost, date_lost, description, image_path FROM lost_items ORDER BY date_reported DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missing Items</title>
    <link rel="stylesheet" href="vmistyles.css">
</head>
<body>
    <div class="landing-container">
        <div class="welcome-section">
            <h1>Currently Missing Items</h1>
            <p class="intro-message">Hover over the items to see more details. Click the claim button if you have found the item.</p>
        </div>

        <!-- Missing Items Grid -->
        <div class="missing-items-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <!-- Front of the Card -->
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['item_name']); ?>" class="card-image">
                        
                        <!-- Back of the Card -->
                        <div class="card__content">
                            <p class="card__title"><?php echo htmlspecialchars($row['item_name']); ?></p>
                            <p class="card__description"><?php echo htmlspecialchars($row['description']); ?></p>
                            <p><strong>Location Lost:</strong> <?php echo htmlspecialchars($row['location_lost']); ?></p>
                            <p><strong>Date Lost:</strong> <?php echo htmlspecialchars($row['date_lost']); ?></p>
                        </div>
                        <!-- Claim Button -->
                        <button class="claim-btn">Contact Owner</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No missing items reported yet.</p>
            <?php endif; ?>
        </div>
        <br><br>
        <div class="back-btn-container">
            <a href="landing.php" class="card-btn">Back to Dashboard</a>
        </div>
    </div>

    <?php $conn->close(); ?>
</body>
</html>

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

// Fetch found items from the database
$sql = "SELECT id, item_name, description, image_path, location_found, date_found, date_reported, reported_by, phone_number FROM found_items ORDER BY date_reported DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Found Items</title>
    <link rel="stylesheet" href="vmistyles.css">
    <link href="http://fonts.googleapis.com/css?family=Corben:bold" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Nobile" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="landing-container">
        <div class="welcome-section">
            <h1>Found Items</h1>
            <p class="intro-message">Hover over the items to see more details. Click the claim button if this item belongs to you.</p>
        </div>

        <!-- Found Items Grid -->
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
                            <p><strong>Location Found:</strong> <?php echo htmlspecialchars($row['location_found']); ?></p>
                            <p><strong>Date Found:</strong> <?php echo htmlspecialchars($row['date_found']); ?></p>
                        </div>
                        <!-- Claim Button -->
                        <button class="claim-btn" 
                                onclick="showOwnerDetails('<?php echo htmlspecialchars($row['reported_by']); ?>', '<?php echo htmlspecialchars($row['phone_number']); ?>')">
                            Contact Finder
                        </button>

                        <!-- Delete Button (Visible only for specific user) -->
                        <?php if ($user_email == "22151542"): ?>
                            <form action="delete_found_item.php" method="POST" style="margin-top: 10px;">
                                <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No found items reported yet.</p>
            <?php endif; ?>
        </div>
        <br><br>
        <div class="back-btn-container">
            <a href="landing.php" class="card-btn">Back to Dashboard</a>
        </div>
    </div>

    <!-- Modal for Contact Details -->
    <div class="modal-overlay" id="modal-overlay"></div>
    <div class="modal" id="owner-modal">
        <h3>Finder Details</h3>
        <p><strong>Name:</strong> <span id="owner-name"></span></p>
        <p><strong>Phone Number:</strong> <span id="owner-phone"></span></p>
        <span class="modal-close" onclick="closeModal()">Close</span>
    </div>

    <script>
        // Show Finder Details Modal
        function showOwnerDetails(name, phone) {
            document.getElementById("owner-name").innerText = name;
            document.getElementById("owner-phone").innerText = phone;

            document.getElementById("modal-overlay").style.display = "block";
            document.getElementById("owner-modal").style.display = "block";
        }

        // Close Modal
        function closeModal() {
            document.getElementById("modal-overlay").style.display = "none";
            document.getElementById("owner-modal").style.display = "none";
        }
    </script>

    <?php $conn->close(); ?>
</body>
</html>

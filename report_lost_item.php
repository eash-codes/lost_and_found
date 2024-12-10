<?php
session_start();

// Include the database connection file
require_once "db_connection.php";

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$user_email = $_SESSION['email'];

// Initialize variables
$item_name = $description = $image_path = $location_lost = $date_lost = "";
$item_name_err = $description_err = $image_err = $location_lost_err = $date_lost_err = "";
$success_message = "";

// Fetch user details (display_name and phone_number) from the database
$sql_user = "SELECT display_name, phone_number FROM users WHERE email = ?";
if ($stmt_user = $conn->prepare($sql_user)) {
    $stmt_user->bind_param("s", $user_email);
    $stmt_user->execute();
    $stmt_user->store_result();

    if ($stmt_user->num_rows > 0) {
        $stmt_user->bind_result($display_name, $phone_number);
        $stmt_user->fetch();
    } else {
        echo "Error: User not found.";
        exit();
    }
    $stmt_user->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate item name
    if (empty(trim($_POST["item_name"]))) {
        $item_name_err = "Please enter the item name.";
    } else {
        $item_name = trim($_POST["item_name"]);
    }

    // Validate description
    if (empty(trim($_POST["description"]))) {
        $description_err = "Please enter a description.";
    } else {
        $description = trim($_POST["description"]);
    }

    // Validate location found
    if (empty(trim($_POST["location_lost"]))) {
        $location_lost_err = "Please enter the location where the item was lost.";
    } else {
        $location_lost = trim($_POST["location_lost"]);
    }

    // Validate date found
    if (empty(trim($_POST["date_lost"]))) {
        $date_lost_err = "Please enter the date the item was found.";
    } else {
        $date_lost = trim($_POST["date_lost"]);
    }

    // Handle image upload
    if (isset($_FILES["image"])) {
        $image_name = $_FILES["image"]["name"];
        $image_tmp = $_FILES["image"]["tmp_name"];
        $image_path = "uploads/" . $image_name;

        if ($_FILES["image"]["error"] != 0) {
            $image_err = "There was an error uploading the image.";
        } else {
            move_uploaded_file($image_tmp, $image_path);
        }
    }

    // If no errors, insert data into the database
    if (empty($item_name_err) && empty($description_err) && empty($location_found_err) && empty($date_found_err) && empty($image_err)) {
        $sql = "INSERT INTO lost_items (item_name, description, image_path, location_lost, date_lost, date_reported, reported_by, phone_number) 
                VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssss", $item_name, $description, $image_path, $location_lost, $date_lost, $display_name, $phone_number);

            if ($stmt->execute()) {
                // Set success message
                $success_message = "Your found item has been successfully reported.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Lost Item</title>
    <link rel="stylesheet" href="styles.css">
    <link href="http://fonts.googleapis.com/css?family=Corben:bold" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Nobile" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="landing-container">
        <div class="welcome-section">
            <h1>Report a Lost Item</h1>
            <p class="intro-message">Let others know by reporting something you Lost . Fill in the details below.</p>
        </div>

        <?php if (!empty($success_message)): ?>
            <!-- Success message -->
            <div class="success-message">
                <p><?php echo htmlspecialchars($success_message); ?></p>
                <div class="button-group">
                    <a href="view_missing_items.php" class="card-btn">View Currently Missing Items</a>
                    <a href="landing.php" class="card-btn">Back to Dashboard</a>
                </div>
            </div>
        <?php else: ?>
            <!-- Form to report found item -->
            <div class="form-container">
    <form action="report_lost_item.php" method="POST" enctype="multipart/form-data" class="form">
        <p class="title">Report Lost Item</p>
        <p class="message">Please fill in the details below to report a lost item.</p>

        <label>
            <input type="text" class="input" name="item_name" required value="<?php echo htmlspecialchars($item_name); ?>">
            <span>Item Name</span>
        </label>
        <span class="error"><?php echo $item_name_err; ?></span>

        <label>
            <textarea class="input" name="description" required><?php echo htmlspecialchars($description); ?></textarea>
            <span>Description</span>
        </label>
        <span class="error"><?php echo $description_err; ?></span>

        <label>
            <input type="text" class="input" name="location_lost" required value="<?php echo htmlspecialchars($location_lost); ?>">
            <span>Location lost</span>
        </label>
        <span class="error"><?php echo $location_lost_err; ?></span>

        <label>
            <input type="date" class="input" name="date_lost" required value="<?php echo htmlspecialchars($date_lost); ?>">
            <span>Date Lost</span>
        </label>
        <span class="error"><?php echo $date_lost_err; ?></span>

        <label>
            <input type="file" class="input" name="image">
            <span>Upload Image</span>
        </label>
        <span class="error"><?php echo $image_err; ?></span>

        <button type="submit" class="submit">Report Item</button>
    </form>
</div>
        <?php endif; ?>
    </div>
</body>
</html>

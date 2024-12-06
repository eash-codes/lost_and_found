<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch user email from session
$user_email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];

    // Image Upload Handling
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_error = $_FILES['image']['error'];

    if ($image_error === 0) {
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_extension = strtolower($image_extension);
        $allowed_extensions = ['jpg', 'jpeg', 'png'];

        if (in_array($image_extension, $allowed_extensions)) {
            $image_new_name = uniqid('', true) . '.' . $image_extension;
            $image_destination = 'uploads/' . $image_new_name;

            // Move the uploaded file to the server's 'uploads' directory
            if (move_uploaded_file($image_tmp_name, $image_destination)) {
                // Insert the item into the database
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "lost_and_found";

                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check if the connection was successful
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "INSERT INTO lost_items (item_name, description, image_path, date_reported)
                        VALUES ('$item_name', '$description', '$image_destination', NOW())";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>Item reported successfully!</p>";
                } else {
                    echo "<p>Error: " . $conn->error . "</p>";
                }

                $conn->close();
            } else {
                echo "<p>There was an error uploading your file.</p>";
            }
        } else {
            echo "<p>Invalid file type. Only JPG, JPEG, and PNG are allowed.</p>";
        }
    } else {
        echo "<p>Error uploading file.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Lost Items</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Report Lost Item Form -->
<div class="login-box">
    <form action="view_lost_items.php" method="POST" enctype="multipart/form-data">
        <div class="user-box">
            <input type="text" name="item_name" required="">
            <label>Item Name</label>
        </div>
        <div class="user-box">
            <textarea name="description" required=""></textarea>
            <label>Description</label>
        </div>
        <div class="custum-file-upload">
            <label for="file" class="custum-file-upload">
                <div class="icon">
                    <svg viewBox="0 0 24 24" fill="" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z" fill=""></path>
                        </g>
                    </svg>
                </div>
                <div class="text">
                    <span>Click to upload image</span>
                </div>
                <input id="file" type="file" name="image" required>
            </label>
        </div>
        <center>
            <a href="#" class="submit">
                Report Item
                <span></span>
            </a>
        </center>
    </form>
</div>

</body>
</html>

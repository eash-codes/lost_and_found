<?php
session_start();
require_once "db_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];

    $sql = "DELETE FROM lost_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    if ($stmt->execute()) {
        header("Location: view_missing_items.php");
        exit();
    } else {
        echo "Error deleting item.";
    }
}
?>

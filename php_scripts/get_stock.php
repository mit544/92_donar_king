<?php
session_start();
require_once(__DIR__ . '/connection.php');

// Initialize error and success messages
$errors = [];
$success = "";

// Fetch stock items
$sql = "SELECT product_id, item_name, item_image, item_quantity, item_updated_at FROM stock";
$result = $link->query($sql);

if ($result) {
    $stockItems = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $errors[] = "Failed to retrieve stock items. Please check the database connection or query.";
}

// Handle quantity update if form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product_id'], $_POST['new_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $new_quantity = (int)$_POST['new_quantity'];

    if ($new_quantity >= 0) {
        $stmt = $link->prepare("UPDATE products SET item_quantity = ? WHERE product_id = ?");
        $stmt->bind_param("ii", $new_quantity, $product_id);

        if ($stmt->execute()) {
            $success = "Stock updated successfully!";
        } else {
            $errors[] = "Error updating stock. Please try again.";
        }
        $stmt->close();
    } else {
        $errors[] = "Quantity cannot be negative.";
    }
}

// Close connection
$link->close();
?>
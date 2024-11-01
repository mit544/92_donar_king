<?php
session_start();
require_once './connection.php';

// Initialize error and success messages
$errors_log_in = [];
$success = "";

// Fetch stock items
$sql = "SELECT product_id, item_name, quantity, product_added_date, updated_at FROM products";
$result = $link->query($sql);
$stockItems = [];
while ($row = $result->fetch_assoc()) {
    $stockItems[] = $row;
}

// Handle note submission (assuming you have a `stock_notes` table for notes)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['note'], $_POST['product_id'])) {
    $note = trim($_POST['note']);
    $product_id = (int)$_POST['product_id'];
    $user_id = $_SESSION['user_id']; // assuming user is logged in and has a user ID

    // Check if note and product ID are not empty
    if (!empty($note) && $product_id) {
        // Insert note into `stock_notes` table
        $stmt = $link->prepare("INSERT INTO stock_notes (stock_id, user_id, note) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $product_id, $user_id, $note);
        
        if ($stmt->execute()) {
            $success = "Note added successfully!";
        } else {
            $errors_log_in[] = "Error adding note. Try again.";
        }
    } else {
        $errors_log_in[] = "Note and Product ID cannot be empty.";
    }
}

// Close connection
$link->close();
?>

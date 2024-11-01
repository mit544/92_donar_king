<?php
include_once './connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    $stmt = $link->prepare("DELETE FROM stock WHERE product_id = ?");
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        // Success - Redirect with success message
        header('Location: ../manager_dashboard.php?deleted=true');
    } else {
        // Failure - Redirect with error message
        header('Location: ../manager_dashboard.php?deleted=false');
    }

    $stmt->close();
    exit();
} else {
    echo "No item ID received.";
}

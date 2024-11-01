<?php
require_once(__DIR__ . '/connection.php'); // Adjust the path as necessary

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect form data
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_number = $_POST['customer_number'];
    $customer_order = $_POST['customer_order'];
    $customer_ordered_date = date('Y-m-d'); // Set the ordered date to today

    // Prepare an insert statement
    $stmt = $link->prepare("INSERT INTO customers (customer_name, customer_email, customer_number, customer_order, customer_ordered_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $customer_name, $customer_email, $customer_number, $customer_order, $customer_ordered_date);

    // Attempt to execute the statement
    if ($stmt->execute()) {
        // Redirect or display success message
        header("Location: ../front_desk.php"); 
        exit();
    } else {
        // Display an error message
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$link->close();
?>

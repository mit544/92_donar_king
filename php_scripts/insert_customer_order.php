<?php
require_once(__DIR__ . './connection.php'); 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_number = $_POST['customer_number'];
    $customer_order = $_POST['customer_order'];
    $customer_ordered_date = date('Y-m-d'); // Set the ordered date to today

    $stmt = $link->prepare("INSERT INTO customers (customer_name, customer_email, customer_number, customer_order, customer_ordered_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $customer_name, $customer_email, $customer_number, $customer_order, $customer_ordered_date);

    if ($stmt->execute()) {
        header("Location: ../front_desk.php"); 
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$link->close();
?>

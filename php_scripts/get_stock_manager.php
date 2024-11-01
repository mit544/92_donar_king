<?php
// Include the database connection file
include_once 'connection.php';

// Initialize an empty array to store stock data
$rows = [];

try {
    // Define the query to get stock information
    $query = "SELECT product_id, item_name, item_image, item_quantity, item_updated_at FROM stock";

    // Execute the query
    if ($result = mysqli_query($link, $query)) {
        // Fetch each row as an associative array and add it to $rows array
        while ($row = mysqli_fetch_assoc($result)) {
            // Add the image data as a base64 encoded string for display
            if (!empty($row['item_image'])) {
                $row['item_image'] = 'data:image/png;base64,' . base64_encode($row['item_image']);
            }
            $rows[] = $row;
        }
    } else {
        // In case of a query error, log or handle it as necessary
        throw new Exception("Query failed: " . mysqli_error($link));
    }

    // Free result set
    mysqli_free_result($result);
} catch (Exception $e) {
    // Log error or handle it (you can modify this part based on your error-handling strategy)
    error_log($e->getMessage());
}

// Close the database connection
mysqli_close($link);
?>

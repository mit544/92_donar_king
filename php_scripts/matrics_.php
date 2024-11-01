<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "92donarking";     

$link = new mysqli($servername, $username, $password, $dbname);

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$totalItems = 0;
$totalStockValue = 0.0;
$lowStockAlerts = 0;
$recentlyUpdated = '';

$result = $link->query("SELECT COUNT(*) as total FROM stock");
if ($result) {
    $row = $result->fetch_assoc();
    $totalItems = $row['total'];
}

$result = $link->query("SELECT SUM(quantity * price) as total_value FROM items");
if ($result) {
    $row = $result->fetch_assoc();
    $totalStockValue = $row['total_value'];
}

$lowStockThreshold = 5;
$result = $link->query("SELECT COUNT(*) as low_stock FROM stock WHERE quantity < $lowStockThreshold");
if ($result) {
    $row = $result->fetch_assoc();
    $lowStockAlerts = $row['low_stock'];
}

$result = $link->query("SELECT item_name FROM stock ORDER BY item_updated_at DESC LIMIT 1");
if ($result) {
    $row = $result->fetch_assoc();
    $recentlyUpdated = $row['name'];
}

$link->close();

header('Content-Type: application/json');
echo json_encode(array(
    "totalItems" => $totalItems,
    "totalStockValue" => number_format($totalStockValue, 2), // Format to 2 decimal places
    "lowStockAlerts" => $lowStockAlerts,
    "recentlyUpdated" => $recentlyUpdated
));
?>

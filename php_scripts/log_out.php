<?php
session_start();

// Only proceed if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Unset session variables and destroy session as before
    if (isset($_SESSION['user'])) {
        unset($_SESSION['user']);
    }
    if (isset($_SESSION['errors_log_in'])) {
        unset($_SESSION['errors_log_in']);
    }
    if (isset($_SESSION['show_loginpopup'])) {
        unset($_SESSION['show_loginpopup']);
    }
    session_destroy();

 header("Location: ../index.php?logout=success");
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
?>

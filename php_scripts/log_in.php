<?php
session_start();
require_once './connection.php';

$errors_log_in = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $errors_log_in[] = "Please enter both email and password.";
    } else {
        // Update query to retrieve staff_position as well
        $stmt = $link->prepare("SELECT staff_id, staff_fname, staff_email, staff_password, staff_position FROM staff WHERE staff_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $name, $email, $hashed_password, $position);
            $stmt->fetch();


            if (password_verify($password, $hashed_password)) {
                unset($_SESSION['errors_log_in']);
                unset($_SESSION['show_loginpopup']);

                $_SESSION['user'] = [
                    'id' => $id,
                    'email' => $email,
                    'name' => $name,
                    'position' => $position
                ];
                // Redirect based on user position
                if ($position === 'staff') {
                    header("Location: ../staff_dashboard.php?login=success");
                } elseif ($position === 'manager') {
                    header("Location: ../manager_dashboard.php");
                } elseif ($position === 'ceo') {
                    header("Location: ../ceo_dashboard.php");
                } elseif ($position === 'front') {
                    header("Location: ../front_desk.php");
                } else {
                    header("Location: ../default_dashboard.php");
                }
                exit();
            } else {
                $errors_log_in[] = "Invalid password.";
            }
        } else {
            $errors_log_in[] = "No account found with that email address.";
        }
        
        $stmt->close();
    }
}


if (!empty($errors_log_in)) {
    $_SESSION['show_loginpopup'] = true;
    $_SESSION['errors_log_in'] = $errors_log_in;
    header("Location: ../index.php?login=error");
    exit();
}

$link->close();
?>
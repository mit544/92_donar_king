<?php
session_start();
require_once './connection.php';

// Initialize variables
$fname = $lname = $email = $password = $confirm_password = "";
$fname_err = $lname_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate first name
    if (empty(trim($_POST["fname"]))) {
        $fname_err = "Please enter your first name.";
    } else {
        $fname = trim($_POST["fname"]);
    }

    // Validate last name
    if (empty(trim($_POST["lname"]))) {
        $lname_err = "Please enter your last name.";
    } else {
        $lname = trim($_POST["lname"]);
    }

    // Validate email
    if (empty(trim($_POST["username"]))) {
        $email_err = "Please enter your email address.";
    } elseif (!filter_var(trim($_POST["username"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        $sql = "SELECT staff_id FROM staff WHERE staff_email = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["username"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["username"]);
                }
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/', trim($_POST["password"]))) {
        $password_err = "Password must contain at least 8 characters, one uppercase letter, one number, and one special character.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm your password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password !== $confirm_password) {
            $confirm_password_err = "Passwords do not match.";
        }
    }

    // Check for errors and store in session if any
    if (!empty($fname_err) || !empty($lname_err) || !empty($email_err) || !empty($password_err) || !empty($confirm_password_err)) {
        $_SESSION['errors'] = [
            'fname_err' => $fname_err,
            'lname_err' => $lname_err,
            'email_err' => $email_err,
            'password_err' => $password_err,
            'confirm_password_err' => $confirm_password_err,
        ];
        $_SESSION['form_data'] = [
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
        ];
        $_SESSION['show_popup'] = true;
        
        header("Location: ../index.php");
        exit();
    } else {
        // Insert data into database if no errors
        $sql = "INSERT INTO staff (staff_fname, staff_lname, staff_email, staff_password) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $fname, $lname, $email, password_hash($password, PASSWORD_DEFAULT));
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['show_popup'] = false; // No errors, signup successful
                header("location: ../index.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

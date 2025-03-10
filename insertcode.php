<?php
session_start();
require 'dbcon.php'; // Include the database connection file

if (isset($_POST['insertdata'])) {
    // Retrieve form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $referral_name = mysqli_real_escape_string($conn, $_POST['referral_name']);
    $referral_phone = mysqli_real_escape_string($conn, $_POST['referral_phone']);

    // Validate form data
    if (empty($name) || empty($email) || empty($username) || empty($password) || empty($confirm_password) || empty($age) || empty($phone)) {
        $_SESSION['error_message'] = "All fields are required.";
        header("Location: registered.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = "Passwords do not match.";
        header("Location: registered.php");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the database
    $query = "INSERT INTO students (name, email, username, password, age, phone, referral_name, referral_phone) 
              VALUES ('$name', '$email', '$username', '$hashed_password', '$age', '$phone', '$referral_name', '$referral_phone')";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $_SESSION['success_message'] = "Student registered successfully!";
        $_SESSION['success_username'] = $username;
        $_SESSION['success_password'] = $password; // Optional: For testing, remove for production
        header("Location: registered.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to register the student. Please try again.";
        header("Location: registered.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: registered.php");
    exit();
}
?>

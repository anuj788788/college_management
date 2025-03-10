<?php
require 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'check_email' && isset($_POST['email'])) {
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $query = "SELECT * FROM students WHERE email = '$email'";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                echo "Email is already registered.";
            } else {
                echo "";
            }
        }

        if ($action === 'check_username' && isset($_POST['username'])) {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $query = "SELECT * FROM students WHERE username = '$username'";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                echo "Username is already taken.";
            } else {
                echo "";
            }
        }

        if ($action === 'check_phone' && isset($_POST['phone'])) {
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $query = "SELECT * FROM students WHERE phone = '$phone'";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                echo "Phone number is already registered.";
            } else {
                echo "";
            }
        }
    }
}
?>

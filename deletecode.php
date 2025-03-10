<?php
$connection = mysqli_connect("localhost", "root", "", "phpadminpanel");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id']; // Get the ID from the GET parameter

    // First, check if the student exists with the given ID
    $checkQuery = "SELECT * FROM students WHERE id='$id'";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Student exists, proceed to delete
        $query = "DELETE FROM students WHERE id='$id'";
        $query_run = mysqli_query($connection, $query);

        session_start();
        if ($query_run) {
            $_SESSION['status'] = "Data Deleted Successfully";
            header("Location: registered.php"); // Redirect back to the registered students page
            exit;
        } else {
            $_SESSION['status'] = "Error: Data Not Deleted";
            header("Location: registered.php");
            exit;
        }
    } else {
        // Student with the given ID does not exist
        session_start();
        $_SESSION['status'] = "Student not found!";
        header("Location: registered.php");
        exit;
    }
} else {
    // If delete_id is not set, redirect to the registered page
    header("Location: registered.php");
    exit;
}
?>

<?php
include('dbcon.php');
//start session
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['edit_id'];
    $name = $_POST['edit_name'];
    $email = $_POST['edit_email'];
    $age = $_POST['edit_age'];
    $phone = $_POST['edit_phone'];
    $role_as = $_POST['edit_role_as'];

    if($phone == ""){
        $_SESSION['status'] = "Phone Number is required";

        header("Location: registered.php"); // Redirect back to the registered students page
        exit(); 
    } 

    // Prepare an SQL query to update student data
    $query = "UPDATE students SET name = ?, email = ?, age = ?, phone = ?,role_as = ? WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$name, $email, $age, $phone, $role_as, $id]);

    // Set a session message for successful update
    $_SESSION['success_message'] = "Student data updated successfully!";

    header("Location: registered.php"); // Redirect back to the registered students page
    exit(); }
?>
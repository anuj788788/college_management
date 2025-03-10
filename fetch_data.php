<?php
require 'dbcon.php';
// Initialize the session
session_start();
// Example show all username from students table
$username = $_SESSION['username'];

$sql = "
SELECT 
    s.id AS student_id,
    s.name AS student_name,
    s.email,
    s.phone,
    s.balance,
    wh.amount,
    wh.description,
    wh.status,
    wh.date
FROM 
    students s
INNER JOIN 
    wallet_history wh
ON 
    s.username = wh.username
WHERE 
    s.username = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Student ID: " . $row['student_id'] . "<br>";
        echo "Name: " . $row['student_name'] . "<br>";
        echo "Email: " . $row['email'] . "<br>";
        echo "Phone: " . $row['phone'] . "<br>";
        echo "Balance: " . $row['balance'] . "<br>";
        echo "Transaction Amount: " . $row['amount'] . "<br>";
        echo "Description: " . $row['description'] . "<br>";
        echo "Status: " . $row['status'] . "<br>";
        echo "Date: " . $row['date'] . "<br><br>";
    }
} else {
    echo "No transactions found for this user.";
}

$conn->close();
?>

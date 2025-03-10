<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}
require 'dbcon.php';

// In levelincome.php
function getLevelIncome($refferal): bool
{
  global $conn;

  // Fetch current user details
  $level_income = [15, 10, 5, 4, 3]; // Level-wise income percentages

  // Loop through levels
  for ($i = 1; $i <= 5; $i++) {
    if (empty($refferal)) {
      break;
    }

    // Get referred user details
    $query = "SELECT * FROM students WHERE phone = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $refferal);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // If no user is found, exit loop
    if (!$user) {
      break;
    }

    $username = $user['username'];
    // fetch current user name from the session
    $current_user = $_SESSION['username'];
    $disc = "Level " . $i . " Income From " . $_SESSION['username'];
    $status = 1;
    $income12 = $level_income[$i - 1]; // Corrected index

    // Update balance
    $query = "UPDATE students SET balance = balance + ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $income12, $username);
    $stmt->execute();
    $stmt->close();

    // Insert into wallet history
    $query = "INSERT INTO wallet_history (username, amount, description, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sisi', $username, $income12, $disc, $status);
    $stmt->execute();
    $stmt->close();

    // Move to the next level's referral
    $refferal = $user['referral_phone'];
  }

  // Debugging log
  error_log("Level Income Calculation Complete");

  return true; // Ensure this always returns true to allow success
}

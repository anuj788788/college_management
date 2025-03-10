<?php
// Start the session
session_start();
include("header.php");
require 'dbcon.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  echo "<script>window.location = 'login.php';</script>";
  exit();
}

$username = $_SESSION['username'];

// Fetch current user's wallet history
$sql = "SELECT id, username, amount, description, status, date FROM wallet_history WHERE username = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$current_wallet_result = $stmt->get_result();

// Fetch current user's balance
$sql_balance = "SELECT balance FROM students WHERE username = ?";
$stmt_balance = $conn->prepare($sql_balance);
$stmt_balance->bind_param("s", $username);
$stmt_balance->execute();
$balance_result = $stmt_balance->get_result();
$current_user_balance = $balance_result->fetch_assoc()['balance'];
$stmt_balance->close();
?>

<title>My Wallet History</title>

<div class="container">
  <!-- Current User's Wallet History -->
  <h1 class="text-center"><i class="fas fa-wallet"></i> My Wallet History</h1>

  <!-- Display current user's balance -->
  <h2 class="text-center" style="color: green;">Your current balance is: $<?php echo number_format($current_user_balance, 2); ?></h2>

  <div class="table-container">
    <table id="example1" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Amount</th>
          <th>Description</th>
          <th>Status</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($current_wallet_result->num_rows > 0): ?>
          <?php while ($row = $current_wallet_result->fetch_assoc()) : ?>
            <tr>
              <td><?php echo htmlspecialchars($row['id']); ?></td>
              <td><?php echo htmlspecialchars($row['username']); ?></td>
              <td>$<?php echo number_format($row['amount'], 2); ?></td>
              <td><?php echo htmlspecialchars($row['description']); ?></td>
              <td>
                <?php
                if ($row['status'] == 1) {
                  echo '<span style="color: green; font-weight: bold;">Received</span>';
                } else {
                  echo '<span style="color: red; font-weight: bold;">Subtracted</span>';
                }
                ?>
              </td>
              <td><?php echo date('d M, Y h:i A', strtotime($row['date'])); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center">No wallet history found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include("footer.php"); ?>
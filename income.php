<?php
include("header.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  echo "<script>window.location = 'login.php';</script>";
  exit();
}

// Fetch current user's details
$username = $_SESSION['username'];
$sql_user = "SELECT id, name, email, phone, balance, status, role_as, created_at, updated_at 
             FROM students 
             WHERE username=? LIMIT 1";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("s", $username);
$stmt->execute();
$current_user = $stmt->get_result()->fetch_assoc();

// Fetch all students referred by the current user with level tracking (up to level 5)
function fetchReferrals($conn, $referral_name, $referral_phone, $level = 1)
{
  if ($level > 5) {
    return []; // Stop recursion at level 5
  }

  $sql = "SELECT id, name, email, phone, referral_name, referral_phone, age, balance, status, created_at, role_as 
            FROM students 
            WHERE referral_name=? AND referral_phone=? 
            ORDER BY created_at DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $referral_name, $referral_phone);
  $stmt->execute();
  $result = $stmt->get_result();

  $referrals = [];
  while ($row = $result->fetch_assoc()) {
    $row['level'] = $level;

    // Fetch wallet history for this referral
    $wallet_sql = "SELECT id, username, amount, description, status, date 
                       FROM wallet_history 
                       WHERE username = ? 
                       ORDER BY id DESC";
    $wallet_stmt = $conn->prepare($wallet_sql);
    $wallet_stmt->bind_param("s", $row['name']);
    $wallet_stmt->execute();
    $wallet_result = $wallet_stmt->get_result();
    $row['wallet_history'] = $wallet_result->fetch_all(MYSQLI_ASSOC);

    // Fetch children only if level < 5
    if ($level < 5) {
      $row['children'] = fetchReferrals($conn, $row['name'], $row['phone'], $level + 1);
    } else {
      $row['children'] = [];
    }

    $referrals[] = $row;
  }
  return $referrals;
}

$students = fetchReferrals($conn, $current_user['name'], $current_user['phone']);

// Collect all wallet history entries from referrals
$referral_wallet_history = [];
function collectWalletHistory($students, &$referral_wallet_history)
{
  foreach ($students as $student) {
    if (!empty($student['wallet_history'])) {
      foreach ($student['wallet_history'] as $entry) {
        $referral_wallet_history[] = $entry;
      }
    }
    if (!empty($student['children'])) {
      collectWalletHistory($student['children'], $referral_wallet_history);
    }
  }
}

collectWalletHistory($students, $referral_wallet_history);
?>

<title>Referral Wallet History</title>

<div class="container">
  <h1 class="text-center"><i class="fas fa-wallet"></i> Referral Wallet History</h1>
  <!-- Show current user's balance -->
  <h3 class="text-center" style="color: green;">Current Balance: $<?php echo number_format($current_user['balance'], 2); ?></h3>

  <?php if (empty($referral_wallet_history)): ?>
    <div class="alert alert-warning text-center">
      No wallet history found for your referrals.
    </div>
  <?php else: ?>
    <div class="table-container">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($referral_wallet_history as $entry): ?>
            <tr>
              <td><?php echo htmlspecialchars($entry['id']); ?></td>
              <td><?php echo htmlspecialchars($entry['username']); ?></td>
              <td><?php echo htmlspecialchars($entry['description']); ?></td>
              <td>$<?php echo number_format($entry['amount'], 2); ?></td>
              <td><?php
                  if ($entry['status'] == 1) {
                    echo '<span style="color: green; font-weight: bold;">Received</span>';
                  } else {
                    echo '<span style="color: red; font-weight: bold;">Subtracted</span>';
                  }
                  ?></td>
              <td><?php echo date('d M, Y h:i A', strtotime($entry['date'])); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php include("footer.php"); ?>
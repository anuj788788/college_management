<?php
include("header.php");
require 'dbcon.php';
require 'levelincome.php';


if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}

// Fetch user details
function getUserDetails($conn, $username)
{
  $query = "SELECT * FROM students WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_assoc();
}

$user = getUserDetails($conn, $_SESSION['username']);
$paymentSuccess = false;
$paymentError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $amount = 100.00;

  if ($user['balance'] < $amount) {
    $paymentError = 'Insufficient balance. Please deposit funds to your account.';
  }


  // Check if the user has sufficient balance
  if ($user['status'] !== 'Active') {
    if ($user['balance'] < $amount) {
      $paymentError = 'Insufficient balance. Please deposit funds to your account.';
    } else {
      $new_balance = $user['balance'] - $amount;
      $status = 'Active';

      // Update the user's balance and status in the database
      $updateQuery = "UPDATE students SET balance = ?, status = ? WHERE username = ?";
      $stmt = $conn->prepare($updateQuery);
      if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error); // Debugging line
      }
      $stmt->bind_param('dss', $new_balance, $status, $_SESSION['username']);
      $stmt->execute();

      if ($stmt->affected_rows > 0) {
        // Payment successful, re-fetch user details
        $user = getUserDetails($conn, $_SESSION['username']);
        $_SESSION['balance'] = $user['balance'];
        $_SESSION['status'] = $user['status'];

        getLevelIncome($user['referral_phone']); // Call the function to distribute level income

        $paymentSuccess = true;
      } else {
        // Log or output the error if no rows were updated
        $paymentError = 'Payment failed, please try again. No rows were affected.';
        error_log("Payment failed for user: " . $_SESSION['username'] . " - MySQL Error: " . $conn->error); // Debugging log
      }
    }
  } else {
    $paymentError = 'You are already active.';
  }
}


?>

<div class="container">
  <div class="container d-flex justify-content-left align-items-center" style="min-height: 70vh;">
    <div class="row">
      <div class="col-md-12">
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
        <p>Pay fees to achieve active status:</p>

        <!-- Display Success or Error Messages -->
        <?php if ($paymentSuccess): ?>
          <div class="alert alert-info alert-dismissible fade show mt-3" role="alert" id="paymentMessage">
            Payment successful! You are now Active. Your new balance is $<?php echo number_format($user['balance'], 2); ?>.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php elseif ($paymentError): ?>
          <div class="alert alert-info alert-dismissible fade show mt-3" role="alert" id="paymentMessage">
            <?php echo $paymentError; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <!-- Display user details -->
        <ul>
          <li><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></li>
          <li><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
          <li><strong>Age:</strong> <?php echo htmlspecialchars($user['age']); ?></li>
          <li><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></li>
          <li><strong>Referral Name:</strong> <?php echo htmlspecialchars($user['referral_name']); ?></li>
          <li><strong>Current Balance:</strong> $<?php echo number_format($user['balance'], 2); ?></li>
          <li><strong>Status:</strong> <?php echo htmlspecialchars($user['status'] ?? 'Inactive'); ?></li>
          <li><strong>Role:</strong> <?php echo ($user['role_as'] == 0) ? 'Student' : 'Admin'; ?></li>
        </ul>

        <!-- If user status is not Active, show the payment button -->
        <?php if (empty($user['status']) || $user['status'] !== 'Active'): ?>
          <form action="" method="post" onsubmit="return confirmPayment()">
            <button type="submit" name="pay" class="btn btn-primary">Pay $100 Now</button>
          </form>
        <?php else: ?>
          <div class="alert alert-info alert-dismissible fade show mt-3" role="alert" id="paymentMessage">
            You are now Active!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function confirmPayment() {
    return confirm("Are you sure you want to pay $100 fee?");
  }
</script>

<?php include("footer.php"); ?>
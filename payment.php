<?php

include 'header.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['username'])) {
  echo "<script>window.location = 'login.php';</script>";
  exit();
}

$username = $_SESSION['username'];

// Fetch user details
$sql = "SELECT name, email, phone, balance, referral_phone FROM students WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $name, $email, $phone, $balance, $referral_phone);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Fetch cart items
$cart_items = [];
$sql_cart = "SELECT courses.id, courses.name, courses.price, cart.quantity, cart.total_price 
             FROM cart 
             JOIN courses ON cart.course_id = courses.id 
             WHERE cart.username = ? AND cart.payment_status = 0"; // Exclude paid items
$stmt_cart = mysqli_prepare($conn, $sql_cart);
mysqli_stmt_bind_param($stmt_cart, "s", $username);
mysqli_stmt_execute($stmt_cart);
$result = mysqli_stmt_get_result($stmt_cart);
while ($row = mysqli_fetch_assoc($result)) {
  $cart_items[] = $row;
}
mysqli_stmt_close($stmt_cart);

$total_price = array_sum(array_column($cart_items, 'total_price'));

// check if user's balance is enough to pay for the cart items
if ($balance < $total_price) {
  echo "<script>alert('Insufficient balance! Please add funds.');</script>";
}

// check if cart items is empty and redirect to courses page alerting user
if (empty($cart_items)) {
  echo "<script>alert('Your cart is empty! Please add courses.'); window.location = 'courses.php';</script>";
  exit();
}



// Handle Payment
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['pay_now'])) {
  if ($balance >= $total_price) {
    // Deduct user's balance
    $new_balance = $balance - $total_price;
    mysqli_query($conn, "UPDATE students SET balance = '$new_balance' WHERE username = '$username'");

    // Get admin details and pay to him of the total price
    $admin_query = mysqli_query($conn, "SELECT username, balance FROM students WHERE role_as = 1 LIMIT 1");
    $admin = mysqli_fetch_assoc($admin_query);
    $admin_username = $admin['username'];
    $admin_share = round($total_price, 2);
    $new_admin_balance = $admin['balance'] + $admin_share;


    mysqli_query($conn, "INSERT INTO wallet_history (username, amount, description, status, date) 
    VALUES ('$username', '$admin_share', 'Course purchase From " . $_SESSION['username'] . "', 1, NOW())");

    // Commission distribution (Referral Levels)
    $commission_rates = [0.12, 0.10, 0.07, 0.05, 0.03];
    $current_referral = $referral_phone;
    $index = 1;
    $last_valid_username = null;

    foreach ($commission_rates as $rate) {
      // Fetch referral user details
      $ref_query = mysqli_query($conn, "SELECT username, balance, referral_phone FROM students WHERE phone = '$current_referral'");
      $ref_data = mysqli_fetch_assoc($ref_query);
      $commission = round($total_price * $rate, 2);

      if ($ref_data) {
        $referral_username = $ref_data['username'];

        // Update referral user's balance
        mysqli_query($conn, "UPDATE students SET balance = balance + $commission WHERE username = '$referral_username'");
        mysqli_query($conn, "INSERT INTO wallet_history (username, amount, description, status, date)
        VALUES ('$referral_username', '$commission', 'Referral commission level $index From " . $_SESSION['username'] . "', 1, NOW())");



        // Store last valid referral
        $last_valid_referral = $ref_data['referral_phone'];
      } else {
        mysqli_query($conn, "UPDATE students SET balance = balance + $commission WHERE username = '$last_valid_username'");
        mysqli_query($conn, "INSERT INTO wallet_history (username, amount, description, status, date)
           VALUES ('$referral_username', '$commission', 'Referral commission level $index From " . $_SESSION["username"] . "', 1, NOW())");
      }

      // Move to the next referral level
      $last_valid_username = $referral_username;
      $current_referral = $last_valid_referral;
      $index++;
    }


    // Clear cart after successful payment
    mysqli_query($conn, "UPDATE cart SET payment_status = '1' WHERE username = '$username'");

    echo "<script>
  alert('Payment successful!');
  window.location = 'courses.php';
</script>";
  } else {
    echo "<script>
  alert('Insufficient balance! Please add funds.');
</script>";
  }
}

// Fetch Admin Wallet History
$admin_wallet_history = [];
$admin_query = mysqli_query($conn, "SELECT username FROM students WHERE role_as = 1 LIMIT 1");
$admin = mysqli_fetch_assoc($admin_query);
$admin_username = $admin['username'];

$sql_admin_wallet = "SELECT amount, description, status, date FROM wallet_history WHERE username = ? ORDER BY date DESC";
$stmt_admin_wallet = mysqli_prepare($conn, $sql_admin_wallet);
mysqli_stmt_bind_param($stmt_admin_wallet, "s", $admin_username);
mysqli_stmt_execute($stmt_admin_wallet);
$result_admin_wallet = mysqli_stmt_get_result($stmt_admin_wallet);
while ($row = mysqli_fetch_assoc($result_admin_wallet)) {
  $admin_wallet_history[] = $row;
}
mysqli_stmt_close($stmt_admin_wallet);

// Fetch Referral Income
$referral_income = [];
$sql_referral_income = "SELECT username, amount, description, date FROM wallet_history
WHERE description = 'Referral commission' AND username = ?
ORDER BY date DESC";
$stmt_referral_income = mysqli_prepare($conn, $sql_referral_income);
mysqli_stmt_bind_param($stmt_referral_income, "s", $username);
mysqli_stmt_execute($stmt_referral_income);
$result_referral_income = mysqli_stmt_get_result($stmt_referral_income);
while ($row = mysqli_fetch_assoc($result_referral_income)) {
  $referral_income[] = $row;
}
mysqli_stmt_close($stmt_referral_income);
?>

<body>
  <div class="container-fluid">

    <div class="row mt-3">

      <div class="col-md-6">

        <div class="card shadow">

          <div class="card-header d-flex justify-content-between align-items-right">

            <h4>
              <i class="fas fa-user-circle me-2"></i> Your Profile
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </h4>

          </div>

          <div class="card-body">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
            <p><strong>Balance:</strong> $<?php echo htmlspecialchars($balance); ?></p>
            <h5>Total Bill: <strong>$<?php echo $total_price; ?></strong></h5>
            <form method="POST">
              <button type="submit" name="pay_now" class="btn btn-success">Pay Now</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">


    <?php
    include 'dbcon.php'; // Include database connection

    // Fetch all students data
    $query = "SELECT id, name, referral_name FROM students";
    $result = mysqli_query($conn, $query);

    $students = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $students[] = $row;
    }

    // Organize referral data in a hierarchical format
    $referrals = [];
    foreach ($students as $student) {
      $referrer = $student['referral_name'];
      $referee = $student['name'];

      // If there is no referrer, it's a root user
      if (!empty($referrer)) {
        $referrals[$referrer][] = $referee;
      }
    }

    // Recursive function to generate the referral tree
    function generateReferralTree($referrals, $referrer)
    {
      if (!isset($referrals[$referrer])) {
        return; // No referrals for this user
      }

      echo "<ul class='list-group'>";
      foreach ($referrals[$referrer] as $referee) {
        echo "<li class='list-group-item'><i class='fas fa-user text-primary'></i> $referee";
        generateReferralTree($referrals, $referee); // Recursively build the tree
        echo "</li>";
      }
      echo "</ul>";
    }

    // Find top-level referrers (users with no referrer in the database)
    $topReferrers = [];
    foreach ($students as $student) {
      if (empty($student['referral_name'])) {
        $topReferrers[] = $student['name'];
      }
    }

    mysqli_close($conn);
    ?>

    <div class="container-fluid col-md-6">
      <h2 class="mt-3">Referral Tree Structure</h2>

      <!-- Referral Tree Card -->
      <div class="card">
        <div class="card-header bg-primary"><button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
          <h3 class="card-title text-white"><i class="fas fa-sitemap"></i> Referral Hierarchy</h3>
        </div>
        <div class="card-body">
          <?php foreach ($topReferrers as $topReferrer) : ?>
            <div class="mb-3">
              <b><i class="fas fa-user-circle text-success"></i> <?= $topReferrer; ?></b>
              <?php generateReferralTree($referrals, $topReferrer); ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  </div>
</body>

<?php include 'footer.php'; ?>
<?php
session_start();
require 'dbcon.php'; // Include your database connection file

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
  header('Location: index.php');
  exit();
}

// Check if referral phone is provided via GET
$referral_phone = "";
if (isset($_GET['referral_phone']) && !empty($_GET['referral_phone'])) {
  $referral_phone = $_GET['referral_phone'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitize inputs
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $confirm_password = trim($_POST['confirm_password']);
  $age = trim($_POST['age']);
  $phone = trim($_POST['phone']);
  $referral_name = !empty($_POST['referral_name']) ? trim($_POST['referral_name']) : null;
  $referral_phone = !empty($_POST['referral_phone']) ? trim($_POST['referral_phone']) : null;

  // Validation
  if (empty($name) || empty($email) || empty($username) || empty($password) || empty($confirm_password) || empty($age) || empty($phone)) {
    $_SESSION['status'] = 'All fields marked with * are required.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['status'] = 'Invalid email format.';
  } elseif ($password !== $confirm_password) {
    $_SESSION['status'] = 'Passwords do not match.';
  } elseif (!is_numeric($age) || $age < 18) {
    $_SESSION['status'] = 'You must be at least 18 years old to register.';
  } elseif (!preg_match('/^\d{10}$/', $phone)) {
    $_SESSION['status'] = 'Invalid phone number format.';
  } elseif ($referral_phone && !preg_match('/^\d{10}$/', $referral_phone)) { // Validate referral phone if provided
    $_SESSION['status'] = 'Invalid referral phone number format.';
  } else {
    // Check if email or username already exists
    $stmt = $conn->prepare("SELECT * FROM students WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $_SESSION['status'] = 'Email or Username is already registered.';
    } else {
      // Hash the password
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      // Set default values for role_as and balance
      $role_as = 0; // Default role (e.g., student)
      $balance = 0.0; // Default balance

      // Insert the data into the database
      $query = "INSERT INTO students (name, email, username, password, age, phone, referral_name, referral_phone, role_as, balance) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param(
        "ssssisssdi",
        $name,
        $email,
        $username,
        $hashed_password,
        $age,
        $phone,
        $referral_name,
        $referral_phone,
        $role_as,
        $balance
      );
      if ($stmt->execute()) {
        // Redirect to login page with success status
        $_SESSION['status'] = 'Registration successful! Please log in.<br>'
          . 'Your Username is: <b>' . htmlspecialchars($username) . '</b><br>'
          . 'Your Password is: <b>' . htmlspecialchars($password) . '</b>';

        header('Location: login.php?status=success');
        exit(); // Stop further execution
      } else {
        $_SESSION['status'] = 'Registration failed. Please try again.';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sign Up</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>Sign Up</b> Page</a>
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Register a new membership</p>

        <!-- Display Error Message -->
        <?php if (isset($_SESSION['status'])): ?>
          <script>
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: '<?php echo htmlspecialchars($_SESSION['status']); ?>',
            });
          </script>
          <?php unset($_SESSION['status']); ?>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="name">Full Name *</label>
              <input type="text" name="name" class="form-control" placeholder="Full Name" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="email">Email Address *</label>
              <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="username">Username *</label>
              <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="password">Password *</label>
              <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="confirm_password">Confirm Password *</label>
              <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="age">Age *</label>
              <input type="text" name="age" class="form-control" placeholder="Age" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="phone">Phone Number *</label>
              <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="referral_name">Referral Name</label>
              <input type="text" name="referral_name" class="form-control" placeholder="Referral Name (Optional)">
            </div>
          </div>
          <div class="mb-3">
            <label for="referral_phone">Referral Phone</label>
            <input type="text" name="referral_phone" class="form-control" placeholder="Referral Phone (Optional)" value="<?php echo htmlspecialchars($referral_phone); ?>">
          </div>
          <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
      </div>
    </div>
  </div>
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
</body>

</html>
<?php
session_start();
include('dbcon.php');

// Check if the student is already logged in
if (isset($_SESSION['username'])) {
  // Redirect to the index page if already logged in
  header('Location: index.php');
  exit();
}

// Check if the student is already logged in
if (isset($_SESSION['status'])) {
  $error = $_SESSION['status'];
  unset($_SESSION['status']);
}

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $role_as = $_POST['role_as'];


  // Use prepared statements to prevent SQL injection
  $query = "SELECT * FROM students WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    // Fetch user details
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
      // Store details in session variables
      $_SESSION['username'] = $user['username'];
      $_SESSION['name'] = $user['name'];
      $_SESSION['role_as'] = $user['role_as'];

      // Redirect to the index page
      header('Location: index.php');
      exit();
    } else {
      $error = "Invalid Username or Password!";
    }
  } else {
    $error = "Invalid Username or Password!";
  }

  $stmt->close();
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login Page</title>
  <!-- Responsive settings -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- AdminLTE Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>Login</b> Page</a>
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <?php if (isset($error)): ?>
          <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post" action="">
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div>
            <div class="col-4">
              <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
            </div>
          </div>
        </form>


        <p class="mb-1">
          <a href="forgotpassword.php">I forgot my password</a>
        </p>
        <p class="mb-0">
        <div class="text-center mt-3">
          <p>Don't have an account? <a href="signup.php" class="text-primary">Register a new membership</a></p>
        </div>
        </p>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
</body>

</html>
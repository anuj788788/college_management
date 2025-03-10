<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>QuickWeb | Dashboard</title>
  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.min.css" rel="stylesheet">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Contact</a>
        </li>
      </ul>
      <!-- SEARCH FORM -->
      <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form>
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <div class="dropdown">
          <button class="btn btn-primary dropdown-toggle"
            type="button"
            id="dropdownMenuButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            style="color: white,azure ;"
            <?php
            // Start session only if it's not already started
            if (session_status() === PHP_SESSION_NONE) {
              session_start();
            }
            if (isset($_SESSION['username'])) {
              echo '<a href="#" class="d-block">' . htmlspecialchars($_SESSION['name']) . '</a>';
            } else {
              echo '<a href="#" class="d-block">No user</a>';
            }
            ?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="background-color:orange;">
              <a class="dropdown-item" href="logout.php">Logout</a>
            </div>
        </div>
        <?php
        // Include database connection file
        include 'dbcon.php';

        // Check if user is logged in
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

        // Fetch cart items for logged-in user
        $cart_items = [];
        $cart_count = 0;
        if (!empty($username)) {
          $sql_cart = "SELECT cart.id, courses.name, courses.price, cart.quantity, cart.total_price 
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
          $cart_count = count($cart_items);
        }
        ?>


        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-shopping-cart"></i>
            <?php if ($cart_count > 0) : ?>
              <span class="badge badge-danger navbar-badge"><?php echo $cart_count; ?></span>
            <?php endif; ?>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow">
            <span class="dropdown-header font-weight-bold">Cart Items (<?php echo $cart_count; ?>)</span>
            <div class="dropdown-divider"></div>

            <?php if (!empty($cart_items)) : ?>
              <div class="cart-scroll" style="max-height: 300px; overflow-y: auto;">
                <?php foreach ($cart_items as $item) : ?>
                  <a href="cart.php" class="dropdown-item">
                    <div class="media-body">
                      <h6 class="text-dark mb-1 font-weight-bold">
                        <?php echo htmlspecialchars($item['name']); ?>
                      </h6>
                      <p class="text-muted small mb-0">
                        Qty: <strong><?php echo $item['quantity']; ?></strong> |
                        Price: <strong>$<?php echo number_format($item['price'], 2); ?></strong>
                      </p>
                      <p class="text-success small mb-0"><i class="fas fa-dollar-sign"></i> Total: <strong>$<?php echo number_format($item['total_price'], 2); ?></strong></p>
                    </div>
                  </a>
                  <div class="dropdown-divider"></div>
                <?php endforeach; ?>
              </div>
            <?php else : ?>
              <a href="#" class="dropdown-item text-center text-muted">Your cart is empty</a>
            <?php endif; ?>

            <a href="cart.php" class="dropdown-item dropdown-footer btn btn-sm btn-primary text-center">
              <i class="fas fa-shopping-cart"></i> View Cart
            </a>
          </div>
        </li>

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">15</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">15 Notifications</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 new messages
              <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-users mr-2"></i> 8 friend requests
              <span class="float-right text-muted text-sm">12 hours</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-file mr-2"></i> 3 new reports
              <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link">
        <img src="dist/img/boxed-bg.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
          style="opacity: .8">
        <span class="brand-text font-weight-light">QuickWeb College</span>
      </a>
      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="dist/img/boxed-bg.jpg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <?php
            // Start session only if it's not already started
            if (session_status() === PHP_SESSION_NONE) {
              session_start();
            }
            if (isset($_SESSION['name'])) {
              echo '<a href="#" class="d-block">' . htmlspecialchars($_SESSION['name']) . '</a>';
            } else {
              echo '<a href="#" class="d-block">No user</a>';
            }
            ?>

          </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-header">User Setting</li>
            <li class="nav-item">
              <a href="login.php" class="nav-link">
                <i class="fas fa-sign-in-alt"></i>
                <!-- if user is logged in show Dashboard not login -->
                <?php
                if (!isset($_SESSION['username'])) {
                  echo '<p>Login</p>';
                } else {
                  echo '<p>Dashboard</p>';
                }
                ?>
              </a>
              <?php
              // Check if the user is an admin

              if (isset($_SESSION['role_as']) && $_SESSION['role_as'] === 1) {
                echo '</li><li class="nav-item">
                              <a href="registered.php" class="nav-link">
                                <i class="fas fa-users"></i>
                                <p>
                                  Registered Students
                                </p>
                              </a>
                            </li>';
              }
              ?>
            <li class="nav-item">
              <a href="news.php" class="nav-link">
                <i class="fas fa-newspaper"></i>
                <p>
                  College news
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="courses.php" class="nav-link">
                <i class="fas fa-book"></i>
                <p>
                  Courses
                </p>
              </a>
            </li>
            <!--Referrals  -->
            <li class="nav-item">
              <a href="referral.php" class="nav-link">
                <i class="fas fa-users"></i>
                <p>
                  Referrals
                </p>
              </a>
            </li>
            <!-- My wallet -->
            <li class="nav-item">
              <a href="wallet.php" class="nav-link" style="color: green;">
                <i class="fas fa-users"></i>
                <p>
                  My Wallet
                </p>
              </a>
            </li>
            <!-- Referral Wallet -->
            <li class="nav-item">
              <a href="income.php" class="nav-link" style="color: red;">
                <i class="fas fa-users"></i>
                <p>
                  Referral Wallet
                </p>
              </a>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon far fa-plus-square"></i>
                <p>
                  Extras
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="login.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Login</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="signup.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Register</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="forgotpassword.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Forgot Password</p>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
        <!-- /.sidar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
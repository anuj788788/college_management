<?php
// Start session
session_start();

// Include database connection
include 'dbcon.php';
include 'header.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['username'])) {
  echo "<script>window.location = 'login.php';</script>";
  exit();
}


// Fetch user data
$username = $_SESSION['username'];
$sql = "SELECT name, email, phone, balance FROM students WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $name, $email, $phone, $balance);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);
} else {
  die("Database query failed: " . mysqli_error($conn));
}

// Add course to cart
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['course_id'])) {
  $course_id = $_POST['course_id'];
  $status1 = 0;

  // Check if item already exists in cart
  $check_sql = "SELECT * FROM cart WHERE username = ? AND course_id = ?";
  $stmt_check = mysqli_prepare($conn, $check_sql);
  mysqli_stmt_bind_param($stmt_check, "si", $username, $course_id);
  mysqli_stmt_execute($stmt_check);
  $result = mysqli_stmt_get_result($stmt_check);

  if (mysqli_num_rows($result) > 0) {
    echo "This course is already in your cart!";
  } else {
    // Insert into cart
    $insert_sql = "INSERT INTO cart (username, course_id, quantity, total_price, payment_status) 
                   VALUES (?, ?, 1, (SELECT price FROM courses WHERE id = ?), 'pending')";
    $stmt_insert = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($stmt_insert, "sii", $username, $course_id, $course_id);
    if (mysqli_stmt_execute($stmt_insert)) {
      echo "Course added to cart successfully!";
    } else {
      echo "Error adding course to cart.";
    }
  }
  exit();
}

// Fetch cart items
$cart_items = [];
$sql_cart = "SELECT cart.id, courses.name, courses.price, cart.quantity, cart.total_price, cart.payment_status 
             FROM cart 
             JOIN courses ON cart.course_id = courses.id 
             WHERE cart.username = ? AND payment_status = ?";
$stmt_cart = mysqli_prepare($conn, $sql_cart);
if ($stmt_cart) {
  $statuss = 0;
  mysqli_stmt_bind_param($stmt_cart, "si", $username, $statuss);
  mysqli_stmt_execute($stmt_cart);
  $result = mysqli_stmt_get_result($stmt_cart);
  while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
  }
  mysqli_stmt_close($stmt_cart);
} else {
  die("Database query failed: " . mysqli_error($conn));
}

// Handle delete course from cart
if (isset($_POST['delete_cart_item'])) {
  $cart_id = $_POST['cart_id'];
  $delete_sql = "DELETE FROM cart WHERE id = ?";
  $stmt_delete = mysqli_prepare($conn, $delete_sql);
  if ($stmt_delete) {
    mysqli_stmt_bind_param($stmt_delete, "i", $cart_id);
    mysqli_stmt_execute($stmt_delete);
    mysqli_stmt_close($stmt_delete);
    echo "<script>alert('Item removed from cart!'); window.location = 'cart.php';</script>";
  } else {
    die("Error deleting item: " . mysqli_error($conn));
  }
}
?>
<div class="container-fluid">
  <!-- Page Header -->
  <div class="row mt-3">
    <div class="col-12">
      <div class="card card-primary shadow">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-shopping-cart me-2"></i> Your Shopping Cart</h3>
          <div class="card-tools">
            <a href="courses.php" class="btn btn-sm btn-info">
              <i class="fas fa-arrow-left"></i> Back to Courses
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Row for Profile and Cart -->
  <div class="row">
    <!-- User Profile Card (Left Side) -->
    <div class="col-md-4">
      <div class="card card-outline card-primary shadow">
        <div class="card-header text-center">
          <h4 class="card-title">Your Profile</h4>
        </div>
        <div class="card-body text-center">
          <h5><i class="fas fa-user me-2"></i> <?php echo htmlspecialchars($name); ?></h5>
          <p><i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($email); ?></p>
          <p><i class="fas fa-phone me-2"></i> <?php echo htmlspecialchars($phone); ?></p>
          <p><i class="fas fa-wallet me-2"></i> Balance: <strong>$<?php echo htmlspecialchars($balance); ?></strong></p>
          <!-- Total bill -->
          <div class="mt-3">
            <h5 class="card-title">Total Bill:</h5>
            <p><strong>$<?php echo array_sum(array_column($cart_items, 'total_price')); ?></strong></p>
          </div>
          <!-- Checkout Button -->
          <div class="mt-3">
            <a href="payment.php" class="btn btn-block btn-success">
              <i class="fas fa-shopping-cart me-2"></i> Checkout
            </a>
          </div>

        </div>
      </div>
    </div>

    <!-- Cart Items (Right Side) -->
    <div class="col-md-8">
      <div class="row">
        <?php if (!empty($cart_items)) : ?>
          <?php foreach ($cart_items as $item) : ?>
            <div class="col-md-6">
              <div class="card shadow-lg card-outline card-success">
                <div class="card-body">
                  <div class="d-flex justify-content-between">
                    <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                    <span class="badge badge-primary">$<?php echo htmlspecialchars($item['price']); ?></span>
                  </div>
                  <p><strong>Quantity:</strong> <?php echo htmlspecialchars($item['quantity']); ?></p>
                  <p><strong>Total:</strong> $<?php echo htmlspecialchars($item['total_price']); ?></p>
                  <div class="d-flex justify-content-between">
                    <form method="POST">
                      <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                      <button type="submit" name="delete_cart_item" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash-alt"></i> Remove
                      </button>
                    </form>
                    <span class="badge badge-<?php echo ($item['payment_status'] == 'pending') ? 'warning' : 'success'; ?>">
                      <?php echo ucfirst($item['payment_status']); ?>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <div class="col-12 text-center py-4">
            <i class="fas fa-shopping-cart fa-3x text-secondary"></i>
            <h5 class="mt-3 text-muted">Your cart is empty.</h5>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>


<!-- Printable Invoice Section -->

<div id="invoice-content">
  <div class="row mt-3" style="margin-left: 2px;">
    <div class="col-10">

      <div class="card card-primary shadow">

        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title"><i class="fas fa-file-invoice-dollar me-2"></i> Invoice</h3>


        </div>
        <div class="card-body">
          <!-- Print Button (Placed Outside Invoice Content) -->
          <button class="btn btn-success float-right" onclick="printInvoice()">
            <i class="fas fa-print me-1"></i> Print Invoice
          </button>
          <h5><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></h5>
          <h6><strong>Invoice Date:</strong> <?php echo date("Y-m-d H:i:s"); ?></h6>
          <hr>

          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Course Name</th>
                  <th>Price</th>
                  <th>Purchase Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Fetch purchased courses for the logged-in user
                $purchased_courses = [];
                $total_amount = 0;
                if (!empty($username)) {
                  $sql_purchased = "SELECT courses.name, courses.price, cart.updated_at AS purchase_date 
                      FROM cart 
                      JOIN courses ON cart.course_id = courses.id 
                      WHERE cart.username = ? AND cart.payment_status = 1"; // Only include paid courses
                  $stmt_purchased = mysqli_prepare($conn, $sql_purchased);
                  mysqli_stmt_bind_param($stmt_purchased, "s", $username);
                  mysqli_stmt_execute($stmt_purchased);
                  $result = mysqli_stmt_get_result($stmt_purchased);
                  while ($row = mysqli_fetch_assoc($result)) {
                    $purchased_courses[] = $row;
                    $total_amount += $row['price']; // Calculate total amount
                  }
                  mysqli_stmt_close($stmt_purchased);
                }
                ?>
                <?php if (empty($purchased_courses)) : ?>
                  <tr>
                    <td colspan="3" class="text-center">No purchased courses found.</td>
                  </tr>
                <?php else : ?>
                  <?php foreach ($purchased_courses as $course) : ?>
                    <tr>
                      <td><?php echo htmlspecialchars($course['name']); ?></td>
                      <td>$<?php echo number_format($course['price'], 2); ?></td>
                      <td><?php echo htmlspecialchars($course['purchase_date']); ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <h5 class="text-end mt-3"><strong>Total Amount you purchased:</strong> $<?php echo number_format($total_amount, 2); ?></h5>
        </div>
      </div>
    </div>
  </div>
</div>



<script>
  function printInvoice() {
    // Get the invoice content
    const invoiceContent = document.getElementById('invoice-content').cloneNode(true);

    // Create a new print window
    const printWindow = window.open('', '_blank');
    if (printWindow) {
      printWindow.document.write(`
        <html>
          <head>
            <title>Invoice</title>
            <style>
              body { font-family: Arial, sans-serif; padding: 20px; }
              .table { width: 100%; border-collapse: collapse; }
              .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
              .table th { background-color: #f2f2f2; }
              .text-end { text-align: right; }
            </style>
          </head>
          <body>
            ${invoiceContent.innerHTML}
          </body>
        </html>
      `);
      printWindow.document.close();
      printWindow.focus();

      // Ensure printing completes before closing
      printWindow.onload = function() {
        printWindow.print();
        printWindow.onafterprint = function() {
          printWindow.close();
        };
      };
    } else {
      alert("Printing blocked by browser. Allow pop-ups and try again.");
    }
  }
</script>


</body>

<?php include 'footer.php'; ?>
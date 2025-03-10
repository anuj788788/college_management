<?php
session_start();
include 'dbcon.php';

if (!isset($_SESSION['username'])) {
  die("User not logged in.");
}

$username = $_SESSION['username'];

if (!isset($_GET['invoice_id'])) {
  die("Invoice ID is required.");
}

$invoice_id = $_GET['invoice_id'];

// Fetch invoice details
$sql_invoice = "SELECT * FROM invoices WHERE invoice_id = ? AND username = ?";
$stmt = $conn->prepare($sql_invoice);
$stmt->bind_param("ss", $invoice_id, $username);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();

if (!$invoice) {
  die("Invalid Invoice ID.");
}

// Fetch cart items associated with this invoice
$sql_cart = "SELECT courses.name, cart.total_price FROM cart 
             JOIN courses ON cart.course_id = courses.id 
             WHERE cart.username = ? AND cart.payment_status = 'paid'";
$stmt = $conn->prepare($sql_cart);
$stmt->bind_param("s", $username);
$stmt->execute();
$cart_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice - <?php echo $invoice_id; ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="container mt-5">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h3>Invoice</h3>
      </div>
      <div class="card-body">
        <p><strong>Invoice ID:</strong> <?php echo $invoice_id; ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>Total Amount:</strong> $<?php echo number_format($invoice['total_amount'], 2); ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($invoice['status']); ?></p>

        <h5>Purchased Courses</h5>
        <ul class="list-group">
          <?php while ($item = $cart_result->fetch_assoc()) : ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <?php echo htmlspecialchars($item['name']); ?>
              <span class="badge bg-success">$<?php echo number_format($item['total_price'], 2); ?></span>
            </li>
          <?php endwhile; ?>
        </ul>

        <button onclick="window.print()" class="btn btn-success mt-3">Print Invoice</button>
        <a href="courses.php" class="btn btn-secondary mt-3">Back to Courses</a>
      </div>
    </div>
  </div>
</body>

</html>
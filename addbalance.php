<?php
session_start();
require 'dbcon.php'; // Ensure $conn (mysqli connection) is initialized

// Check if the student is logged in
if (!isset($_SESSION['username']) || $_SESSION['role_as'] != "1") {
    $_SESSION['status'] = 'You need to log in first.';
    header('Location: login.php');
    exit();
}

// Check if student ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['status'] = 'Student ID is missing.';
    header('Location: registered.php');
    exit();
}

$student_id = intval($_GET['id']); // Ensure ID is treated as an integer

// Fetch student details
$query = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    $_SESSION['status'] = 'Student not found.';
    header('Location: registered.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = floatval($_POST['amount']);
    $action = $_POST['action'];
    $description = htmlspecialchars($_POST['description']); // Optional: Description for transaction

    // Validate amount
    if ($amount <= 0) {
        $_SESSION['status'] = 'Invalid amount. Please enter a positive number.';
        header('Location: addbalance.php?id=' . $student_id);
        exit();
    }

    // Determine the new balance and status
    if ($action === 'add') {
        $new_balance = $student['balance'] + $amount;
        $status = 1; // Status 1 for adding amount
    } elseif ($action === 'subtract') {
        $new_balance = $student['balance'] - $amount;
        if ($new_balance < 0) {
            $_SESSION['status'] = 'Insufficient balance.';
            header('Location: addbalance.php?id=' . $student_id);
            exit();
        }
        $status = 0; // Status 0 for subtracting amount
    } else {
        $_SESSION['status'] = 'Invalid action.';
        header('Location: addbalance.php?id=' . $student_id);
        exit();
    }

    // Update the balance in the `students` table
    $update_query = "UPDATE students SET balance = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("di", $new_balance, $student_id);

    // Insert transaction into `wallet_history` table
    $insert_query = "INSERT INTO wallet_history (username, amount, description, status, date) VALUES (?, ?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("sdsi", $student['username'], $amount, $description, $status);

    if ($update_stmt->execute() && $insert_stmt->execute()) {
        $_SESSION['success_message'] = 'Balance updated successfully for ' . htmlspecialchars($student['name']);
    } else {
        $_SESSION['status'] = 'Failed to update the balance. Please try again.';
    }

    header('Location: addbalance.php?id=' . $student_id);
    exit();
}
?>
<?php include 'header.php'; ?>
<div class="container">
    <div class="col-md-6 justify-content-center">

        <body>
            <h2>Update Balance for <?= htmlspecialchars($student['name']) ?></h2>

            <!-- Display status messages -->
            <?php if (isset($_SESSION['status'])): ?>
                <div style="color: red;"><?= htmlspecialchars($_SESSION['status']);
                                            unset($_SESSION['status']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div style="color: green;"><?= htmlspecialchars($_SESSION['success_message']);
                                            unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>

            <!-- Balance update form -->
            <form action="" method="POST">
                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="action">Action:</label>
                    <select name="action" id="action" class="form-control" required>
                        <option value="">Select Action</option>
                        <option value="add">Add</option>
                        <option value="subtract">Subtract</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter a description for this transaction (optional)"></textarea>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Update Balance</button>
                </div>
            </form>

            <!-- Display current balance -->
            <p class="mt-3">Current Balance: <?= htmlspecialchars($student['balance']) ?></p>
        </body>
    </div>
</div>
<?php include 'footer.php'; ?>
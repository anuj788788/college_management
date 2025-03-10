<?php

ob_start(); // Turn on output buffering
session_start(); // Start the session
include("header.php");
require 'dbcon.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  $_SESSION['status'] = "You need to login first";
  header("Location: login.php");
  exit();
}

// Check if the user is an admin
if ($_SESSION['role_as'] != 1) {
  $_SESSION['status'] = "Access Denied. You are not authorized to access this page.";
  header("Location: login.php");
  exit();
}

// Fetch data from the students table
$query = "SELECT * FROM students";
$query_run = mysqli_query($conn, $query); // Execute the query
?>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Dashboard</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Registered Students</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- Display session messages -->
<?php if (isset($_SESSION['status'])): ?>
  <div class="alert alert-danger mt-2">
    <?php echo $_SESSION['status']; ?>
  </div>
  <?php unset($_SESSION['status']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success_message'])): ?>
  <div class="alert alert-success mt-2">
    <?php echo $_SESSION['success_message']; ?>
  </div>
  <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>
<div class="container">
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Registered Students</h3>
          <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#studentaddmodal">Add New Student</button>
        </div>

        <!-- Add Student Modal -->
        <div class="modal fade" id="studentaddmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add New Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="insertcode.php" method="POST" id="addStudentForm">
                <div class="modal-body">
                  <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
                    <span id="emailCheck" class="text-danger"></span>
                  </div>
                  <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Enter Username" required>
                    <span id="usernameCheck" class="text-danger"></span>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Age</label>
                    <input type="number" name="age" class="form-control" placeholder="Enter Age" min="18" max="100" required>
                  </div>
                  <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter Phone Number" required>
                    <span id="phoneCheck" class="text-danger"></span>
                  </div>
                  <div class="form-group">
                    <label>Referral Name</label>
                    <input type="text" name="referral_name" class="form-control" placeholder="Enter Referral Name">
                  </div>
                  <div class="form-group">
                    <label>Referral Phone</label>
                    <input type="text" name="referral_phone" class="form-control" placeholder="Enter Referral Phone">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" name="insertdata" class="btn btn-primary">Save Data</button>
                </div>
              </form>
            </div>
          </div>
        </div>


        <!-- Edit Student Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="updatecode.php" method="POST">
                <div class="modal-body">
                  <!-- Hidden ID Field -->
                  <input type="hidden" name="edit_id" id="edit_id">

                  <div class="form-group">
                    <label for="edit_name">Name</label>
                    <input type="text" name="edit_name" id="edit_name" class="form-control" placeholder="Enter Name" required>
                  </div>

                  <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" name="edit_email" id="edit_email" class="form-control" placeholder="Enter Email" required>
                  </div>

                  <div class="form-group">
                    <label for="edit_username">Username</label>
                    <input type="text" name="edit_username" id="edit_username" class="form-control" placeholder="Enter Username" required>
                  </div>

                  <div class="form-group">
                    <label for="edit_password">Password</label>
                    <div class="input-group">
                      <input type="password" name="edit_password" id="edit_password" class="form-control" placeholder="Enter Password">
                      <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility()">
                          <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="edit_age">Age</label>
                    <input type="text" name="edit_age" id="edit_age" class="form-control" placeholder="Enter Age" min="18" max="100" required>
                  </div>

                  <div class="form-group">
                    <label for="edit_phone">Phone</label>
                    <input type="text" name="edit_phone" id="edit_phone" class="form-control" placeholder="Enter Phone Number" required>
                  </div>

                  <div class="form-group">
                    <label for="edit_phone">Role</label>
                    <select name="edit_role_as" id="edit_role_as" class="form-control" value="">
                      <option>Select</option>
                      <option value="0">Student</option>
                      <option value="1">Admin</option>
                    </select>
                  </div>


                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" name="update_data" class="btn btn-primary">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped" overflow="auto">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Age</th>
                <th>Phone</th>
                <th>Referral Name</th>
                <th>Referral Phone</th>
                <th>Role</th>
                <th>Balance</th>
                <th>EDIT</th>
                <th>DELETE</th>
                <th>Add/Minus Balance</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($query_run && mysqli_num_rows($query_run) > 0) {
                while ($row = mysqli_fetch_assoc($query_run)) {
              ?>
                  <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['username']; ?></td>
                    <td><?= $row['age']; ?></td>
                    <td><?= $row['phone']; ?></td>
                    <td><?= $row['referral_name']; ?></td>
                    <td><?= $row['referral_phone']; ?></td>
                    <td>
                      <?php
                      if ($row['role_as'] == 0) {
                        echo "Student";
                      } elseif ($row['role_as'] == 1) {
                        echo "Admin";
                      } else {
                        echo "Invalid Role";
                      }
                      ?>
                    </td>
                    <td><?= $row['balance'] ?? 00.00; ?></td>
                    <td>
                      <button
                        type="button"
                        class="btn btn-success editbtn"
                        id="editbutton"
                        onclick="putEditValues(
                          '<?= $row['id']; ?>', 
                          '<?= addslashes($row['name']); ?>', 
                          '<?= addslashes($row['email']); ?>', 
                          '<?= addslashes($row['username']); ?>',
                          '<?= $row['password']; ?>',
                          '<?= $row['age']; ?>', 
                          '<?= $row['phone']; ?>',
                          '<?= $row['role_as']; ?>'
                        )">
                        EDIT
                      </button>
                    </td>
                    <td>
                      <a href="#" class="btn btn-danger" onclick="confirmDelete(<?= $row['id']; ?>)">DELETE</a>
                    </td>
                    <td>
                      <a href="addbalance.php?id=<?= $row['id']; ?>" class="btn btn-primary">Add/Minus Balance</a>
                    </td>
                  </tr>
              <?php
                }
              } else {
                echo "<tr><td colspan='13'>No Record Found</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include("footer.php"); ?>
<?php
include 'header.php';
require 'dbcon.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
  echo "<script>window.location = 'login.php';</script>";
  exit();
}
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Pagination logic
$limit = 3; // Number of courses per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Offset for SQL query

// Fetch total number of courses
$total_query = "SELECT COUNT(*) as total FROM courses";
$total_result = $conn->query($total_query);
$total_courses = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_courses / $limit); // Total pages

// Fetch courses for the current page
$query = "SELECT * FROM courses LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>
<!-- Splash Image with Button at Bottom Right -->
<div class="splash-image" style="background-image: url('https://images.squarespace-cdn.com/content/v1/664b87af79884c4b3b367344/1725045305051-L09EHYOTE0N9TVEO7CAB/unsplash-image-ykgLX_CwtDw.jpg?format=600w'); background-size: cover; background-position: center; height: 300px; margin: 5px; position: relative; border-radius: 10px;">
  <div class="container h-100 d-flex align-items-end text-left" style="padding: 10px;">
    <h1 class="text-white" style="font-weight: bold;">Welcome to Our Courses</h1>
  </div>

  <!-- Go to Cart Button at Bottom Right -->
  <a href="cart.php" class="btn btn-danger" style="position: absolute; bottom: 10px; right: 10px; box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.3);">
    <i class="fas fa-shopping-cart"></i> Go to Cart
  </a>
</div>


<div class="container mt-4">


  <?php if (isset($_SESSION['username']) && $_SESSION['role_as'] == 1) : ?>
    <!-- If user is admin and logged in, show this -->
    <h2>Manage Courses</h2>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addCourseModal">Add New Course</button>
  <?php endif; ?>


  <div class="row">
    <?php
    if ($result->num_rows > 0) {
      while ($course = $result->fetch_assoc()) {
        echo '<div class="col-md-4 mb-4">';
        echo '<div class="card" style="width: 18rem;">';
        echo '<img src="' . htmlspecialchars($course["image"]) . '" class="card-img-top" alt="img" style="width: 100%; height: 200px; object-fit: cover;">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . htmlspecialchars($course["name"]) . '</h5>';
        echo '<p class="card-text">' . htmlspecialchars($course["description"]) . '</p>';
        echo '<p class="card-text"><strong>Price:</strong> $' . number_format($course["price"], 2) . '</p>';

        // Only show Edit and Delete buttons if the user is NOT an admin and is logged in
        if (isset($_SESSION['username']) && $_SESSION['role_as'] != 0) {
          echo '<button class="btn btn-primary editCourseBtn" 
                        data-id="' . $course["id"] . '"
                        data-name="' . htmlspecialchars($course["name"]) . '"
                        data-description="' . htmlspecialchars($course["description"]) . '"
                        data-price="' . $course["price"] . '"
                        data-image="' . htmlspecialchars($course["image"]) . '"
                        data-bs-toggle="modal" data-bs-target="#editCourseModal">Edit</button> ';

          echo '<button class="btn btn-danger deleteCourseBtn" 
                      data-id="' . $course["id"] . '" 
                      data-bs-toggle="modal" data-bs-target="#deleteCourseModal">Delete</button> ';
        }

        if (!isset($_SESSION['username'])) {
          echo '<a href="login.php" class="btn btn-danger">Login to Buy</a>';
        } else {
          $username = $_SESSION['username'];
          $course_id = $course["id"];

          // Check if the course is in the cart
          $sql_cart = "SELECT payment_status FROM cart WHERE username = ? AND course_id = ?";
          $stmt_cart = mysqli_prepare($conn, $sql_cart);
          mysqli_stmt_bind_param($stmt_cart, "si", $username, $course_id);
          mysqli_stmt_execute($stmt_cart);
          $result_cart = mysqli_stmt_get_result($stmt_cart);
          $cart_item = mysqli_fetch_assoc($result_cart);
          mysqli_stmt_close($stmt_cart);

          if ($cart_item) {
            if ($cart_item['payment_status'] == 1) {
              // User has already purchased the course
              echo '<span class="badge bg-success p-2">You have already purchased this course</span>';
            } else {
              // Course is in cart but not paid
              echo '<a href="cart.php" class="btn btn-primary">Proceed to Checkout</a>';
            }
          } elseif ($_SESSION['role_as'] != 1) {
            // Normal user who has not added the course to cart yet
            echo '<button class="btn btn-warning addToCartBtn" data-id="' . $course["id"] . '">Add to Cart</button>';
          }
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
      }
    } else {
      echo '<p>No courses available.</p>';
    }
    $stmt->close();
    ?>
  </div>

  <!-- Pagination -->
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo; Previous</span>
          </a>
        </li>
      <?php endif; ?>



      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>

      <?php if ($page < $total_pages): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
            <span aria-hidden="true">Next &raquo;</span>
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addCourseForm">
        <div class="modal-header">
          <h5 class="modal-title" id="addCourseModalLabel">Add New Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="text" id="addCourseName" name="name" class="form-control mb-3" placeholder="Course Name" required>
          <textarea id="addCourseDescription" name="description" class="form-control mb-3" placeholder="Description" required></textarea>
          <input type="number" id="addCoursePrice" name="price" class="form-control mb-3" placeholder="Price" required>
          <input type="text" id="addCourseImage" name="image" class="form-control mb-3" placeholder="Image URL" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add Course</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editCourseForm">
        <div class="modal-header">
          <h5 class="modal-title" id="editCourseModalLabel">Edit Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editCourseId" name="id">
          <input type="text" id="editCourseName" name="name" class="form-control mb-3" placeholder="Course Name" required>
          <textarea id="editCourseDescription" name="description" class="form-control mb-3" placeholder="Description" required></textarea>
          <input type="number" id="editCoursePrice" name="price" class="form-control mb-3" placeholder="Price" step="0.01" required>
          <label for="editCourseImage">Image URL</label>
          <input type="text" id="editCourseImage" name="image" class="form-control mb-3" placeholder="Image URL">
          <label for="editCourseFile">Or Upload Image</label>
          <input type="file" id="editCourseFile" name="image_file" class="form-control mb-3" accept="image/*">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Course Modal -->
<div class="modal fade" id="deleteCourseModal" tabindex="-1" aria-labelledby="deleteCourseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="deleteCourseForm" method="post" action="delete_course.php">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteCourseModalLabel" value="deleteCourseId">Delete Course</h5>
        </div>
        <div class="modal-body">
          <input type="hidden" id="deleteCourseId" name="course_id">
          <p>Are you sure you want to delete this course? </p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Delete Course</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancle</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- jQuery and Bootstrap Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>

<!-- Custom Scripts -->
<script>
  $(document).ready(function() {
    // Populate Edit Modal with Selected Course Data
    $('.editCourseBtn').on('click', function() {
      let id = $(this).data('id');
      let name = $(this).data('name');
      let description = $(this).data('description');
      let price = $(this).data('price');
      let image = $(this).data('image');

      // Populate form fields
      $('#editCourseId').val(id);
      $('#editCourseName').val(name);
      $('#editCourseDescription').val(description);
      $('#editCoursePrice').val(price);
      $('#editCourseImage').val(image);
    });

    // Handle Edit Course Form Submission
    $('#editCourseForm').on('submit', function(e) {
      e.preventDefault();
      submitForm($(this), 'edit_course.php', 'Course updated successfully!');
    });

    // Populate Delete Modal with Selected Course Data
    $('.deleteCourseBtn').on('click', function() {
      let id = $(this).data('id');
      $('#deleteCourseId').val(id);
      $('#deleteCourseModalLabel').text('Delete Course ID: ' + id);
    });



    // Handle Add Course Form Submission
    $('#addCourseForm').on('submit', function(e) {
      e.preventDefault();
      submitForm($(this), 'add_course.php', 'Course added successfully!');
    });

    // Handle Add to Cart Button
    $('.addToCartBtn').on('click', function() {
      let course_id = $(this).data('id');
      $.ajax({
        url: 'cart.php',
        type: 'POST',
        data: {
          course_id: course_id
        },
        success: function(response) {
          alert('Course added to cart successfully!');
        },
        error: function(xhr, status, error) {
          alert('Error adding course to cart: ' + error);
        }
      });
    });
  });

  /**
   * Helper function to handle form submissions via AJAX.
   * @param {jQuery} form - The form element.
   * @param {string} url - The URL to send the request to.
   * @param {string} successMessage - The message to display on success.
   */
  function submitForm(form, url, successMessage) {
    console.log(form.serialize());
    $.ajax({
      url: url,
      type: 'POST',
      data: form.serialize(),
      success: function(response) {
        alert(response || successMessage);
        location.reload();
      },
      error: function(xhr, status, error) {
        alert('Error: ' + error);
      }
    });
  }
</script>

<!-- Footer -->
<?php include 'footer.php'; ?>

<!-- Add to cartlogic -->
<?php
if (isset($_GET['course_id'])) {
  $course_id = $_GET['course_id'];
  addToCart($course_id);
}
function addToCart($course_id)
{
  $cart = [];
  if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
  }
  $cart[] = $course_id;
  $_SESSION['cart'] = $cart;
  echo 'Course added to cart successfully!';
}
?>
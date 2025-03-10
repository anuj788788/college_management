<?php
include 'dbcon.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    // 
    // $course_id = $_POST['course_id'];

    $query = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $query->bind_param("i", $course_id);

    if ($query->execute()) {
      echo "Course deleted successfully.";
      // Redirect to the courses page after deletion
      header('Location: courses.php');
    } else {
      echo "Error deleting course.";
    }
  } else {
    echo "Course not found.";
  }

  $conn->close();
}

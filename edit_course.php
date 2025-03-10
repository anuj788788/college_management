<?php
include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $image = $_POST['image'];

  $query = $conn->prepare("UPDATE courses SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
  $query->bind_param("ssdsi", $name, $description, $price, $image, $id);

  if ($query->execute()) {
    echo "Course updated successfully.";
  } else {
    echo "Error updating course.";
  }
}

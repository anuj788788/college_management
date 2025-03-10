<?php
include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $image = $_POST['image'];

  $query = $conn->prepare("INSERT INTO courses (name, description, price, image) VALUES (?, ?, ?, ?)");
  $query->bind_param("ssds", $name, $description, $price, $image);

  if ($query->execute()) {
    echo "New course added successfully.";
  } else {
    echo "Error adding course.";
  }
}

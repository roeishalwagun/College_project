<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

if (isset($_POST['add_category'])) {
  $category_name = $_POST['category_name'];
  if (!empty($category_name)) {
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $category_name);

    if ($stmt->execute()) {
      echo "✅ Category added successfully.";
    } else {
      echo "❌ Error: " . $stmt->error;
    }
  } else {
    echo "❌ Please enter a category name.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Category</title>
</head>
<body>
<h2>Add New Category</h2>
<form method="POST">
  <input name="category_name" placeholder="Category Name" required><br><br>
  <button name="add_category">Add Category</button>
</form>
</body>
</html>

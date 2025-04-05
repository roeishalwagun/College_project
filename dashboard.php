<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard - Jewelry Shop</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Sidebar / Hamburger Menu -->
<div  class="sidebar" id="sidebar">
  <button style="margin-left: 50px; background-color:black; margin-top:-5px; margin-left:180px" class="closebtn" onclick="closeNav()">×</button>

  <a href="dashboard.php">Dashboard</a>
  <a href="add_item.php">Add New Jewelry</a>
  <a href="view_items.php">View Jewelry</a>
  <a href="logout.php" class="logout-btn">Logout</a>
</div>

<!-- Main content -->
<div id="main">
  <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
  
  <h2 style="margin-left: 100px; margin-top: -5px">Welcome, <?= $_SESSION['username'] ?> | <a href="logout.php">Logout</a></h2>
  
  <h3 style="margin-left: 100px; margin-top: -5px">Jewelry Items</h3>
  

  <table border="1" cellpadding="8" style="margin: auto; background: #f9f9f9;">
    <tr>
      <th>Name</th>
      <th>Category</th>
      <th>Price</th>
      <th>Description</th>
      <th>Actions</th>
    </tr>

    <?php
    $sql = "SELECT jewelry.id, jewelry.name, categories.name AS category, jewelry.price, jewelry.description
            FROM jewelry
            LEFT JOIN categories ON jewelry.category_id = categories.id";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
      echo "<tr>
        <td>{$row['name']}</td>
        <td>{$row['category']}</td>
        <td>\${$row['price']}</td>
        <td>{$row['description']}</td>
        <td>
          <a href='edit_item.php?id={$row['id']}'>✏️ Edit</a> |
          <a href='delete_item.php?id={$row['id']}' onclick=\"return confirm('Are you sure?')\">🗑️ Delete</a>
        </td>
      </tr>";
    }
    ?>
  </table>
</div>

<script>
  // Open the sidebar
  function openNav() {
    document.getElementById("sidebar").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
  }

  // Close the sidebar
  function closeNav() {
    document.getElementById("sidebar").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
  }
</script>

</body>
</html>

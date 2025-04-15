<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Handle form submission
if (isset($_POST['add'])) {
  $name = $_POST['name'];
  $category_id = $_POST['category_id'];
  $price = $_POST['price'];
  $desc = $_POST['description'];

  echo "❌ Error: " . $stmt->error;
$stmt = $conn->prepare("CALL AddJewelryItem(?, ?, ?, ?, ?)");
$stock = 10; // Default stock (required for procedure); change as needed
$stmt->bind_param("ssdii", $name, $desc, $price, $stock, $category_id);

if ($stmt->execute()) {
  echo "<div class='success-message'>✅ Item added using stored procedure. <a href='index.php'>Go to Dashboard</a></div>";
  exit();
} else {
  echo '<div class="error-message">❌ Error: ' . htmlspecialchars($stmt->error) . '</div>';
} 
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Jewelry Item</title>
  <link rel="stylesheet" href="style.css">
</head>
<style>
     /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  .maindiv{
/* border: 2px solid black; */
display: flex;  
flex-direction: column;
width:100%;
justify-content: center;
align-items:center;
} 

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
    color: #333;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    transition: background-color .3s;
  }
  
  /* Form Styling */
  form {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;
  }
  
  h2 {
    font-size: 24px;
    color: #343a40;
    margin-bottom: 20px;
    font-weight: bold;
    text-align: center;
  }
  
  /* Input Fields Styling */
  input, select, textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 16px;
    color: #555;
    transition: all 0.3s ease;
  }
  
  input:focus, select:focus, textarea:focus {
    border-color: #007bff;
    outline: none;
  }
  
  /* Button Styling */
  button {
    background-color: #28a745;
    color: white;
    font-size: 18px;
    padding: 12px 30px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }
  
  button:hover {
    background-color: #218838;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
  }
  
  /* Placeholder Styling */
  input::placeholder, textarea::placeholder {
    color: #888;
    font-style: italic;
  }
  
  /* Textarea Styling */
  textarea {
    height: 150px;
    resize: vertical;
  }
  
  /* Success/Error Message Styling */
  .success-message, .error-message {
    text-align: center;
    font-size: 18px;
    margin-top: 20px;
  }
  
  .success-message {
    color: #28a745;
  }
  
  .error-message {
    color: #dc3545;
  }
  
  /* Responsive Design */
  @media screen and (max-width: 768px) {
    form {
      padding: 20px;
      width: 90%;
    }
  
    h2 {
      font-size: 22px;
    }
  
    button {
      font-size: 16px;
    }
 
}
  
</style>
<body>
    <div class="maindiv">

<h2>Add Jewelry Item</h2>
<form method="POST">
  <input name="name" placeholder="Jewelry Name" required><br><br>

  <select name="category_id" required>
    <option value="">Select Category</option>
    <?php
    $cat_res = $conn->query("SELECT * FROM categories");
    while ($cat = $cat_res->fetch_assoc()) {
      echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
    }
    ?>
  </select><br><br>

  <input name="price" type="number" step="0.01" placeholder="Price" required><br><br>
  <textarea name="description" placeholder="Description"></textarea><br><br>
  <button name="add">Add Item</button>
</form>
</div>

</body>
</html>

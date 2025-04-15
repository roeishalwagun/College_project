<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
  echo "❌ Invalid item ID.";
  exit();
}

$stmt = $conn->prepare("SELECT j.*, c.name AS category FROM jewelry j
                        LEFT JOIN categories c ON j.category_id = c.id
                        WHERE j.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
  echo "❌ Item not found.";
  exit();
}

// Handle transaction
if (isset($_POST['buy'])) {
  $qty = $_POST['quantity'];
  $total = $qty * $item['price'];

  $insert = $conn->prepare("INSERT INTO transactions (jewelry_id, quantity, total_price) VALUES (?, ?, ?)");
  $insert->bind_param("iid", $id, $qty, $total);
  if ($insert->execute()) {
    echo "<p style='color:green;'>✅ Transaction recorded successfully!</p>";
  } else {
    echo "<p style='color:red;'>❌ Error: " . $insert->error . "</p>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Jewelry Item Details</title>
  <style>
    body { font-family: Arial; background: #f8f9fa; padding: 30px; }
    .item-box {
      background: #fff; padding: 25px; border-radius: 10px;
      box-shadow: 0 0 8px rgba(0,0,0,0.1); max-width: 500px; margin: auto;
    }
    h2 { margin-bottom: 20px; }
    p { margin-bottom: 10px; }
    form { margin-top: 20px; }
    input[type=number], button {
      padding: 10px; font-size: 16px; margin-top: 10px; width: 100%;
      border: 1px solid #ccc; border-radius: 5px;
    }
    button {
      background: #007bff; color: white; border: none;
      cursor: pointer; transition: 0.3s ease;
    }
    button:hover { background: #0056b3; }
    a { text-decoration: none; color: #007bff; display: inline-block; margin-top: 15px; }
  </style>
</head>
<body>

<div class="item-box">
  <h2><?= htmlspecialchars($item['name']) ?></h2>
  <p><strong>Category:</strong> <?= htmlspecialchars($item['category']) ?></p>
  <p><strong>Price:</strong> $<?= number_format($item['price'], 2) ?></p>
  <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($item['description'])) ?></p>

  <form method="POST">
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" min="1" required>
    <button name="buy">💳 Buy / Record Transaction</button>
  </form>

  <a href="view_jewelry.php">← Back to Jewelry List</a>
</div>

</body>
</html>



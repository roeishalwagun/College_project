<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$search = $_GET['search'] ?? '';

// Query with search
$stmt = $conn->prepare("SELECT j.id, j.name, j.price, j.description, c.name AS category
                        FROM jewelry j
                        LEFT JOIN categories c ON j.category_id = c.id
                        WHERE j.name LIKE ? OR c.name LIKE ?");
$like = "%$search%";
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>View Jewelry</title>
  <style>
    body { font-family: Arial; padding: 30px; background: #f0f0f0; }
    form { margin-bottom: 20px; text-align: center; }
    input[type=text] { padding: 10px; width: 300px; border-radius: 5px; border: 1px solid #ccc; }
    button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
    button:hover { background: #0056b3; }
    table { width: 90%; margin: auto; border-collapse: collapse; background: white; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ccc; }
    th { background: #007bff; color: white; }
    a.view-btn {
      padding: 6px 12px; background: #28a745; color: white;
      border-radius: 5px; text-decoration: none;
    }
    a.view-btn:hover { background: #218838; }
  </style>
</head>
<body>

<h2 style="text-align:center;">Search Jewelry Items</h2>

<form method="GET">
  <input type="text" name="search" placeholder="Search by name or category" value="<?= htmlspecialchars($search) ?>">
  <button type="submit">🔍 Search</button>
</form>

<table>
  <tr>
    <th>Name</th>
    <th>Category</th>
    <th>Price</th>
    <th>Description</th>
    <th>Actions</th>
  </tr>

  <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['category']) ?></td>
      <td>$<?= number_format($row['price'], 2) ?></td>
      <td><?= htmlspecialchars($row['description']) ?></td>
      <td><a class="view-btn" href="view_item.php?id=<?= $row['id'] ?>">💳 View / Buy</a></td>
    </tr>
  <?php endwhile; ?>
</table>

</body>
</html>

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

$stmt = $conn->prepare("DELETE FROM jewelry WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  header("Location: index.php");
  exit();
} else {
  echo "❌ Failed to delete item: " . $stmt->error;
}
?>

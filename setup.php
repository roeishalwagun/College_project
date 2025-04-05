<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "jewelry_shop";

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$conn->query("INSERT INTO categories (name) VALUES 
  ('Rings'), ('Necklaces'), ('Bracelets'), ('Earrings'), ('Watches')");


$sql = "
CREATE DATABASE IF NOT EXISTS jewelry_shop;
USE jewelry_shop;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS jewelry (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  category_id INT,
  price DECIMAL(10,2),
  description TEXT,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(10,2),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  jewelry_id INT,
  quantity INT,
  price DECIMAL(10,2),
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (jewelry_id) REFERENCES jewelry(id)
);
";

if ($conn->multi_query($sql)) {
  echo "✅ All tables created successfully.";
} else {
  echo "❌ Error: " . $conn->error;
}
?>

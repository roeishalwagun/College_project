<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "jewelry_shop";

// Establish the connection
$conn = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Start the session only if it hasn't already been started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
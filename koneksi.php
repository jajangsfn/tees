<?php
$conn = new mysqli("localhost", "root", "", "tees");

if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . $conn->error . PHP_EOL;
    exit;
}
?>
<?php
// Database connection
require_once '../secret.php';
    $connection = new mysqli('localhost', 'root', $pass, 'sapo');
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    ?>
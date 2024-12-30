<?php
// Database connection
    $connection = new mysqli('localhost', 'root', 'rabin', 'sapo');
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    
    ?>
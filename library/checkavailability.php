<?php

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "rabin";
    $database = "sapo";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch student data from the database using the student_id
    $sql = "SELECT * FROM student WHERE std_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id); // Binding the student_id to the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        $student = $result->fetch_assoc();
       
    } else {
        echo "No student found with the given ID.";
    }

    $stmt->close();
    $conn->close();

?>

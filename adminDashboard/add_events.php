<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "rabin";
$database = "sapo";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_description = $_POST['event_description'];
    
    $sql = "INSERT INTO event (event_name, event_date, event_description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $event_name, $event_date, $event_description);
    
    if ($stmt->execute()) {
        $event_id = $conn->insert_id; 
        
        if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
            $imageTmpName = $_FILES['event_image']['tmp_name'];
            $imageFileType = strtolower(pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION));
            
            $uploadDir = __DIR__ . '/../assets/images/eventsimage/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $newFileName = $event_id . '.' . $imageFileType;
            $uploadPath = $uploadDir . $newFileName;
            
            if (move_uploaded_file($imageTmpName, $uploadPath)) {
                $imagePath = '/assets/images/eventsimage/' . $newFileName;
                $updateSql = "UPDATE event SET event_image = ? WHERE event_id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("si", $imagePath, $event_id);
                
                if ($updateStmt->execute()) {
                    echo "<script>alert('Event and image added successfully!'); window.location.href='add_events.php';</script>";
                } else {
                    echo "Error updating image path: " . $updateStmt->error;
                }
                $updateStmt->close();
            } else {
                echo "Error moving uploaded file.";
            }
        } else {
            echo "<script>alert('Event added successfully without image!'); window.location.href='add_events.php';</script>";
        }
    } else {
        echo "Error adding event: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Event</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color:rgb(235, 241, 243);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 50rem;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
         
        }
        .form-logo {
    width: auto;
    height: 20vh;
    display: inline-block;
    border-radius: 8px;
   margin-bottom:0;

}
img{
    margin-left: 20rem;
}

        h1 {
            font-size: 2rem;
            color: #333;
            text-align: center;
            margin-bottom: 10px;
            margin-top:0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="date"],
        textarea,
        input[type="file"] {
            width: 97%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        textarea:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            font-size: 1rem;
            font-weight: 500;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        .btn-cancel {
            background-color: #f44336;
            color: white;
            margin-left: 10px;
        }

        .btn-cancel:hover {
            background-color: #e53935;
        }

        .form-actions {
            text-align: center;
            margin-top: 20px;
        }
        @media (max-width: 600px) {
        
        .form-logo {
        width: 60px;
        display :none;
    }
}
        @media (max-width: 360px) {
        
        .form-logo {
        width: 50px;
    }
}
    </style>
</head>
<body>
    <div class="container">
        <img src="../assets/images/final-logo.png" alt="logo" class="form-logo">
        <h1>Add New Event</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="event_name">Event Name</label>
                <input type="text" id="event_name" name="event_name" required>
            </div>

            <div class="form-group">
                <label for="event_date">Event Date</label>
                <input type="date" id="event_date" name="event_date" required>
            </div>

            <div class="form-group">
                <label for="event_description">Event Description</label>
                <textarea id="event_description" name="event_description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="event_image">Event Image</label>
                <input type="file" id="event_image" name="event_image" accept="image/*">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-submit">Add Event</button>
                <a href="admin_events.php" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>

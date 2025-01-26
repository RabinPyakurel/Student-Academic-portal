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
            font-family: "poppins";
            background-color:#1e1e2c;
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
        .header {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: auto;
    flex-direction: row;
    text-align: center;
}

.form-logo {
   
    margin-right: 10px; /* Adjust if needed */
    width: 8rem; /* Adjust size */
}

.form-logo img {
    width: 100%;
    max-width: 60px;
    height: auto;
}

h1 {
    text-align: center;
    flex-grow: 1;
    padding-right:5rem;
    font-size: 2rem;
            color: #1e1e2c;
}

        .form-group {
            margin-bottom: 15px;
            
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #1e1e2c;
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
            border-color: #1e1e2c;
            outline: none;
            
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
            background-color: #01B6A0;
            color: white;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        .btn-cancel {
            background-color:rgb(224, 60, 57);
            color: white;
            margin-left: 10px;
        }

        .btn-cancel:hover {
            background-color:rgb(255, 6, 1);
        }

        .form-actions {
            text-align: center;
            margin-top: 20px;
        }
        
        .form-logo img {
    width: 100%;
    max-width: 80px; /* Adjust this as needed */
    height: auto;
}

@media (max-width: 600px) {
    .form-logo img {
        max-width: 60px;
    }
    h1{
        padding-right: 1rem;
    }
    label{
        margin-left:1rem;
    }
    input[type="text"],
        input[type="date"],
        textarea,
        input[type="file"] {
            width: 80%;
            margin-left: 1.2rem;
            
            
        }
}


@media (max-width: 400px) {
    .form-logo img {
        max-width: 100px;
    }
    .header{
        flex-direction: column;
    }
    .form-logo {
        margin-bottom: 0px;
    }

    h1 {
        margin-top: -2rem;
        text-align: center;
        padding-right:0rem;
    }
}

    </style>
</head>
<body>
<?php include './sidebar.htm' ?>
    <div class="container">
        <div class="header">
            <div class="form-logo">

                <img src="../assets/images/final-logo.png" alt="logo">
            </div>
            <h1>Add New Event</h1>
        </div>
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
                <label for="event_description">Event Form Link</label>
                <textarea id="event_description" name="event_description" rows="2" required></textarea>
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

    
    <script>
document.querySelector('form').addEventListener('submit', function(e) {
    // Event name validation
    var eventName = document.getElementById('event_name').value.trim();
    if (eventName === "") {
        alert("Event Name cannot be empty.");
        e.preventDefault();
        return;
    }

    // Event date validation
    var eventDate = document.getElementById('event_date').value;
    if (eventDate === "") {
        alert("Event Date cannot be empty.");
        e.preventDefault();
        return;
    }

    // Event description validation (should be a valid URL)
    var eventDescription = document.getElementById('event_description').value.trim();
    var urlRegex = /^(https?:\/\/[^\s$.?#].[^\s]*)$/;
    if (eventDescription === "") {
        alert("Event Description (Link) cannot be empty.");
        e.preventDefault();
        return;
    } else if (!urlRegex.test(eventDescription)) {
        alert("Please enter a valid URL for the Event Link.");
        e.preventDefault();
        return;
    }

    // Event image validation (optional, but if present, it should be an image)
    var eventImage = document.getElementById('event_image').files[0];
    if (eventImage) {
        var validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validImageTypes.includes(eventImage.type)) {
            alert("Please upload a valid image file (JPEG, PNG, or GIF).");
            e.preventDefault();
            return;
        }
        if (eventImage.size > 5 * 1024 * 1024) { // 5MB limit
            alert("File size exceeds 5MB. Please upload a smaller image.");
            e.preventDefault();
            return;
        }
    }
});
</script>

</body>
</html>

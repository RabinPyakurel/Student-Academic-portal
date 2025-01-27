<?php


include "../backend/db_connection.php"; 
// count

$sql_count = "SELECT COUNT(*) AS event_count FROM event";
$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute();
$row_count = $stmt_count->fetch(PDO::FETCH_ASSOC);
$event_count = $row_count['event_count'];


if (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']); 

    // Fetch event details from the database
    $sql = "SELECT * FROM event WHERE event_id = :event_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':event_id' => $event_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $event_name = $row['event_name'];
        $event_date = $row['event_date'];
        $event_description = $row['event_description'];
        $event_image = $row['event_image'];
    } else {
        echo "Event not found.";
        exit;
    }
} else {
    echo "Event ID is missing from the URL.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = trim($_POST['event_name']);
    $event_date = trim($_POST['event_date']);
    $event_description = trim($_POST['event_description']);
    $event_image = $row['event_image']; // Preserve existing image if not updated

    // Handle image upload if provided
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
            $event_image = '/assets/images/eventsimage/' . $newFileName;
        } else {
            echo "Error moving uploaded file.";
            exit;
        }
    } elseif (isset($_POST['remove_image']) && $_POST['remove_image'] == 1) {
        // Remove image if checkbox is checked
        $event_image = null;
    }

    // Update the event record
    $update_sql = "UPDATE event SET event_name = :event_name, event_date = :event_date, event_description = :event_description, event_image = :event_image WHERE event_id = :event_id";
    $stmt = $pdo->prepare($update_sql);

    if ($stmt->execute([':event_name' => $event_name, ':event_date' => $event_date, ':event_description' => $event_description, ':event_image' => $event_image, ':event_id' => $event_id])) {
        echo "<script>
            alert('Event updated successfully!');
            window.location.href = 'manage_events.php';
        </script>";
        exit;
    } else {
        echo "Error updating event information.";
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Event</title>
  <link rel="stylesheet" href="/assets/css/edit_st.css">
  <style>
  /* Event Image Section */
  #event_image_section {
    margin-bottom: 20px;
  }

  #event_image_section img {
    width: 100px;
    height: auto;
    margin-bottom: 10px;
  }

  #event_image_section a {
    color: rgb(126, 78, 223);
    text-decoration: none;
    font-size: 1rem;
    margin-right: 1rem;
  }

  #event_image_section input[type="file"] {}

  .date-input-container {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;

    /* Modern font */
  }

  .date-input-container label {
    font-size: 1rem;
    font-weight: bold;

  }

  .date-input-container input[type="date"] {
    padding: 10px 15px;
    font-size: 1rem;
    border: 1px solid #ccc;
    /* Light border */
    border-radius: 8px;
    /* Rounded corners */
    width: 100%;
    box-sizing: border-box;
    transition: all 0.3s ease;
    /* Smooth transitions */
  }


  .date-input-container input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    filter: invert(30%);
    /* Adjusts color of calendar icon */
  }

  .date-input-container input[type="date"]::placeholder {
    color: #aaa;
    /* Placeholder color */
  }


  @media (max-width: 768px) {

    #event_image_section .remove-image-checkbox {
      margin-top: 10px;
    }

    #event_image_section .remove-image-checkbox input {
      margin-right: 5px;
    }
  }
  </style>
</head>

<body>
  <div class="edit-container">
    <div class="header">
      <h1>Edit Event Information</h1>
      <?php if (isset($event_id)) { ?>
      <div class="std_id">Event ID: <?= $event_id ?></div>
      <?php } ?>
    </div>

    <div class="form-container">
      <a href="manage_events.php" class="close-btn">&times;</a>

      <form method="POST" action="edit_events.php?event_id=<?= $event_id ?>" enctype="multipart/form-data">
        <div class="date-input-container">
          <label for="event_date">Event Date:</label><br>
          <input type="date" id="event_date" name="event_date" value="<?= htmlspecialchars($event_date) ?>" required>
        </div>

        <label for="event_name">Event Name:</label>
        <input type="text" id="event_name" name="event_name" value="<?= htmlspecialchars($event_name) ?>" required><br>

        <label for="event_description">Event Link:</label>
        <input type="text" id="event_description" name="event_description"
          value="<?= htmlspecialchars($event_description) ?>" required><br>

        <label for="event_image">Event Image:</label><br><br>
        <div id="event_image_section">
          <?php if ($event_image): ?>
          <img id="eventImageDisplay" src="<?= htmlspecialchars($event_image) ?>" alt="Current Event Image"
            width="100"><br>
          <a href="javascript:void(0)" id="changeImageBtn">Change Image</a><br><br>
          <input type="file" id="event_image" name="event_image" style="display: none;">
          <label class="remove-image-checkbox" style="color: red;">
            <input type="checkbox" name="remove_image" value="1"> Remove Image
          </label>
          <br>
          <?php else: ?>
          <input type="file" id="event_image" name="event_image">
          <?php endif; ?>
        </div>

        <script>
        // JavaScript to trigger file input when clicking "Change Image"
        document.getElementById("changeImageBtn")?.addEventListener("click", function() {
          document.getElementById("event_image").click();
        });

        // JavaScript to preview the new image after selecting it
        document.getElementById("event_image")?.addEventListener("change", function(event) {
          const file = event.target.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
              // Update the displayed image source with the selected file
              document.getElementById("eventImageDisplay").src = e.target.result;
            };
            reader.readAsDataURL(file);
          }
        });
        </script>



        <input type="submit" value="Update Event">
      </form>



    </div>
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

    // Convert input date to a Date object
    var selectedDate = new Date(eventDate);

    // Get today's date and set time to 00:00:00 for accurate comparison
    var today = new Date();
    today.setHours(0, 0, 0, 0);

    // Check if the selected date is in the past
    if (selectedDate <= today) {
      alert("Please select a future date.");
      e.preventDefault();
      return;
    }

    // Event description validation
    var eventDescription = document.getElementById('event_description').value.trim();
    var urlRegex = /^(https?:\/\/[^\s$.?#].[^\s]*)$/;

    // Check if the field is empty
    if (eventDescription === "") {
      alert("Event Description cannot be empty.");
      e.preventDefault();
      return;
    }

    // If the description starts with "http" or "https", validate it as a URL
    if (eventDescription.startsWith("http") || eventDescription.startsWith("https")) {
      if (!urlRegex.test(eventDescription)) {
        alert("Please enter a valid URL for the Event Description (Link).");
        e.preventDefault();
        return;
      }
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
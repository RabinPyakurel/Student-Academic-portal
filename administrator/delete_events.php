<?php
include "../backend/db_connection.php"; 


if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    if (is_numeric($event_id)) {
        
        $sql = "DELETE FROM event WHERE event_id = :event_id";
        
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":event_id",$event_id); // 
            if ($stmt->execute()) {
                echo "<script>alert('Event deleted successfully!'); window.location.href='manage_events.php';</script>";
                exit;
            } else {
              echo "<script>alert('Error deleting Event!'); window.location.href='manage_events.php';</script>";
            }
           
        } else {
            echo "Error preparing the query.";
        }
    } else {
        echo "Invalid Event ID.";
    }
} else {
    echo "Event ID is missing from the URL.";
}
?>
<script>
  document.querySelectorAll('.delete-link').forEach(link => {
  link.addEventListener('click', function (e) {
    if (!confirm('Are you sure you want to delete this event?')) {
      e.preventDefault();
    }
  });
});

</script>

<?php
include "./db_connection.php";

// Handle filtering
$filter = $_GET['filter'] ?? 'all'; // Default to 'all' if no filter is selected
$search = $_GET['search'] ?? ''; // Get search input

$query = "SELECT * FROM event WHERE 1";
// if ($filter !== 'all') {
//     $query .= " AND event_date = '$filter'";
// }
if (!empty($search)) {
    $query .= " AND event_name LIKE '%$search%'";
}

$query .= " ORDER BY event_date ASC";

$result = $connection->query($query);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event List</title>
    <link rel="stylesheet" href="/assets/css/student_list.css">
    <style>
        main{
            color: black;
        }
        
    </style>
</head>
<body>
    <?php include './sidebar.htm' ?>
    <main>
    <h1>Event List</h1>
    <div class="filter-container">
        <form method="GET" action="">
          
            <input type="text" name="search" placeholder="Search by event name..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Event Date</th>
                    <th>Event Name</th>
                    <th>Event Link</th>
                    <th>Event Poster</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['event_date'] ?></td>
                            <td><?= $row['event_name'] ?></td>
                            <td><?= $row['event_description'] ?></td>
                            <td>
    <img src="<?= $row['event_image']; ?>" alt="Event Image" style="width:200px; height:auto;">
</td>

                            <td>
                            
                            <a class="edit-link" href="./edit_events.php?event_id=<?= $row['event_id'] ?>">Edit</a>
    
    <hr>
                            <a class="delete-link" 
                              href="./delete_events.php?event_id=<?= $row['event_id'] ?>" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
    
    
                        </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No Event Record found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </main>
</body>
</html>

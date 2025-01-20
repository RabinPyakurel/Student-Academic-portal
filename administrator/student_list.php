<?php
include "./db_connection.php";

// Handle filtering
$filter = $_GET['filter'] ?? 'all'; // Default to 'all' if no filter is selected
$search = $_GET['search'] ?? ''; // Get search input

$query = "SELECT * FROM student WHERE 1";
if ($filter !== 'all') {
    $query .= " AND semester = '$filter'";
}
if (!empty($search)) {
    $query .= " AND name LIKE '%$search%'";
}

$query .= " ORDER BY name ASC";

$result = $connection->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
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
    <h1>Student List</h1>
    <div class="filter-container">
        <form method="GET" action="">
            <select name="filter">
                <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All Semesters</option>
                <option value="1st" <?= $filter === '1st' ? 'selected' : '' ?>>1st Semester</option>
                <option value="2nd" <?= $filter === '2nd' ? 'selected' : '' ?>>2nd Semester</option>
                <option value="3rd" <?= $filter === '3rd' ? 'selected' : '' ?>>3rd Semester</option>
            </select>
            <input type="text" name="search" placeholder="Search by name..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Semester</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['std_id'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['semester'] ?></td>
                            
                            <td>
                            
                            <a class="edit-link" href="./edit_students.php?id=<?= $row['std_id'] ?>">Edit</a>
    
    
                            <a class="delete-link" 
                              href="./delete_students.php?id=<?= $row['std_id'] ?>" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
    
    
                        </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </main>
</body>
</html>

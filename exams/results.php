<?php
session_start();
if(!isset($_SESSION['user_id'])){
    include '../not-found.htm';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="../assets/css/result.css">
</head>
<body>
    <?php include '../layout/nav.htm' ?>
    <main>
        
    </main>
    <?php include '../layout/footer.htm' ?>
</body>
</html>
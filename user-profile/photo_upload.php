<?php
header('Content-Type: application/json');

$baseDir = __DIR__ . '/../assets/images';
$uploadDir = $baseDir . '/uploads/';
$publicDir = '/assets/images/uploads/';

include '../backend/db_connection.php';

session_start();
if(!isset($_SESSION['user_id'])){
    include '../not-found.htm';
    exit();
}
$user_id = $_SESSION['user_id'];

if (isset($_FILES['photo'])) {
    $img = $_FILES['photo'];

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($img['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed.']);
        exit;
    }

    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            echo json_encode(['success' => false, 'message' => 'Failed to create upload directory']);
            exit;
        }
    }

    $fileName = time() . '_' . basename($img['name']);
    $fileName = preg_replace('/[^a-zA-Z0-9\._-]/', '_', $fileName);

    $targetPath = $uploadDir . $fileName;
    $publicPath = $publicDir . $fileName;

    if (move_uploaded_file($img['tmp_name'], $targetPath)) {
        try {
            $query = "UPDATE student SET photo_url = :url WHERE std_id = :user_id;";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':url' => $publicPath,
                ':user_id' => $user_id
            ]);
            echo json_encode([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'photo_url' => $publicPath
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Database update failed',
                'error' => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No image file provided']);
}
?>

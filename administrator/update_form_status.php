<?php
include "../backend/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formId = $_POST['form_id'];
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("UPDATE examform SET status = ? WHERE form_id = ?");
        $stmt->execute([$status, $formId]);
        header("Location: examAdmin.php?message=Form status updated successfully.");
        exit;
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

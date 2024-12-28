<?php
header('Content-Type: application/json');

include '../backend/db_connection.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    include '../not-found.htm';
    exit();
}

$user_id = $_SESSION['user_id'];
$updated = false;

try {
    // Check if personal email or phone number is set
    if (isset($_POST['personalEmail']) || isset($_POST['phoneNumber'])) {
        $personalEmail = $_POST['personalEmail'] ?? null;
        $contactNum = $_POST['phoneNumber'] ?? null;

        $query = "UPDATE student 
                  SET personal_email = :email, contact_number = :contact
                  WHERE std_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':email' => $personalEmail, 
            ':contact' => $contactNum, 
            ':user_id' => $user_id
        ]);

        if ($stmt->rowCount() > 0) {
            $updated = true; 
        }
    }

    // Check if emergency contact information is set
    if (isset($_POST['emgContactName']) || isset($_POST['emgContactRel']) || isset($_POST['emgContactNum']) || isset($_POST['emgGrdContact'])) {
        $emgContactName = $_POST['emgContactName'] ?? null;
        $emgContactRel = $_POST['emgContactRel'] ?? null;
        $emgContactNum = $_POST['emgContactNum'] ?? null;
        $emgGrdContact = $_POST['emgGrdContact'] ?? null;

        // Check if emergency contact exists
        $query = "SELECT COUNT(*) FROM emergency_contact WHERE std_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':user_id' => $user_id]);
        $exists = $stmt->fetchColumn();

        // Update or insert emergency contact information
        if ($exists) {
            $query = "UPDATE emergency_contact 
                      SET emergency_name = :name, 
                          relation = :relation, 
                          contact = :contact, 
                          guardian_contact = :g_contact
                      WHERE std_id = :user_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':name' => $emgContactName, 
                ':relation' => $emgContactRel, 
                ':contact' => $emgContactNum, 
                ':g_contact' => $emgGrdContact, 
                ':user_id' => $user_id
            ]);
        } else {
            $query = "INSERT INTO emergency_contact (std_id, emergency_name, relation, contact, guardian_contact) 
                      VALUES (:user_id, :name, :relation, :contact, :g_contact)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':user_id' => $user_id,
                ':name' => $emgContactName,
                ':relation' => $emgContactRel,
                ':contact' => $emgContactNum,
                ':g_contact' => $emgGrdContact
            ]);
        }

        // Check if any row was affected
        if ($stmt->rowCount() > 0) {
            $updated = true;
        }
    }

    // Return success or failure response
    if ($updated) {
        echo json_encode([
            'success' => true,
            'message' => 'Successfully updated personal contact and/or emergency contact information'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No fields were updated'
        ]);
    }

} catch (PDOException $e) {
    // Handle database errors
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while updating the data.',
        'error' => $e->getMessage()
    ]);
}
?>

<?php
header('Content-Type: application/json');

include '../backend/db_connection.php';

session_start();
if(!isset($_SESSION['user_id'])){
    include '../not-found.htm';
    exit();
}
$user_id = $_SESSION['user_id'];
$updated = false;

// Check if personalEmail or phoneNumber is set
if (isset($_POST['personalEmail']) || isset($_POST['phoneNumber'])) {
    $personalEmail = isset($_POST['personalEmail']) ? $_POST['personalEmail'] : null;
    $contactNum = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : null;

    $query = "UPDATE student 
              SET personal_email = :email, contact_number = :contact
              WHERE std_id = :user_id";

    $stmt = $pdo->prepare($query);
    $stmt->execute([':email' => $personalEmail, ':contact' => $contactNum, ':user_id' => $user_id]);


    if ($stmt->rowCount() > 0) {
        $updated = true; 
    }
}

if (isset($_POST['emgContactName']) || isset($_POST['emgContactRel']) || isset($_POST['emgContactNum']) || isset($_POST['emgGrdContact'])) {
    $emgContactName = isset($_POST['emgContactName']) ? $_POST['emgContactName'] : null;
    $emgContactRel = isset($_POST['emgContactRel']) ? $_POST['emgContactRel'] : null;
    $emgContactNum = isset($_POST['emgContactNum']) ? $_POST['emgContactNum'] : null;
    $emgGrdContact = isset($_POST['emgGrdContact']) ? $_POST['emgGrdContact'] : null;

    $query = "SELECT COUNT(*) FROM emergency_contact WHERE std_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':user_id' => $user_id]);
    $exists = $stmt->fetchColumn();

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

        if ($stmt->rowCount() > 0) {
            $updated = true;
        }
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

        if ($stmt->rowCount() > 0) {
            $updated = true;
        }
    }
}

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
?>

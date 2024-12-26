<?php
header('Content-Type: application/json');

try {
    // Corrected 'hos' to 'host'
    $pdo = new PDO("mysql:host=localhost;dbname=sapo", 'root', 'rabin');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed',
        'error' => $e->getMessage()
    ]);
    exit;
}

$user_id = 5639;
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

    // Check if any row was updated
    if ($stmt->rowCount() > 0) {
        $updated = true; // Set the updated flag to true
    }
}

// Check if emergency contact fields are set
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
        // Update if emergency contact exists
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
            $updated = true; // Set the updated flag to true
        }
    } else {
        // Insert if emergency contact doesn't exist
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

// Send the success message if any update was made
if ($updated) {
    echo json_encode([
        'success' => true,
        'message' => 'Successfully updated personal contact and/or emergency contact information'
    ]);
} else {
    // Send a message if no fields were updated
    echo json_encode([
        'success' => false,
        'message' => 'No fields were updated'
    ]);
}
?>

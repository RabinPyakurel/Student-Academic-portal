<?php
header('Content-Type: application/json');

$fieldName = $_POST['fieldName'] ?? '';
$fieldValue = $_POST['fieldValue'] ?? '';

$response = ['valid' => false, 'message' => 'Invalid input.'];

switch ($fieldName) {
    case 'p_email':
        if (filter_var($fieldValue, FILTER_VALIDATE_EMAIL)) {
            $response['valid'] = true;
            $response['message'] = 'Valid email address.';
        } else {
            $response['message'] = 'Please enter a valid email address.';
        }
        break;

    case 'p_contact':
    case 'emg_contact':
    case 'grd_contact':
        // Check if contact starts with 97 or 98 and is 10 digits long
        if (preg_match('/^(97|98)\d{8}$/', $fieldValue)) {
            $response['valid'] = true;
            $response['message'] = 'Valid phone number.';
        } else {
            $response['message'] = 'Phone number must start with 97 or 98 and be 10 digits.';
        }
        break;

    case 'emg_name':
        if (preg_match('/^[a-zA-Z\s]{2,50}$/', $fieldValue)) {
            $response['valid'] = true;
            $response['message'] = 'Valid name.';
        } else {
            $response['message'] = 'Name should be 2-50 characters long and contain only letters and spaces.';
        }
        break;

    case 'emg_rel':
        if (preg_match('/^[a-zA-Z\s]{2,50}$/', $fieldValue)) {
            $response['valid'] = true;
            $response['message'] = 'Valid relationship.';
        } else {
            $response['message'] = 'Relation should be 2-50 characters long.';
        }
        break;

    default:
        $response['message'] = 'Unknown field.';
}

echo json_encode($response);
?>

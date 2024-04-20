<?php 
// Form Validation
// Empty fields 
function validateRequiredFields($fields) {
    $emptyFields = [];
    foreach ($fields as $key => $value) {
        if (empty($value)) {
            $emptyFields[] = $key;
        }
    }
    return $emptyFields;
}

// Validate datetime local
function validateAndFormatDateTime($dateString) {
    try {
        $dateTime = new DateTime($dateString);
        // Date and time is valid
        return $dateTime->format('Y-m-d H:i:s'); // Format for MySQL datetime field
    } catch (Exception $e) {
        // Date and time is not valid
        // You can handle the validation error here, display error message, etc.
        return false; // Return false to indicate validation failure
    }
}






























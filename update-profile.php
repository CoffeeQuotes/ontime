<?php

require 'vendor/autoload.php';

use System\SessionManager;
use System\Connection;

// Initialize SessionManager
$session = new SessionManager();

// Check if a file was uploaded
if (isset($_FILES['profileImage'])) {
    $file = $_FILES['profileImage'];

    // Specify the directory to save the uploaded image
    $uploadDir = 'uploads/';

    // Generate a unique filename for the uploaded image
    $fileName = uniqid() . '_' . $file['name'];

    // Move the uploaded image to the specified directory
    move_uploaded_file($file['tmp_name'], $uploadDir . $fileName);
}

// Initialize Connection
$pdo = Connection::getInstance();
extract($_POST);
if (!isset($fileName)) {
    // If no new file was uploaded, fetch the existing profile picture
    $sql = 'SELECT picture FROM profiles WHERE user_id=:user_id;';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $session->get('logged_user')['id']);
    $statement->execute();
    $profile = $statement->fetch(PDO::FETCH_ASSOC);
    $profilePicture = $profile['picture'];
    $fileName = $profilePicture;
}

// Update profile information
$sql = 'UPDATE profiles SET picture=:picture, firstname=:firstname, middlename=:middlename, lastname=:lastname, designation=:designation WHERE user_id=:user_id;';
$statement = $pdo->prepare($sql);
$statement->bindValue(':picture', $fileName);
$statement->bindValue(':user_id', $session->get('logged_user')['id']);
$statement->bindValue(':firstname', $firstname);
$statement->bindValue(':middlename', $middlename);
$statement->bindValue(':lastname', $lastname);
$statement->bindValue(':designation', $designation);
$statement->execute();

// Construct the path to the uploaded image

// Prepare JSON response
$response = [
    'message' => 'Profile picture uploaded successfully.'
];

// Send JSON response
echo json_encode($response);
?>

<?php
require '../vendor/autoload.php';
use System\Connection;

require '../functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = Connection::getInstance();
    // Retrieve the comment text from the POST request
    $user_id = $_POST['user_id'] ?? '';
    $role_id = $_POST['role_id'] ?? '';
    if ($role_id == '' && $user_id == '') {
        throw new Error("Role ID or User ID not found!");
    }
    // Validate the comment text (e.g., check if it's not empty)
    if ($role_id && $user_id) {
        // Check if the combination of role_id and user_id already exists in the table
        $sql = "SELECT COUNT(*) AS count FROM user_roles WHERE role_id = :role_id AND user_id = :user_id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':role_id', $role_id);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $count = $statement->fetchColumn();
        if ($count == 0) {
            $sql = "INSERT INTO user_roles (role_id, user_id) VALUES (:role_id, :user_id)";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':role_id', $role_id);
            $statement->bindValue(':user_id', $user_id);
            $statement->execute();
            $success = 'Success';
            $data = array(
                'success' => $success
            );
            echo json_encode($data);
        } else {
            // Combination already exists, return an error message
            $data = array(
                'failed' => 'Error: Duplicate entry'
            );
            echo json_encode($data);
        }
    } else {
        // Comment text is empty, handle the error (e.g., return an error message)
        echo 'Error: Data cannot be empty';
    }
} else {
    // Request method is not POST, handle the error (e.g., return an error message)
    echo 'Error: Only POST requests are allowed';
}



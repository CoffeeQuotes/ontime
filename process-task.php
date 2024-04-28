<?php
require 'vendor/autoload.php';
use System\SessionManager;
use System\Connection;

$session = new SessionManager();
require 'functions.php';
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require 'constants.php');
}

$task = $_POST;

$emptyFields = validateRequiredFields($task);
$deadlineString = $task['deadline'];
$formattedDateTime = validateAndFormatDateTime($deadlineString);

$errors = [];
if (!empty($emptyFields)) {
    foreach ($emptyFields as $field) {
        // Construct the error message for each empty field
        $errorMessage = ucfirst($field) . " is required.";
        // Store the error message in the errors array with the field as key
        $errors[$field][] = $errorMessage;
    }
}

if ($formattedDateTime === false) {
    $errors['deadline'][] = "Invalid deadline format";
}

// authorize the user 
if ($session->get('logged_user')) {
    if ($task['user_id'] != $session->get('logged_user')['id']) {
        $errors['failed'] = "Unauthorized to perfom this actions";
    }
}

if (count($errors) != 0) {
    // $_SESSION['errors'] = $errors;
    $session->set('errors', $errors);
    header("Location: " . CONSTANTS['site_url'] . "create-task.php");
    exit; // Make sure to exit after the redirect
}
// $pdo = require 'Connection.php';
$pdo = Connection::getInstance();
$sql = 'INSERT INTO tasks (user_id, project_id, title, description, deadline, priority) VALUES (:user_id, :project_id,:title, :description, :deadline, :priority)';

$statement = $pdo->prepare($sql);
$statement->bindValue(':user_id', $task['user_id']);
$statement->bindValue(':project_id', $task['project_id']);
$statement->bindValue(':title', $task['title']);
$statement->bindValue(':description', $task['description']);
$statement->bindValue(':deadline', $formattedDateTime);
$statement->bindValue(':priority', $task['priority']);
$statement->execute();
$task_id = $pdo->lastInsertId();

// $_SESSION['success'] = "Successfully created task.";
$session->set('success', 'Successfully created task');
$session->set('task_id', $task_id);
// Redirect to task-assets page 
header("Location: " . CONSTANTS['site_url'] . "upload-task-assets.php");
// Redirect to success page or any other appropriate page
exit; // Make sure to exit after the redirect
?>
<?php
require 'vendor/autoload.php';
use  System\Connection;
use System\SessionManager;
require 'functions.php';
$session = new SessionManager();
$user_id = $session->get('logged_user')['id'];
// Perform any necessary setup (e.g., database connection)

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = Connection::getInstance();
    // Retrieve the comment text from the POST request
    $commentText = $_POST['comment_text'];
    $task_id = $_POST['task_id'];
    $parent_comment_id = $_POST['comment_id'];
    if($parent_comment_id == 0) {
        $parent_comment_id = NULL;
    }
    // Validate the comment text (e.g., check if it's not empty)
    if (!empty($commentText)) {
        // Assume $pdo is your database connection PDO object
        // Prepare and execute the INSERT query to add the comment to the database
        $sql = "INSERT INTO task_comments (task_id, user_id, comment_text, parent_comment_id) VALUES (:task_id, :user_id, :comment_text, :parent_comment_id)";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':task_id', $task_id);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':comment_text', $commentText);
        $statement->bindValue(':parent_comment_id', $parent_comment_id);
        $result = $statement->execute();
        $success = '<label class="text-success">Comment Added</label>';
        $data = array(
            'success'  => $success
        );
        echo json_encode($data);
    } else {
        // Comment text is empty, handle the error (e.g., return an error message)
        echo 'Error: Comment text cannot be empty';
    }
} else {
    // Request method is not POST, handle the error (e.g., return an error message)
    echo 'Error: Only POST requests are allowed';
}



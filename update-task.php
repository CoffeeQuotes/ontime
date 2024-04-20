<?php

require 'vendor/autoload.php';

use System\SessionManager;
use System\Connection;

// Initialize SessionManager
$session = new SessionManager();
// Define constants if not defined
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require 'constants.php');
}

if(isset($_POST['task_id'])) 
{
    extract($_POST); 
    $pdo = Connection::getInstance();
    
    // Verify if users owns the task 
    $sql = 'SELECT * FROM tasks WHERE id=:task_id;';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':task_id', $task_id);
    $statement->execute();
    $task = $statement->fetch(PDO::FETCH_ASSOC);
    // print_r($task);
    // die;
    if($session->get('logged_user')['id'] == $task['user_id']) {
        // print_r($task_id);
        // print_r($status);
        // die;
        $sql = 'UPDATE tasks SET status=:status WHERE id=:task_id';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':status', $status);
        $statement->bindValue(':task_id', $task_id);
        $statement->execute();

        $session->set('success', "Task Updated Successfully");
        // Redirect to success page or any other appropriate page
        header("Location: ".CONSTANTS['site_url']."tasks.php");
    }  else {
        $session->set('failed', "You are not authorized to edit other's task status.");
        header("Location: ".CONSTANTS['site_url']."tasks.php");
    }    
}
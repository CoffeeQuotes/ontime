<?php
require 'vendor/autoload.php';
use System\Connection;
use System\SessionManager;

$session = new SessionManager();
require 'functions.php';
if (!defined('CONSTANTS')) {
define('CONSTANTS', require 'constants.php');
}
$task_id = isset($_GET['task_id']) ? intval($_GET['task_id']) : 0;
if($task_id === 0) {
    // Redirect to tasks
    $session->set('failed', "Error: Task not found!");
	header("Location: ". CONSTANTS['site_url'] ."tasks.php");
}
$pdo = Connection::getInstance();
$sql = 'SELECT tasks.*, task_assets.caption, task_assets.location, task_assets.description as asset_description, task_assets.type, task_assets.size 
FROM tasks 
LEFT JOIN task_assets ON tasks.id = task_assets.task_id 
WHERE tasks.id = :task_id;';
$statement = $pdo->prepare($sql);
$statement->bindValue(':task_id', $task_id, PDO::PARAM_INT);
$statement->execute();
$task = $statement->fetchAll(PDO::FETCH_ASSOC);
if(!count($task) > 0) {
    $session->set('failed', "Error: Task not found.");
	header("Location: ". CONSTANTS['site_url'] ."tasks.php");
}
?>
<?php include 'partials/header.php'; ?>
    <div class="center-50">
        <h2><?= $task[0]['title'] ?></h2>
        <p><?= $task[0]['description'] ?></p> 
        <p><small> <b>Task deadline : </b><i> <?= date("F j, Y, g:i a", strtotime($task[0]['deadline']));  ?></i></small> </p>   
    </div>
<?php include 'partials/footer.php'; ?>
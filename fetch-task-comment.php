<?php 
require 'vendor/autoload.php';
use System\Connection;

$pdo =  Connection::getInstance();
require 'functions.php';
$task_id = 11;
$sql = 'SELECT task_comments.*, users.username, profiles.firstname , profiles.lastname , profiles.picture , profiles.designation  FROM task_comments LEFT JOIN users ON task_comments.user_id = users.id LEFT JOIN profiles ON users.id = profiles.user_id WHERE task_id=:task_id AND parent_comment_id IS NULL ORDER BY updated_at DESC';

$statement = $pdo->prepare($sql);
$statement->bindValue(':task_id', $task_id, PDO::PARAM_INT);
$statement->execute();
$comments = $statement->fetchAll(PDO::FETCH_ASSOC);
// print_r($comments);
$output = '';

foreach($comments as $comment) {
    $output .= '
        <div class="parent_comment_box flex">
            <div style="padding-right: 10px;">
            <img style="border-radius:50%;" src="uploads/'.$comment['picture'].'" width="60" height="60">
            </div>
            <div class="flex flex-column">
            <div><b>'.$comment['firstname'].' '. $comment['lastname'] .'</b>&nbsp;<i class="secondary-text">'.makeDateTimeHumanFriendly($comment["created_at"]).'</i></div>
            <div>'.$comment['comment_text'].'</div>
            <div><a href="javascript:void()" class="reply secondary-text" id="'.$comment["id"].'">Reply</a></div>
            </div>
        </div>
    ';
    $output .= get_reply_comment($task_id, $pdo, $comment["id"]);

}
echo $output;
function get_reply_comment($task_id, $pdo, $parent_id = NULL, $marginleft = 0) {
    $output = ''; // Initialize output variable
    $sql = 'SELECT task_comments.*, users.username, profiles.firstname , profiles.lastname , profiles.picture , profiles.designation  
            FROM task_comments 
            LEFT JOIN users ON task_comments.user_id = users.id 
            LEFT JOIN profiles ON users.id = profiles.user_id   
            WHERE task_id = :task_id AND parent_comment_id = :parent_comment_id 
            ORDER BY updated_at DESC';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':task_id', $task_id, PDO::PARAM_INT);
    $statement->bindValue(':parent_comment_id', $parent_id);
    $statement->execute();
    $comments = $statement->fetchAll(PDO::FETCH_ASSOC);
    $count = $statement->rowCount();
    if($parent_id == NULL) {
        $marginleft = 0;
    } else {
        $marginleft = $marginleft + 12;
    }
    if($count > 0) {
        foreach($comments as $comment) {
            $output .= '
            <div class="child_comment_box flex" style="margin-left:'.$marginleft.'px">
                <div style="padding-right: 10px;">
                    <img style="border-radius:50%;" src="uploads/'.$comment['picture'].'" width="60" height="60">
                </div>
                <div class="flex flex-column">
                <div><b>'.$comment['firstname'].' '. $comment['lastname'] .'</b>&nbsp;<i class="secondary-text">'.makeDateTimeHumanFriendly($comment["created_at"]).'</i></div>
                <div>'.$comment["comment_text"].'</div>
                <div><a href="javascript:void(0)" type="button" class="reply secondary-text" id="'.$comment["id"].'">Reply</a></div>
                </div>
                '.get_reply_comment($task_id, $pdo, $comment["id"], $marginleft).'
            </div>';
        }
    }
    return $output;
}

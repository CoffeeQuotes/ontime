<?php
require '../vendor/autoload.php';
use System\Connection;

$pdo = Connection::getInstance();
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['role_id']) && $data['role_id'] != '') {
    $role_id = $data['role_id'];
    $sql = "SELECT user_id FROM user_roles WHERE role_id=:role_id;";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(":role_id", $role_id, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $user_ids = array_map(function ($item) {
        return $item["user_id"];
    }, $result);
    // echo "<pre>";
    // print_r($user_ids);
    $response = ['user_ids' => $user_ids];
    echo @json_encode($response);
}

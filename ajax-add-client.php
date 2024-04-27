<?php
require 'vendor/autoload.php';
use System\Connection;

$pdo = Connection::getInstance();
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['client_name']) && $data['client_name'] != '') {
    $client_name = $data['client_name'];
    $sql = "INSERT INTO clients (client_name) VALUES (:client_name);";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(":client_name", $client_name, PDO::PARAM_STR);
    $statement->execute();
    // Send json response two add option
    $sql = "SELECT * FROM clients WHERE status='active'";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $clients = $statement->fetchAll(PDO::FETCH_ASSOC);
    $response = ['clients' => $clients];
    echo @json_encode($response);
}

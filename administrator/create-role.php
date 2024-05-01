<?php
require '../vendor/autoload.php';
use System\Connection;

require '../functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = Connection::getInstance();
    $role_name = $_POST['role_name'] ?? '';
    $description = $_POST['description'] ?? '';
    if ($role_name == '' && $description == '') {
        throw new Error("Role name or Description not found!");
    }
    if (!$role_name == "" && !$description == "") {
        $sql = "INSERT INTO roles (role_name, description) VALUES (:role_name,:description);";
        $statement = $pdo->prepare($sql);
        $role_name = strtolower($role_name);
        $statement->bindParam(":role_name", $role_name, PDO::PARAM_STR);
        $statement->bindParam(":description", $description, PDO::PARAM_STR);
        $statement->execute();
        $success = 'Success';
        $data = array(
            'success' => $success
        );
        echo json_encode($data);
    } else {
        echo 'Error: Data cannot be empty';
    }
}
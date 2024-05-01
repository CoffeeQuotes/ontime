<?php
include '../vendor/autoload.php';
use System\Connection;

// Define constants if not defined
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require '../constants.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['team'])) {
        // Upload logo
        if (isset($_FILES['logo'])) {
            $file = $_FILES['logo'];

            // Specify the directory to save the uploaded image
            $uploadDir = '../uploads/';

            // Generate a unique filename for the uploaded image
            $fileName = uniqid() . '_' . $file['name'];

            // Move the uploaded image to the specified directory
            move_uploaded_file($file['tmp_name'], $uploadDir . $fileName);
        }

        $team_name = $_POST['team_name'] ?? '';
        $team_name = strtolower($team_name);
        $logo = $fileName ?? '';
        $color = $_POST['color'] ?? '';
        $description = $_POST['description'] ?? '';

        $pdo = Connection::getInstance();
        $sql = "INSERT INTO teams (team_name, logo, color, description) VALUES (:team_name, :logo, :color, :description);";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':team_name', $team_name);
        $statement->bindValue(':logo', $logo);
        $statement->bindValue(':color', $color);
        $statement->bindValue(':description', $description);
        $statement->execute();
        header("Location: " . CONSTANTS['admin_url'] . "team-member.php");
    }
}
// echo "<pre>";
// print_r($_FILES);
// echo "<br>";
// print_r($_POST);
// die;
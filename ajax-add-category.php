<?php
require 'vendor/autoload.php';
use System\Connection;

$pdo = Connection::getInstance();
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['category_name']) && $data['category_name'] != '') {
    $category_name = $data['category_name'];
    $category_slug = generateUniqueSlug($category_name, $pdo);
    $sql = "INSERT INTO categories (category_name, category_slug) VALUES (:category_name, :category_slug);";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(":category_name", $category_name, PDO::PARAM_STR);
    $statement->bindValue(":category_slug", $category_slug, PDO::PARAM_STR);
    $statement->execute();
    // Send json response two add option
    $sql = "SELECT * FROM categories WHERE status='active'";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $categories = $statement->fetchAll(PDO::FETCH_ASSOC);
    $response = ['categories' => $categories];
    echo @json_encode($response);
}

function generateUniqueSlug($title, $pdo)
{
    // Lowercase and replace non-alphanumeric characters with underscores
    $slug = preg_replace("/[^a-z0-9_]+/", "_", strtolower($title));

    // Append a number if a duplicate is found
    $i = 1;
    $sql = "SELECT * FROM categories WHERE category_slug = :category_slug";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue("category_slug", $slug);

    while ($stmt->execute() && $stmt->rowCount() > 0) {  // Use rowCount() for PDO
        // $stmt->close();
        $slug .= "_" . $i++;
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue("category_slug", $slug);
    }

    // $stmt->close();
    return $slug;
}


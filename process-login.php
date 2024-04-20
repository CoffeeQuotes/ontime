<?php 
require 'vendor/autoload.php';
use System\SessionManager;
use System\Connection;
$session = new SessionManager();

// Define constants if not defined
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require 'constants.php');
}

// Include necessary files
require 'functions.php';
$pdo = Connection::getInstance();
// $pdo = require 'Connection.php';

// Get login details from POST
$login_details = $_POST;

// Validate required fields
$emptyFields = validateRequiredFields($login_details);
$errors = [];
if (!empty($emptyFields)) {
    foreach ($emptyFields as $field) {
        $errors[$field][] = ucfirst($field) . " is required.";
    }
}

// Check if login field is set
if (!isset($login_details['login'])) {
    $errors['login'][] = 'Login field is required.';
} else {
    // Trim and sanitize the login input
    $login = trim($login_details['login']);

    // Check if the input is an email or username
    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        // Input is email 
        $sql = 'SELECT * FROM users WHERE email = :login AND status = "active"';
    } else {
        // Input is username
        $sql = 'SELECT * FROM users WHERE username = :login AND status = "active"';
    }

    // Prepare and execute the query
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':login', $login);
    $statement->execute();

    // Fetch the user data
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    // Check if user exists and password is correct
    if (!$user || !password_verify($login_details['password'], $user['password'])) {
            $errors['password'][] = 'Invalid username/email or password.';   
            if(isset($errors['password'][0])) {
                unset($errors['password'][1]);
            }
    }
}

// If errors, redirect with error messages
if (!empty($errors)) {
    $session->set('errors',$errors);
    header("Location: ". CONSTANTS['site_url'] ."login.php");
    exit; // Make sure to exit after the redirect
}

// Store user data in session and redirect tasks page.

 $logged_user = [
    'id' => $user['id'],
 	'username' => $user['username'],
 	'email' => $user['email'],
 	'phone' => $user['phone'],
 	'email_verified_at' => $user['email_verified_at'],
 	'phone_verified_at' => $user['phone_verified_at'],
 	'status' => $user['status'],
 	'created_at' => $user['created_at'],
 	'updated_at' => $user['updated_at'],
 ];

$session->set('logged_user', $logged_user);
$session->set('success', "Welcome back!");
// Redirect to success page or any other appropriate page
header("Location: ".CONSTANTS['site_url']."tasks.php");
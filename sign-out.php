<?php 
require 'vendor/autoload.php';
use System\SessionManager;
require 'constants.php';
if (!defined('CONSTANTS')) {
define('CONSTANTS', require 'constants.php');
}
$session = new SessionManager();
$session->delete("logged_user");
header("Location: " . CONSTANTS["site_url"] . "login.php");

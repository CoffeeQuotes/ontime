<?php 
namespace System;
use PDO;
class Connection 
{
	private static $pdo;

	private function __construct() {} // prevent instantiations 

	public static function getInstance()
	{
		if(!isset(self::$pdo)) {
			$config = require __DIR__ . '/../config.php';
            $host = $config['host'];
            $db = $config['db'];
            $user = $config['user'];
            $password = $config['password'];
			$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

			try {
				$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
				self::$pdo = new PDO($dsn, $user, $password, $options);
			} catch(PDOException $e) {
				die($e->getMessage());
			}
		}

		return self::$pdo;
	}
}
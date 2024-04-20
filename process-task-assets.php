<?php 
					require 'vendor/autoload.php';
use System\SessionManager;
use System\Connection;
$session = new SessionManager();
require 'functions.php';
if (!defined('CONSTANTS')) {
define('CONSTANTS', require 'constants.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Check if any files were uploaded
	$task_id = $_POST['task_id'];
	
	if(isset($_FILES['location'])) {
		// Loop through each uploaded file 
	for($i=0; $i < count($_FILES['location']['name']); $i++) {
			// Get file details 
			$fileName = $_FILES['location']['name'][$i];
			$fileTmpname = $_FILES['location']['tmp_name'][$i];
			$fileType = $_FILES['location']['type'][$i];
			$fileError = $_FILES['location']['error'][$i];
			$fileSize = $_FILES['location']['size'][$i];

			if($fileError===0) {
				// Specify the directory where you want to store uploaded files 
				$uploadDir = "uploads/";

				// Generate a unique file name to avoid overwriting existing files 
				$fileDestination = $uploadDir . uniqid() .  "_" . $fileName;

				// Move the uploaded file to the desired directory 
				if(move_uploaded_file($fileTmpname, $fileDestination)) {
					// File uploaded successfully 
					// Insert file details into your database, along with caption and other details 
					$caption = $_POST['caption'][$i]; // Get the corresponding caption 
					$description = $_POST['description'][$i]; // Get the corressponding description 
					$size = $fileSize;
					$type = $fileType;

					// Insert into database 
					$pdo = Connection::getInstance();
					$sql = "INSERT INTO task_assets (task_id, location, caption, description, type, size) VALUES (:task_id, :filename, :caption, :description, :type, :size)";
					
					$statement = $pdo->prepare($sql);
					$statement->bindValue(':task_id', $task_id);
					$statement->bindValue(':filename', $fileName);	
					$statement->bindValue(':caption', $caption);	
					$statement->bindValue(':description', $description);	
					$statement->bindValue(':size', $size);
					$statement->bindValue(':type', $type);
					$statement->execute();
					$session->set('success', 'Successfully uploaded task assets.');
					header("Location: ". CONSTANTS['site_url'] ."tasks.php");

				} else {
					// failed
					$session->set('failed', 'Error: Failed to move file to destination directory.');
					header("Location: ". CONSTANTS['site_url'] ."tasks.php");
				}
			} else {
				// File upload encountered an error
				$session->set('failed', "Error: File upload failed with error code $fileError.");
					header("Location: ". CONSTANTS['site_url'] ."tasks.php");
                // echo ;
			}
		}
	} else {
		 // No files were uploaded
		$session->set('failed', "Error: No files uploaded.");
		header("Location: ". CONSTANTS['site_url'] ."tasks.php");
        // echo ;
	}
} else {
	  // Form was not submitted via POST method
    echo "Error: Form not submitted.";
}
?>
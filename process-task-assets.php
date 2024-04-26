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
	$errors = array();
	//  Server side validation 
	// if there's error 
	// print_r($_POST);die;	
	if(isset($_FILES['location'])) {
		
		$captions  = $_POST['caption'];
		$locations = $_FILES['location'];
		$descriptions = $_POST['description'];
	
		$uniqueCaptions = array_unique($captions); 
	
		// Assuming $locations contains the file names of the uploaded images
		$locations = $_FILES['location']['name'];
	
		// Convert file names to lowercase for case-insensitive comparison
		$locationsLowercase = array_map('strtolower', $locations);
	
		// Check if any caption is empty
		foreach ($captions as $caption) {
			if (empty($caption)) {
				// Handle empty caption
				$errors['empty_captions'] = "Please provide a caption for all images.";
			}
		}
	
		// Check if any location is empty
		foreach ($locations as $location) {
			if (empty($location)) {
				// Handle empty location
				$errors['empty_location'] = "Please select a file for all captions.";
			}
		}
	
	
		if(count($uniqueCaptions) !== count($captions)) { 
			$errors['duplicate_captions'] = 'Duplicate captions detected. Please provide unique captions.';
			// $session->set('errors', $errors);
		}
	
		// Check if there are any duplicate file names
		if (count($locations) !== count(array_unique($locationsLowercase))) {
			// Handle duplicate images
			$errors['duplicate_images'] = 'Duplicate images detected. Please provide unique images.';
			// $session->set('errors', $errors);
		}
		if(count($errors) > 0) {
			$session->set('errors', $errors);
			$session->set('task_id', $task_id);
			header("Location: ". CONSTANTS['site_url'] ."upload-task-assets.php");
			exit;
		} 
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
				$fileName = uniqid() .  "_" . $fileName; 
				$fileDestination = $uploadDir . $fileName;

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
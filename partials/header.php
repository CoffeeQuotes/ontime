<!DOCTYPE html>
<html>
<head>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="style.css"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<!-- Font Awesome CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<!-- Cropper.js CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
	<!-- Cropper.js JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
</head>
<body>
	<header id="primary-header">
		<h1>Ontime</h1>
		<nav>
			<?php 
				if($session->get('logged_user')) {		
			?> 
			<a href="tasks.php">Tasks</a>
			<a href="profile.php">
    			<i class="fa fa-user"></i>
    			@<?= $session->get('logged_user')['username']; ?>
			</a>
			<?php
				} else {
				?>
				<a href="login.php">Login</a>	
				<?php
				}
			?>
		</nav>
	</header>
	<div id="wrapper">
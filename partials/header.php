<!DOCTYPE html>
<html>
<head>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="style.css"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<!-- Font Awesome CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<!-- Cropper.js CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
	<!-- Cropper.js JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.2/photoswipe.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe-dynamic-caption-plugin/1.2.7/photoswipe-dynamic-caption-plugin.min.css"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
	<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

</head>
<body>
	<header id="primary-header">
		<h2>Ontime</h2>
		<nav>
			<?php 
				if($session->get('logged_user')) {		
			?> 
			<a href="tasks.php">Tasks</a>
			<a href="profile.php">
    			<i class="fa fa-user"></i>
    			&nbsp;@<?= $session->get('logged_user')['username']; ?>
			</a>
			<a href="sign-out.php"><i class="fas fa-sign-out-alt"></i>&nbsp;Sign Out</a>
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
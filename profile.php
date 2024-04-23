<?php 
require 'vendor/autoload.php';
use System\SessionManager;
use System\Connection;

$session = new SessionManager();
// Define constants if not defined
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require 'constants.php');
}
if(!$session->get('logged_user')) {
	header("Location: ".CONSTANTS['site_url']."login.php");
}
if($session->get('profile_incomplete')) {
    header("Location: " . CONSTANTS['site_url'] . "complete-profile.php");
}
$pdo = Connection::getInstance();
$sql = 'SELECT * FROM profiles WHERE user_id=:user_id';
$user_id = $session->get('logged_user')['id'];
$statement = $pdo->prepare($sql);
$statement->bindValue(':user_id', $user_id);
$statement->execute();
$profile = $statement->fetch(PDO::FETCH_ASSOC);
?>
<?php include 'partials/header.php'; ?>
	<div id="profile-wrapper" class="center-50">
		<div class="profile-box">
			<div>
				<div class="image-container">
					<img id="profilePicture" src="<?= "uploads/".$profile['picture']; ?>" width="150" height="150">
					<div class="edit-icon" onclick="openModal()">
					<i class="fas fa-edit"></i>
					</div>
				</div>
			</div>
			<div>
				<h1 id="aboutHeading" style="display: flex;"><?= $profile['firstname']??''?> <?= $profile['lastname']??'' ?> <sup class="secondary-text edit-head-icon" onclick="openModal()"><i class="fas fa-edit"></i></sup></h1>
				<p><small>Designation:&nbsp;<?= $profile['designation']??'' ?></small></p>
			</div>
		</div>
		<div class="profile-box">
			<div>
				<p><small>Email</small></p>
			</div>
			<div>
				<p><small><?= $session->get('logged_user')['email']??'' ?></small></p>		
			</div>
		</div>
		<div class="profile-box">
			<div>
				<p><small>Phone</small></p>
			</div>
			<div>
				<p><small><?= $session->get('logged_user')['phone']??'' ?></small></p>		
			</div>
		</div>
	</div>
	<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div>
			<h2>Update Profile</h2>
			<form id="profileForm" action="update-profile.php" method="post" enctype="multipart/form-data">
				<div id="modalFormFlex">

					<div>
						<input type="file" id="inputImage" name="profileImage">
						<div id="cropperContainer"></div>
					</div>
					<div class="rightSideModal">	
					<label for="firstname">Firstname</label>	
					<input type="text" name="firstname" id="firstname" value="<?php echo $profile['firstname'] ?>"/>
						<label for="middlename">Middlename</label>
						<input type="text" name="middlename" id="middlename" value="<?php echo $profile['middlename'] ?>"/>
						<label for="lastname">Lastname</label>
						<input type="text" name="lastname" id="lastname" value="<?php echo $profile['lastname'] ?>"/>
						<label for="">Designation</label>
						<input type="text" name="designation" id="designation" value="<?php echo $profile['designation'] ?>"/> 
					</div>
				</div>
				<div>	
					<button type="button" id="cropButton" onclick="cropImage()" disabled>Crop Image</button>
					<input type="button" onclick="updateProfile()" value="Update Profile">
				</div>
			</form>
		</div>
    </div>
</div>
<script>
var cropper;

document.getElementById('inputImage').addEventListener('change', function (e) {
	document.getElementById("cropButton").disabled = false;

    var file = e.target.files[0];
    var reader = new FileReader();

    reader.onload = function (event) {
        var img = document.createElement('img');
        img.src = event.target.result;

        var cropperContainer = document.getElementById('cropperContainer');
        cropperContainer.innerHTML = '';
        cropperContainer.appendChild(img);

        cropper = new Cropper(img, {
            aspectRatio: 1 / 1, // Set aspect ratio as needed
            zoomable: false, // Enable zooming
            // Other options can be configured as needed
        });
    };

    reader.readAsDataURL(file);
});

function cropImage() {
    var croppedCanvas = cropper.getCroppedCanvas({
        width: 200, // Set desired width
        height: 200, // Set desired height
    });
	var croppedImageData = croppedCanvas.toDataURL('image/jpeg');

	// Create a new image element for the cropped image
	var croppedImage = new Image();
	croppedImage.src = croppedImageData;

	// Replace the original image with the cropped image
	var cropperContainer = document.getElementById('cropperContainer');
	cropperContainer.innerHTML = '';
	cropperContainer.appendChild(croppedImage);

	// Configure a new Cropper instance for the cropped image
	cropper = new Cropper(croppedImage, {
		aspectRatio: 1 / 1, // Set aspect ratio as needed
		zoomable: false, // Enable zooming
		// Other options can be configured as needed
	});
}

function updateProfile() {
    // Get other form data
    var firstname = document.getElementById('firstname').value;
    var middlename = document.getElementById('middlename').value;
    var lastname = document.getElementById('lastname').value;
    var designation = document.getElementById('designation').value;

    // Create a FormData object to send the form data
    var formData = new FormData();
    formData.append('firstname', firstname);
    formData.append('middlename', middlename);
    formData.append('lastname', lastname);
    formData.append('designation', designation);

    // Check if the cropper object is defined and not null
    if (cropper) {
        // Get the cropped canvas from the Cropper instance
        var croppedCanvas = cropper.getCroppedCanvas({
            width: 200, // Set desired width
            height: 200, // Set desired height
        });

        // Convert the cropped canvas to a Blob object
        croppedCanvas.toBlob(function (blob) {
            // Append the image data to the FormData object
            formData.append('profileImage', blob, 'profile-image.jpg');

            // Send the form data to the server using AJAX
            sendFormData(formData);
        }, 'image/jpeg');
    } else {
        // Send the form data to the server using AJAX
        sendFormData(formData);
    }
}

function sendFormData(formData) {
    // Send the form data to the server using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update-profile.php');
    xhr.onload = function () {
        if (xhr.status === 200) {
            var jsonData = JSON.parse(xhr.responseText);
            var profilePicture = document.getElementById('profilePicture');
            profilePicture.src = jsonData.path;
            // Handle successful response from server
            closeModal();
            location.reload();
            console.log('Profile updated successfully.');
        } else {
            // Handle error response from server
            closeModal();
            console.error('Error updating profile.');
        }
    };
    xhr.send(formData);
}

function openModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "block";
}

function closeModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
}
</script>
<?php include 'partials/footer.php'; ?>
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
if ($session->get("success")) {
    $success = $session->get("success");
    $session->delete("success");
}
if($session->get("profile_incomplete")) {
    $failed = "Please complete your profile.";
}
$user = $session->get('logged_user');   
$errors = [];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    $pdo = Connection::getInstance();

    // Check if a file was uploaded
    if (isset($_FILES['cropped_image'])) {
        $file = $_FILES['cropped_image'];

        // Specify the directory to save the uploaded image
        $uploadDir = 'uploads/';

        // Generate a unique filename for the uploaded image
        $fileName = uniqid() . '_' . $file['name'];

        // Move the uploaded image to the specified directory
        move_uploaded_file($file['tmp_name'], $uploadDir . $fileName);
    }

    // Insert profile data into the database
    $sql = 'INSERT INTO profiles (user_id, firstname, middlename, lastname, picture, designation) VALUES (:user_id, :firstname, :middlename, :lastname, :picture, :designation)';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $user['id']);
    $statement->bindValue(':firstname', $firstname);
    $statement->bindValue(':middlename', $middlename);
    $statement->bindValue(':lastname', $lastname);
    $statement->bindValue(':picture', $fileName);
    $statement->bindValue(':designation', $designation);
    $statement->execute();
    // If successful, return the location URL
    $session->delete('profile_incomplete');
    exit;
}
?>
<?php include 'partials/header.php' ?>
<p class="error-alert"><?php echo $failed??""; ?></p>
<form method="post" action="" class="task-form" enctype="multipart/form-data">
    <fieldset>
        <legend>Create Profile</legend>
        <div class="form-row">
            <label for="firstname_field">Enter first name</label>
            <input type="text" name="firstname" id="firstname_field" placeholder="Enter first name"/>
            <!-- Add error handling for first name here -->
        </div>
        <div class="form-row">
            <label for="middlename_field">Enter middle name</label>
            <input type="text" name="middlename" id="middlename_field" placeholder="Enter middle name"/>
            <!-- Add error handling for first name here -->
        </div>
        <div class="form-row">
            <label for="lastname_field">Enter last name</label>
            <input type="text" name="lastname" id="lastname_field" placeholder="Enter last name"/>
            <!-- Add error handling for first name here -->
        </div>
        <!-- Add fields for middlename, lastname, and designation -->

        <div class="form-row">
            <label for="picture_field">Upload Picture</label>
            <input type="file" name="picture" accept="image/jpeg" id="picture_field" />
            <!-- Add error handling for picture here -->
            <div id="image-preview" style="max-width: 600px; max-height: 600px;">&nbsp;&nbsp;</div>
        </div>
        <div class="form-row">
            <label for="designation_field">Enter Designation</label>
            <input type="text" name="designation" id="designation_field" placeholder="Enter last designation"/>
            <!-- Add error handling for first name here -->
        </div>

        <div class="form-row">
            <button class="button" type="submit">Update Profile</button>
        </div>
    </fieldset>
</form>
<script type="text/javascript">
var cropper; // Declare cropper variable    
var image = document.getElementById('picture_field');
var preview = document.getElementById('image-preview');

image.addEventListener('change', function () {
    var file = this.files[0];
    var reader = new FileReader();

    reader.onload = function (e) {
        var img = new Image();
        img.src = e.target.result;
        img.onload = function () {
            preview.innerHTML = '';
            preview.appendChild(img);

            // Initialize Cropper
            cropper = new Cropper(img, { 
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1,
                // zoomable: false, // Enable zooming
                minCropBoxWidth: 150,
                minCropBoxHeight: 150,
                ready: function () {
                    cropper.crop();
                },
            });
        };
    };
    reader.readAsDataURL(file);
});

document.querySelector('.task-form').addEventListener('submit', function (e) {
    e.preventDefault();

    // Get the cropped image data
    var canvas = cropper.getCroppedCanvas({
        width: 200, // Set desired width
        height: 200, // Set desired height
    });
    if (!canvas) {
        // Handle case when no crop has been made
        alert('Please crop the image before submitting.');
        return;
    }
    // Convert canvas to blob
    canvas.toBlob(function(blob) {
        // Create FormData object and append the blob
        var formData = new FormData();
        formData.append('cropped_image', blob, 'profile-image.jpg');

        // Append other form data
        var inputs = document.querySelectorAll('.task-form input:not(#picture_field)');
        inputs.forEach(function(input) {
            formData.append(input.name, input.value);
        });

        // Submit the form with AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '');
        xhr.onload = function() {
            if (xhr.status === 200) {
                window.location.href = "<?= CONSTANTS['site_url'].'tasks.php'?>"; // Redirect to the URL specified in the response
            } else {
                alert('Error uploading image. Please try again.');
            }
        };
        xhr.send(formData);
    }, 'image/jpeg'); // Adjust format as needed
});

</script>
<?php include 'partials/footer.php' ?>

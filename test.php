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
$user = $session->get('logged_user');   
$errors = [];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    $pdo = Connection::getInstance();

    // Check if a file was uploaded
    if (isset($_FILES['picture'])) {
        $file = $_FILES['picture'];

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
    header("Location: " . CONSTANTS["site_url"] . "tasks.php");
}
?>
<?php include 'partials/header.php' ?>
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
            <input type="file" name="picture" accept="image/*" id="picture_field" />
            <!-- Add error handling for picture here -->
            <div id="image-preview">&nbsp;&nbsp;</div>
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
        console.log('FileReader loaded:', e);
        var img = new Image();
        img.src = e.target.result;
        img.onload = function () {
            console.log('Image loaded');
            preview.innerHTML = '';
            preview.appendChild(img);

            // Initialize Cropper
            var cropper = new Cropper(img, { 
                aspectRatio: 1,
                viewMode: 2,
                minCropBoxWidth: 200,
                minCropBoxHeight: 200,
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
    var canvas = cropper.getCroppedCanvas();
    var croppedImageData = canvas.toDataURL();

    // Create a hidden input field to store the cropped image data
    var hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'cropped_image';
    hiddenInput.value = croppedImageData;

    // Append the hidden input field to the form
    this.appendChild(hiddenInput);

    // Submit the form
    this.submit();
});

</script>
<?php include 'partials/footer.php' ?>

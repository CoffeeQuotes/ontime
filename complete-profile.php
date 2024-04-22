<?php 
require 'vendor/autoload.php';
use System\SessionManager;
$session = new SessionManager();
// Define constants if not defined
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require 'constants.php');
}
if(!$session->get('logged_user')) {
	header("Location: ".CONSTANTS['site_url']."login.php");
}
$user = $session->get('logged_user');	
?>
<?php include 'partials/header.php' ?>
<form method="post" action="" class="task-form">
    <fieldset>
        <legend>Create Profile</legend>
        <div class="form-row">
            <input type="hidden" name="user_id" value="<?= $user['id']; ?>"/>
            <label for="firstname_field">Enter first name</label>
            <input type="text" name="firstname" id="firstname_field" placeholder="Enter first name"/>
            <?php 
                if(isset($errors['firstname'])) {
                    foreach($errors['firstname'] as $error) {
                        echo $error . "<br />";
                    }
                }
            ?>
        </div>
        <div class="form-row">
            <label for="middlename_field">Enter middle name</label>
            <input type="text" name="middlename" id="middlename_field" placeholder="Enter middlename name"/>
            <?php 
                if(isset($errors['middlename'])) {
                    foreach($errors['middlename'] as $error) {
                        echo $error . "<br />";
                    }
                }
            ?>
        </div>
        <div class="form-row">
            <label for="lastname_field">Enter last name</label>
            <input type="text" name="lastname" id="lastname_field" placeholder="Enter last name"/>
            <?php 
                if(isset($errors['lastname'])) {
                    foreach($errors['lastname'] as $error) {
                        echo $error . "<br />";
                    }
                }
            ?>
        </div>
        <div class="form-row">
            <label for="picture_field">Upload Picture</label>
            <input type="file" name="picture" accept="accept= image/*" />
            <?php 
                if(isset($errors['picture'])) {
                    foreach($errors['picture'] as $error) {
                        echo $error . "<br />";
                    }
                }
            ?>
        </div>
        <div class="form-row">
            <label for="designation_field">Designation</label>
        	<input type="text" name="designation" id="designation_field" placeholder="Enter designation"/>
            <?php 
                if(isset($errors['designation'])) {
                    foreach($errors['designation'] as $error) {
                        echo $error . "<br />";
                    }
                }
            ?>
        </div>
      
        <div class="form-row">
            <button class="button" type="submit">Update Profile</button>
        </div>
    </fieldset>
</form>
<?php include 'partials/footer.php' ?>
<?php 
// require 'SessionManager.php';
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
if($session->get('profile_incomplete')) {
    header("Location: " . CONSTANTS['site_url'] . "complete-profile.php");
}
if($session->get('errors')) {
	$errors = $session->get('errors');
	$session->delete('errors');
}

$user = $session->get('logged_user'); 
// print_r($user);

?>
<?php include 'partials/header.php' ?>
<p><?php echo $errors['failed']??''; ?></p>
<form method="post" action="process-task.php" class="task-form">
    <fieldset>
        <legend>Create a task</legend>
        <div class="form-row">
            <input type="hidden" name="user_id" value="<?= $user['id']; ?>"/>
            <label for="title_field">Title</label>
            <input type="text" name="title" id="title_field" placeholder="Enter title"/>
            <span class="error">
            <?php 
                if(isset($errors['title'])) {
                    foreach($errors['title'] as $error) {
                        echo $error . "<br />";
                    }
                }
            ?>
            </span>
        </div>
        <div class="form-row">
            <label for="description_field">Description</label>
            <textarea name="description" id="description_field"></textarea>
            <span class="error">
            <?php 
                if(isset($errors['description'])) {
                    foreach($errors['description'] as $error) {
                        echo $error . "<br />";
                    }
                }
            ?>
            </span>
        </div>
        <div class="form-row">
            <label for="deadline_field">Deadline</label>
        	<input type="text" name="deadline" id="deadline_field" placeholder="Select deadline"/>
            <span class="error">
            <?php 
                if(isset($errors['deadline'])) {
                    foreach($errors['deadline'] as $error) {
                        echo $error . "<br />";
                    }
                }
            ?>
            </span>
        </div>
        <div class="form-row">
            <label for="priority_field">Priority</label>
            <select name="priority" id="priority_field">
                <option value="very-low">Very Low</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="very-high">Very High</option>
            </select>
        </div>
        <div class="form-row">
            <input class="button" type="submit" value="Create Task"/>
        </div>
    </fieldset>
</form>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script type="text/javascript">
 flatpickr('#deadline_field', {
    enableTime: true, // Enable time selection
    dateFormat: 'Y-m-d H:i', // Date format
    time_24hr: true, // Use 24-hour time format
    minDate: 'today', // Set minimum date to today
    altInput: true, // Show the selected date and time in the input field
    altFormat: 'F j, Y H:i', // Format for displaying the selected date and time
    placeholder: 'Select deadline', // Placeholder text
});
var simplemde = new SimpleMDE({ element: document.getElementById("description_field") });

</script>
<?php include 'partials/footer.php' ?>

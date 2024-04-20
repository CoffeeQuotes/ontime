<?php 
require 'vendor/autoload.php';
use System\SessionManager;
$session = new SessionManager();
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require 'constants.php');
}
if($session->get('logged_user')) {
	header("Location: ".CONSTANTS['site_url']."tasks.php");
}
 if($session->get('errors'))  {
 	$errors = $session->get('errors');
 	$session->delete('errors');
 }
?>
<?php include 'partials/header.php'; ?>
<form method="post" action="process-login.php">
	<fieldset class="flex flex-column">
		<legend>Log into your account</legend>
		<label for="login_field">Username or Email </label>
		<input type="text" name="login" id="login_field"/>
		<?php 
			if(isset($errors['login'])) {
				foreach($errors['login'] as $error) {
					echo $error . "<br />";
				}
			}
		?>
		<label for="password_field"> Password </label>
		<input type="password" class="mb-10" name="password" id="password_field"/>
		<?php 
			if(isset($errors['password'])) {
				foreach($errors['password'] as $error) {
					echo $error . "<br />";
				}
			}
		?>
		<input type="submit" value="Sign in"/>
	</fieldset>
</form>

<?php include 'partials/footer.php'; ?>
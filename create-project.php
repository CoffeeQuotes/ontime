<?php
require 'vendor/autoload.php';
use System\Connection;
use System\SessionManager;

$pdo = Connection::getInstance();
$session = new SessionManager();

?>
<?php include 'partials/header.php'; ?>
<form action="" method="POST">
    <fieldset>
        <legend>Create a new project</legend>
        <div class="form-row">
            <label for="project_name">Project name</label>
            <input type="text" id="project_name" name="project_name" placeholder="Enter your project name" />

        </div>
        <div class="form-row">
            <label for="project_description">Project description</label>
            <textarea id="project_description" name="project_description"
                placeholder="Enter your project description"></textarea>
        </div>
        <div class="form-row">
            <label for="status">Status</label>
            <select name="status" placeholder="Enter your status">
                <option value="not-started">Not Started</option>
                <option value="in-progress">In Progress</option>
                <option value="stopped">Stopped</option>
                <option value="completed">Complete</option>
            </select>
        </div>
        <div class="form-row">
            <label for="project_manager_id">Project manager id</label>
            <input type="text" id="project_manager_id" name="project_manager_id"
                placeholder="Enter your project manager" />
        </div>
        <div class="form-row">
            <label for="priority">Priority</label>
            <input type="text" id="priority" name="priority" placeholder="Enter your priority" />
        </div>
        <div class="form-row">
            <label for="budget">Budget</label>
            <input type="text" id="budget" name="budget" placeholder="Enter your budget" />
        </div>
        <div class="form-row">
            <label for="client_id">Client</label>
            <input type="text" id="client_id" name="client_id" placeholder="Enter your client" />

        </div>
        <div class="form-row">
            <label for="category_id">Category</label>
            <input type="text" id="category_id" name="category_id" placeholder="Enter your category" />

        </div>
    </fieldset>
</form>
<?php include 'partials/footer.php'; ?>
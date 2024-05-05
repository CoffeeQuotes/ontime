<?php
require 'vendor/autoload.php';
use System\Connection;
use System\SessionManager;

// Define constants if not defined
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require 'constants.php');
}

$pdo = Connection::getInstance();
$session = new SessionManager();
$errors = array();
if (!$session->get('logged_user')) {
    header("Location: " . CONSTANTS['site_url'] . "login.php");
}
if ($session->get('profile_incomplete')) {
    header("Location: " . CONSTANTS['site_url'] . "complete-profile.php");
}
if ($session->get('errors')) {
    $errors = $session->get('errors');
    $session->delete('errors');
}

$user = $session->get('logged_user');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // print_r($_POST);
    $project_name = $_POST['project_name'] ?? '';
    $project_description = $_POST['project_description'] ?? '';
    $status = $_POST['status'] ?? '';
    $project_manager_id = $_POST['project_manager_id'] ?? '';
    $priority = $_POST['priority'] ?? '';
    $budget = $_POST['budget'] ?? '';
    $client_id = $_POST['client_id'] ?? '';
    $category_id = $_POST['category_id'] ?? '';

    // Validate inputs
    if ($project_name == '') {
        $errors['project_name'] = 'Please enter project name';

    }
    if ($status == '') {
        $errors['status'] = 'Please enter status';
    }
    if ($project_manager_id == '') {
        $errors['project_manager_id'] = 'Please enter project manager';
    }
    if ($priority == '') {
        $errors['priority'] = 'Please enter priority';
    }
    if ($client_id == '') {
        $errors['client_id'] = 'Please enter client';
    }

    if ($category_id == '') {
        $errors['category_id'] = 'Please enter category';
    }
    if (count($errors) < 0 || empty($errors)) {
        $sql = "INSERT INTO projects (project_name, project_description, status, project_manager_id, priority, budget, client_id, category_id) VALUES (:project_name, :project_description, :status, :project_manager_id, :priority, :budget, :client_id, :category_id);";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(':project_name', $project_name);
        $statement->bindValue(':project_description', $project_description);
        $statement->bindValue(':status', $status);
        $statement->bindValue(':project_manager_id', $project_manager_id);
        $statement->bindValue(':priority', $priority);
        $statement->bindValue(':budget', $budget);
        $statement->bindValue(':client_id', $client_id);
        $statement->bindValue(':category_id', $category_id);
        $statement->execute();
        $session->set('success', 'Successfully created project.');
        $success = $session->get('success');
        $session->delete('success');
    }
}
$sql = "SELECT users.*, profiles.firstname, profiles.lastname
FROM users LEFT JOIN profiles ON users.id = profiles.user_id
WHERE status='active'";
$statement = $pdo->prepare($sql);
$statement->execute();
$project_managers = $statement->fetchAll(PDO::FETCH_ASSOC);
// Categories
$sql = "SELECT * FROM categories WHERE status='active'";
$statement = $pdo->prepare($sql);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);
// Clients
$sql = "SELECT * FROM clients WHERE status='active'";
$statement = $pdo->prepare($sql);
$statement->execute();
$clients = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'partials/header.php'; ?>
<p><?php echo $failed ?? ''; ?></p>
<p><?php echo $success ?? ''; ?></p>
<form action="" method="POST" class="center-50 my-50">
    <fieldset>
        <legend>Create a new project</legend>
        <div class="form-row">
            <label for="project_name">Project name<sup>*</sup></label>
            <input type="text" id="project_name" name="project_name" onclick="clearError('.error-project-name')"
                placeholder="Enter your project name" />
            <div class="error error-project-name"><?= $errors['project_name'] ?? '' ?></div>
        </div>
        <div class="form-row">
            <label for="project_description">Project description</label>
            <textarea id="project_description" name="project_description"
                placeholder="Enter your project description"></textarea>
        </div>
        <div class="error error-project-description"><?= $errors['project_description'] ?? '' ?></div>
        <div class="form-row">
            <label for="status">Status<sup>*</sup></label>
            <select name="status" placeholder="Enter your status" onchange="clearError('.error-status')">
                <option value="not-started">Not Started</option>
                <option value="in-progress">In Progress</option>
                <option value="stopped">Stopped</option>
                <option value="completed">Complete</option>
            </select>
            <div class="error error-status"><?= $errors['status'] ?? '' ?></div>
        </div>
        <div class="form-row">
            <label for="project_manager_id">Project manager id<sup>*</sup></label>
            <select name="project_manager_id" id="project_manager_id" onchange="clearError('.error-project-manager')">
                <?php foreach ($project_managers as $project_manager): ?>
                    <option value="<?= $project_manager['id'] ?>">
                        <?= '@' . $project_manager['username'] ?> ~
                        <?= $project_manager['firstname'] . ' ' . $project_manager['lastname'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="error error-project-manager"><?= $errors['project_manager_id'] ?? '' ?></div>
        </div>
        <div class=" form-row">
            <label for="priority">Priority<sup>*</sup></label>
            <select id="priority" name="priority" onchange="clearError('.error-priority')">
                <option value='very-low'>Very Low</option>
                <option value='low'>Low</option>
                <option value='medium'>Medium</option>
                <option value='high'>High</option>
                <option value='very-high'>Very High</option>
            </select>
            <div class="error error-priority"><?= $errors['priority'] ?? '' ?></div>
        </div>
        <div class="form-row">
            <label for="budget">Budget</label>
            <input type="text" id="budget" name="budget" placeholder="Enter your budget" />
            <div class="error error-budget"><?= $errors['budget'] ?? '' ?></div>
        </div>
        <div class="form-row">
            <label for="client_id">Select Client<sup>*</sup></label>
            <select name="client_id" id="client_id" onchange="clearError('.error-client')">
                <option value="" disabled selected>Select a Client</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['id'] ?>"><?= $client['client_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <div class="error error-client"><?= $errors['client_id'] ?? '' ?></div>
        </div>
        <div class="form-row">
            <div class="flex justify-content-between align-items-center">
                <input type="text" id="add_client" name="add_client" size="40" placeholder="Enter your client"
                    onclick="clearError('.error-add-client')" />
                <a onclick="sendNewClient()">+ Add New Client</a>
            </div>
            <div class="error-add-client error"></div>
        </div>
        <div class="form-row">
            <label for="category_id">Select Category<sup>*</sup></label>
            <select name="category_id" id="category_id" onchange="clearError('.error-category')">
                <option value="" disabled selected>Select a Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <div class="error error-category"><?= $errors['category_id'] ?? '' ?></div>
        </div>
        <div class="form-row">
            <div class="flex justify-content-between align-items-center">
                <input type="text" id="add_category" name="add_category" size="40" placeholder="Enter your category"
                    onclick="clearError('.error-add-category')" />
                <a onclick="sendNewCategory()">+ Add New Category</a>
            </div>
            <div class="error-add-category error"></div>
        </div>
        <div class="form-row">
            <button class="button" type="submit">Create Project</button>
        </div>
    </fieldset>
</form>
<script>
    function sendNewCategory() {
        // alert("Test");
        category = document.getElementById('add_category').value.trim();
        if (category.length > 0) {
            data = { category_name: category }
            fetch("<?= CONSTANTS['site_url'] . 'ajax-add-category.php' ?>", {  // Replace 'url' with your URL
                method: "POST",  // Replace with your method (GET, POST, etc.)
                // headers: { "Content-Type": "application/json" }, // Optional headers
                body: JSON.stringify(data) // Optional data for methods like POST
            })
                .then(response => response.json())  // Parse the JSON response
                .then(data => {
                    console.log(data); // Access the response data
                    document.getElementById('add_category').value = '';
                    selectCategory = document.getElementById("category_id");
                    removeOptionsExceptFirst(selectCategory);
                    data.categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.text = category.category_name;
                        selectCategory.appendChild(option);
                    });

                })
                .catch(error => {
                    console.error(error); // Handle errors
                });
        } else {
            document.querySelector('.error-add-category').innerHTML = 'Please provide category name first';

        }
    }
    function sendNewClient() {
        // alert("Test");
        client = document.getElementById('add_client').value.trim();
        if (client.length > 0) {
            data = { client_name: client }
            fetch("<?= CONSTANTS['site_url'] . 'ajax-add-client.php' ?>", {  // Replace 'url' with your URL
                method: "POST",  // Replace with your method (GET, POST, etc.)
                // headers: { "Content-Type": "application/json" }, // Optional headers
                body: JSON.stringify(data) // Optional data for methods like POST
            })
                .then(response => response.json())  // Parse the JSON response
                .then(data => {
                    console.log(data); // Access the response data
                    document.getElementById('add_client').value = '';
                    selectClient = document.getElementById("client_id");
                    removeOptionsExceptFirst(selectClient);
                    data.clients.forEach(client => {
                        const option = document.createElement('option');
                        option.value = client.id;
                        option.text = client.client_name;
                        selectClient.appendChild(option);
                    });

                })
                .catch(error => {
                    console.error(error); // Handle errors
                });
        } else {
            document.querySelector('.error-add-client').innerHTML = 'Please provide client name first';

        }
    }
    function removeOptionsExceptFirst(selectElement) {
        for (let i = selectElement.options.length - 1; i > 0; i--) {
            selectElement.options.remove(i);
        }
    }
    function clearError(classname) {
        const errorElement = document.querySelector(classname);
        if (errorElement) {
            errorElement.innerHTML = '';
            // You can also remove any temporary visual feedback for a blank category here (e.g., reset input border color)
        }
    }
    var simplemde = new SimpleMDE({ element: document.getElementById("project_description") });
</script>
<?php include 'partials/footer.php'; ?>
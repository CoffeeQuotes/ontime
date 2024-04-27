<?php
require 'vendor/autoload.php';
use System\Connection;
use System\SessionManager;

// Define constants if not defined
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require 'constants.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    print_r($_POST);
    // if ($_POST['category_name']) {
    //     echo json_encode(array('error' => 'Test passed'));
    //     exit;
    // }
    exit;
}
$pdo = Connection::getInstance();
$session = new SessionManager();
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
<form action="" method="POST" class="center-50 my-50">
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
            <select name="project_manager_id" id="project_manager_id">
                <?php foreach ($project_managers as $project_manager): ?>
                    <option value="<?= $project_manager['id'] ?>">
                        <?= '@' . $project_manager['username'] ?> ~
                        <?= $project_manager['firstname'] . ' ' . $project_manager['lastname'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class=" form-row">
            <label for="priority">Priority</label>
            <select id="priority" name="priority">
                <option value='very-low'>Very Low</option>
                <option value='low'>Low</option>
                <option value='medium'>Medium</option>
                <option value='high'>High</option>
                <option value='very-high'>Very High</option>
            </select>
        </div>
        <div class="form-row">
            <label for="budget">Budget</label>
            <input type="text" id="budget" name="budget" placeholder="Enter your budget" />
        </div>
        <div class="form-row">
            <label for="client_id">Select Client</label>
            <select name="client_id" id="client_id">
                <option value="" disabled selected>Select a Client</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['id'] ?>"><?= $client['client_name'] ?></option>
                <?php endforeach; ?>
            </select>
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
            <label for="category_id">Select Category</label>
            <select name="category_id" id="category_id">
                <option value="" disabled selected>Select a Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <div class="flex justify-content-between align-items-center">
                <input type="text" id="add_category" name="add_category" size="40" placeholder="Enter your category"
                    onclick="clearError('.error-add-category')" />
                <a onclick="sendNewCategory()">+ Add New Category</a>
            </div>
            <div class="error-add-category error"></div>
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

</script>
<?php include 'partials/footer.php'; ?>
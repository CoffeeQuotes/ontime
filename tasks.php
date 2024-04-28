<?php
require "vendor/autoload.php";
use System\SessionManager;
use System\Connection;

require 'functions.php';
$session = new SessionManager();
// Define constants if not defined
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require 'constants.php');
}
if (!$session->get('logged_user')) {
    header("Location: " . CONSTANTS['site_url'] . "login.php");
}

if ($session->get('profile_incomplete')) {
    header("Location: " . CONSTANTS['site_url'] . "complete-profile.php");
}
$pdo = Connection::getInstance();
if ($session->get("success")) {
    $success = $session->get("success");
    $session->delete("success");
}
if ($session->get("failed")) {
    $failed = $session->get("failed");
    $session->delete("failed");
}

$user_id = $session->get("logged_user")["id"];

$project_id = $_GET['project_id'] ?? '';
if ($project_id == '') {
    $sql = "SELECT * FROM tasks WHERE user_id=:user_id ORDER BY updated_at DESC;"; // Later have to update it for filters and sorting
    $statement = $pdo->prepare($sql);
} else {
    $sql = "SELECT * FROM tasks WHERE user_id=:user_id AND project_id=:project_id ORDER BY updated_at DESC;"; // Later have to update it for filters and sorting
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':project_id', $project_id);
}
$statement->bindValue(":user_id", $user_id);
$statement->execute();
$tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
// echo "<pre>";
// print_r($tasks);

$sql = "SELECT * FROM projects ORDER BY updated_at DESC";
$statement = $pdo->prepare($sql);
$statement->execute();
$projects = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "partials/header.php"; ?>

<p class="success-alert"><?php echo $success ?? ""; ?></p>
<p class="error-alert"><?php echo $failed ?? ""; ?></p>
<div class="flex align-items-center justify-content-around">
    <h2>Your Task</h2>
    <a href="create-task.php">Create Task</a>
    <select name="project_id" id="project_id">
        <option value="" <?php echo ($project_id == '') ? 'selected' : ''; ?> disabled>Select Projects </option>
        <?php foreach ($projects as $project): ?>
            <option value="<?= $project['id'] ?>" <?php echo ($project_id == $project['id']) ? 'selected' : ''; ?>>
                <?= $project['project_name'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="flex justify-content-around">
    <div class="kanban-box">
        <h2><i class="fas fa-tasks"></i> Todo</h2>
        <?php
        foreach ($tasks as $task): ?>
            <?php if ($task['status'] == 'not-started'): ?>
                <div class="task p-10 mb-10" data-deadline="<?= strtotime($task['deadline']) ?>">
                    <a href="<?= CONSTANTS['site_url'] . 'show-task.php?task_id=' . $task['id'] ?>">
                        <h3><?php echo createExcerpt($task["title"], 50); ?></h3>
                    </a>
                    <span
                        class="priority-<?= $task["priority"] ?>"><small><?= str_replace("-", " ", strtoupper($task["priority"])) ?></small></span>
                    <p><?php echo createExcerpt($task["description"]); ?></p>
                    <?php
                    $sql = "SELECT * FROM task_assets WHERE task_id=:task_id ORDER BY updated_at ASC;";
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(':task_id', $task['id']);
                    $statement->execute();
                    $task_assets = $statement->fetchAll(PDO::FETCH_ASSOC);
                    if (count($task_assets) > 0) {
                        foreach ($task_assets as $asset) {
                            if ($asset['type'] == 'image/jpeg' || $asset['type'] == 'image/png') {
                                ?>
                                <img src="<?php echo CONSTANTS['site_url'] . 'uploads/' . $asset['location']; ?>" width="100"
                                    height="100" />
                                <?php
                            }
                        }
                    }
                    ?>
                    <div class="countdown-timer"></div> <!-- Display countdown timer here -->
                    <div class="flex justify-content-between">
                        <span
                            class="status-<?= $task["status"] ?>"><small><?= str_replace("-", " ", strtoupper($task["status"])) ?></small></span>
                        <button class="button small-button dark-button" onclick="openModal(<?php echo $task['id']; ?>)"><i
                                class="fa fa-tasks"></i></button>
                    </div>
                </div>
                <?php
            endif; ?>
            <?php
        endforeach; ?>
    </div>
    <div class="kanban-box">
        <h2><i class="fas fa-play"></i> Currently working upon</h2>
        <?php
        foreach ($tasks as $task): ?>
            <?php if ($task['status'] == 'in-progress'): ?>
                <div class="task p-10 mb-10" data-deadline="<?= strtotime($task['deadline']) ?>">
                    <a href="<?= CONSTANTS['site_url'] . 'show-task.php?task_id=' . $task['id'] ?>">
                        <h3><?php echo createExcerpt($task["title"], 50); ?></h3>
                    </a>
                    <span
                        class="priority-<?= $task["priority"] ?>"><small><?= str_replace("-", " ", strtoupper($task["priority"])) ?></small></span>
                    <p><?php echo createExcerpt($task["description"]); ?></p>
                    <?php
                    $sql = "SELECT * FROM task_assets WHERE task_id=:task_id ORDER BY updated_at ASC;";
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(':task_id', $task['id']);
                    $statement->execute();
                    $task_assets = $statement->fetchAll(PDO::FETCH_ASSOC);
                    if (count($task_assets) > 0) {
                        foreach ($task_assets as $asset) {
                            if ($asset['type'] == 'image/jpeg' || $asset['type'] == 'image/png') {
                                ?>
                                <img src="<?php echo CONSTANTS['site_url'] . 'uploads/' . $asset['location']; ?>" width="100"
                                    height="100" />
                                <?php
                            }
                        }
                    }
                    ?>
                    <div class="countdown-timer"></div> <!-- Display countdown timer here -->
                    <div class="flex justify-content-between">
                        <span
                            class="status-<?= $task["status"] ?>"><small><?= str_replace("-", " ", strtoupper($task["status"])) ?></small></span>
                        <button class="button small-button dark-button" onclick="openModal(<?php echo $task['id']; ?>)"><i
                                class="fa fa-tasks"></i></button>
                    </div>
                </div>
                <?php
            endif; ?>
            <?php
        endforeach; ?>
    </div>
    <div class="kanban-box">
        <h2><i class="fas fa-check-circle"></i> Completed</h2>
        <?php foreach ($tasks as $task): ?>
            <?php if ($task['status'] == 'completed'): ?>
                <div class="task p-10 mb-10" data-deadline="<?= strtotime($task['deadline']) ?>">


                    <a href="<?= CONSTANTS['site_url'] . 'show-task.php?task_id=' . $task['id'] ?>">
                        <h3><?php echo createExcerpt($task["title"], 50); ?></h3>
                    </a>
                    <span
                        class="priority-<?= $task["priority"] ?>"><small><?= str_replace("-", " ", strtoupper($task["priority"])) ?></small></span>
                    <p><?php echo createExcerpt($task["description"]); ?></p>
                    <?php
                    $sql = "SELECT * FROM task_assets WHERE task_id=:task_id ORDER BY updated_at ASC;";
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(':task_id', $task['id']);
                    $statement->execute();
                    $task_assets = $statement->fetchAll(PDO::FETCH_ASSOC);
                    if (count($task_assets) > 0) {
                        foreach ($task_assets as $asset) {
                            if ($asset['type'] == 'image/jpeg' || $asset['type'] == 'image/png') {
                                ?>
                                <img src="<?php echo CONSTANTS['site_url'] . 'uploads/' . $asset['location']; ?>" width="100"
                                    height="100" />
                                <?php
                            }
                        }
                    }
                    ?>
                    <div class="countdown-timer"></div> <!-- Display countdown timer here -->
                    <div class="flex justify-content-between">
                        <span
                            class="status-<?= $task["status"] ?>"><small><?= str_replace("-", " ", strtoupper($task["status"])) ?></small></span>
                        <button class="button small-button dark-button" onclick="openModal(<?php echo $task['id']; ?>)"><i
                                class="fa fa-tasks"></i></button>
                    </div>
                </div>
                <?php
            endif; ?>
            <?php
        endforeach; ?>
    </div>
    <div class="kanban-box">
        <h2><i class="fas fa-stop-circle"></i> Abandon</h2>
        <?php
        foreach ($tasks as $task): ?>
            <?php if ($task['status'] == 'stopped'): ?>
                <div class="task p-10 mb-10" data-deadline="<?= strtotime($task['deadline']) ?>">
                    <a href="<?= CONSTANTS['site_url'] . 'show-task.php?task_id=' . $task['id'] ?>">
                        <h3><?php echo createExcerpt($task["title"], 50); ?></h3>
                    </a>
                    <span
                        class="priority-<?= $task["priority"] ?>"><small><?= str_replace("-", " ", strtoupper($task["priority"])) ?></small></span>
                    <p><?php echo createExcerpt($task["description"]); ?></p>
                    <?php
                    $sql = "SELECT * FROM task_assets WHERE task_id=:task_id ORDER BY updated_at ASC;";
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(':task_id', $task['id']);
                    $statement->execute();
                    $task_assets = $statement->fetchAll(PDO::FETCH_ASSOC);
                    if (count($task_assets) > 0) {
                        foreach ($task_assets as $asset) {
                            if ($asset['type'] == 'image/jpeg' || $asset['type'] == 'image/png') {
                                ?>
                                <img src="<?php echo CONSTANTS['site_url'] . 'uploads/' . $asset['location']; ?>" width="100"
                                    height="100" />
                                <?php
                            }
                        }
                    }
                    ?>
                    <div class="countdown-timer"></div> <!-- Display countdown timer here -->
                    <div class="flex justify-content-between">
                        <span
                            class="status-<?= $task["status"] ?>"><small><?= str_replace("-", " ", strtoupper($task["status"])) ?></small></span>
                        <button class="button small-button dark-button" onclick="openModal(<?php echo $task['id']; ?>)"><i
                                class="fa fa-tasks"></i></button>
                    </div>
                </div>
                <?php
            endif; ?>
            <?php
        endforeach; ?>
    </div>

</div>
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Update Work Status</h2>
        <form method="post" action="update-task.php">
            <div class="flex flex-column mb-10">
                <label for="status">Select Status</label>
                <select id="status" name="status">
                    <option value="not-started">Not started</option>
                    <option value="in-progress">In progress</option>
                    <option value="stopped">Stopped</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div>
                <input class="button primary-button" type="submit" value="Update" />
            </div>
        </form>
    </div>
</div>
<script>

    function openModal(taskId) {
        var modal = document.getElementById("myModal");
        modal.style.display = "block";
        // Find the form element inside the modal
        var form = modal.querySelector("form");
        // Create a hidden input element
        var hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "task_id";
        hiddenInput.value = taskId;

        // Append the hidden input to the form
        form.appendChild(hiddenInput);
    }

    function closeModal() {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
        // Find the form element inside the modal
        var form = modal.querySelector("form");

        // Find and remove the hidden input element
        var hiddenInput = form.querySelector("input[name='task_id']");
        if (hiddenInput) {
            form.removeChild(hiddenInput);
        }
    }

    /*** Countdown Deadline ***/
    document.addEventListener("DOMContentLoaded", function () {
        var tasks = document.querySelectorAll('.task');
        tasks.forEach(function (task) {
            var countdownTimer = task.querySelector('.countdown-timer');
            var deadline = parseInt(task.getAttribute("data-deadline"));

            // Update countdown timer every second
            setInterval(function () {
                var currentTime = Math.floor(Date.now() / 1000); // Convert milliseconds to seconds
                var remainingTime = deadline - currentTime;

                if (remainingTime <= 0) {
                    countdownTimer.textContent = 'Deadline passed';
                } else {
                    var days = Math.floor(remainingTime / (60 * 60 * 24));
                    var hours = Math.floor((remainingTime % (60 * 60 * 24)) / (60 * 60));
                    var minutes = Math.floor((remainingTime % (60 * 60)) / 60);
                    var seconds = Math.floor(remainingTime % 60);

                    // Format the countdown timer
                    var timerText = '';
                    if (days > 0) {
                        timerText += days + "d ";
                    }
                    if (hours > 0 || days > 0) {
                        timerText += hours + "h ";
                    }
                    if (minutes > 0 || hours > 0 || days > 0) {
                        timerText += minutes + "m ";
                    }
                    timerText += seconds + "s";

                    countdownTimer.textContent = timerText;
                }
            }, 1000); // Update every second
        });
    });


    // Project Selected %
    document.getElementById("project_id").addEventListener("change", function (event) {
        // Get project Id 
        project_id = this.value;
        // alert(project_id);
        // change the url with new project_id 
        // Assuming the URL is in the format "http://example.com/page?project_id=value"
        var currentUrl = window.location.href;
        var newUrl;

        // Check if the URL already has a query string
        if (currentUrl.indexOf('?') !== -1) {
            // URL already has a query string
            newUrl = currentUrl.replace(/(project_id=)[^\&]+/, '$1' + project_id);
        } else {
            // URL doesn't have a query string
            newUrl = currentUrl + '?project_id=' + project_id;
        }

        // Redirect to the new URL
        window.location.href = newUrl;
    });

</script>
<?php include "partials/footer.php"; ?>
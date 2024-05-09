<?php
require '../vendor/autoload.php';
use System\Connection;

require '../functions.php';
// Define constants if not defined
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require '../constants.php');
}
$pdo = Connection::getInstance();


$sql = "SELECT * FROM projects ORDER BY updated_at DESC";
$statement = $pdo->prepare($sql);
$statement->execute();
$projects = $statement->fetchAll(PDO::FETCH_ASSOC);

$project_id = $_GET['project_id'] ?? '';
if ($project_id == '') {
    $sql = "SELECT * FROM tasks ORDER BY updated_at DESC;"; // Later have to update it for filters and sorting
    $statement = $pdo->prepare($sql);
} else {
    $sql = "SELECT * FROM tasks WHERE project_id=:project_id ORDER BY updated_at DESC;"; // Later have to update it for filters and sorting
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':project_id', $project_id);
}
$statement->execute();
$tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
// print_r($projects);
$page_title = "Tasks";
$page_desc = "Manage Tasks";


?>
<?php include 'partials/head.php'; ?>
<div class="page-body">
    <div class="container-xl">
        <div class="d-flex mb-3 justify-content-end align-items-center gap-2">
            <label class="form-label">Select projects</label>
            <select type="text" class="form-select" placeholder="Select a role" id="project_id" value="">
                <option value="" selected disabled></option>
                <?php foreach ($projects as $item): ?>
                    <option value="<?= $item['id'] ?>" <?php if (isset($project_id) && $project_id == $item['id']):
                          echo 'selected';
                      endif; ?>><?= ucfirst($item['project_name']); ?></option>
                <?php endforeach; ?>
            </select>
            <button id="clearFilter" class="btn btn-default">Clear</button>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 col-lg">
                <h2 class="mb-3">To Do</h2>
                <div class="mb-4">
                    <div class="row row-cards">
                        <?php foreach ($tasks as $task): ?>
                            <?php if ($task['status'] == 'not-started'): ?>
                                <div class="col-12">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <h3 class="card-title"><?= $task['title']; ?></h3>
                                            <div class="text-muted mb-2"><?php echo createExcerpt($task["description"]); ?>
                                            </div>
                                            <?php
                                            $sql = "SELECT * FROM task_assets WHERE task_id=:task_id ORDER BY updated_at ASC;";
                                            $statement = $pdo->prepare($sql);
                                            $statement->bindValue(':task_id', $task['id']);
                                            $statement->execute();
                                            $task_assets = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            ?>
                                            <div class="container" style="max-width: 100%; padding: 0;">
                                                <div class="row" style=" margin-right: 0; margin-left: 0;">
                                                    <?php
                                                    if (count($task_assets) > 0):
                                                        foreach ($task_assets as $asset):
                                                            if ($asset['type'] == 'image/jpeg' || $asset['type'] == 'image/png'):
                                                                ?>
                                                                <div class="col-md-3 col-sm-6 col-xs-12" style="padding:0;">
                                                                    <!-- <img src="./static/projects/dashboard-1.png" class="rounded object-cover"
                                                                alt=""> -->
                                                                    <img src="<?php echo CONSTANTS['site_url'] . 'uploads/' . $asset['location']; ?>"
                                                                        style="width: 100%; height: auto;" />
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="avatar-list avatar-list-stacked">
                                                            <span class="avatar avatar-xs avatar-rounded">EP</span>
                                                            <span class="avatar avatar-xs avatar-rounded"
                                                                style="background-image: url(./static/avatars/002f.jpg)"></span>
                                                            <span class="avatar avatar-xs avatar-rounded"
                                                                style="background-image: url(./static/avatars/003f.jpg)"></span>
                                                            <span class="avatar avatar-xs avatar-rounded">HS</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto text-muted">
                                                        <button class="switch-icon switch-icon-scale"
                                                            data-bs-toggle="switch-icon">
                                                            <span class="switch-icon-a text-muted">
                                                                <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M19.5 12.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                                                </svg>
                                                            </span>
                                                            <span class="switch-icon-b text-red">
                                                                <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-filled"
                                                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M19.5 12.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                                                </svg>
                                                            </span>
                                                        </button>
                                                        7
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#"
                                                            class="link-muted"><!-- Download SVG icon from http://tabler-icons.io/i/message -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path
                                                                    d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4" />
                                                                <line x1="8" y1="9" x2="16" y2="9" />
                                                                <line x1="8" y1="13" x2="14" y2="13" />
                                                            </svg>
                                                            2</a>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#"
                                                            class="link-muted"><!-- Download SVG icon from http://tabler-icons.io/i/share -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <circle cx="6" cy="12" r="3" />
                                                                <circle cx="18" cy="6" r="3" />
                                                                <circle cx="18" cy="18" r="3" />
                                                                <line x1="8.7" y1="10.7" x2="15.3" y2="7.3" />
                                                                <line x1="8.7" y1="13.3" x2="15.3" y2="16.7" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg">
                <h2 class="mb-3">In Progress</h2>
                <div class="mb-4">
                    <div class="row row-cards">
                        <?php foreach ($tasks as $task): ?>
                            <?php if ($task['status'] == 'in-progress'): ?>
                                <div class="col-12">
                                    <div class="card card-sm">
                                        <div class="card-status-top bg-green"></div>
                                        <div class="card-body">
                                            <h3 class="card-title"><?= $task['title']; ?></h3>
                                            <div class="text-muted mb-2"><?php echo createExcerpt($task["description"]); ?>
                                            </div>
                                            <?php
                                            $sql = "SELECT * FROM task_assets WHERE task_id=:task_id ORDER BY updated_at ASC;";
                                            $statement = $pdo->prepare($sql);
                                            $statement->bindValue(':task_id', $task['id']);
                                            $statement->execute();
                                            $task_assets = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            ?>
                                            <div class="container" style="max-width: 100%; padding: 0;">
                                                <div class="row" style=" margin-right: 0; margin-left: 0;">
                                                    <?php
                                                    if (count($task_assets) > 0):
                                                        foreach ($task_assets as $asset):
                                                            if ($asset['type'] == 'image/jpeg' || $asset['type'] == 'image/png'):
                                                                ?>
                                                                <div class="col-md-3 col-sm-6 col-xs-12" style="padding:0;">
                                                                    <!-- <img src="./static/projects/dashboard-1.png" class="rounded object-cover"
                                                                alt=""> -->
                                                                    <img src="<?php echo CONSTANTS['site_url'] . 'uploads/' . $asset['location']; ?>"
                                                                        style="width: 100%; height: auto;" />
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="avatar-list avatar-list-stacked">
                                                            <span class="avatar avatar-xs avatar-rounded">EP</span>
                                                            <span class="avatar avatar-xs avatar-rounded"
                                                                style="background-image: url(./static/avatars/002f.jpg)"></span>
                                                            <span class="avatar avatar-xs avatar-rounded"
                                                                style="background-image: url(./static/avatars/003f.jpg)"></span>
                                                            <span class="avatar avatar-xs avatar-rounded">HS</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto text-muted">
                                                        <button class="switch-icon switch-icon-scale"
                                                            data-bs-toggle="switch-icon">
                                                            <span class="switch-icon-a text-muted">
                                                                <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M19.5 12.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                                                </svg>
                                                            </span>
                                                            <span class="switch-icon-b text-red">
                                                                <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-filled"
                                                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M19.5 12.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                                                </svg>
                                                            </span>
                                                        </button>
                                                        7
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#"
                                                            class="link-muted"><!-- Download SVG icon from http://tabler-icons.io/i/message -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path
                                                                    d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4" />
                                                                <line x1="8" y1="9" x2="16" y2="9" />
                                                                <line x1="8" y1="13" x2="14" y2="13" />
                                                            </svg>
                                                            2</a>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#"
                                                            class="link-muted"><!-- Download SVG icon from http://tabler-icons.io/i/share -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <circle cx="6" cy="12" r="3" />
                                                                <circle cx="18" cy="6" r="3" />
                                                                <circle cx="18" cy="18" r="3" />
                                                                <line x1="8.7" y1="10.7" x2="15.3" y2="7.3" />
                                                                <line x1="8.7" y1="13.3" x2="15.3" y2="16.7" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg">
                <h2 class="mb-3">Abandon</h2>
                <div class="mb-4">
                    <div class="row row-cards">
                        <?php foreach ($tasks as $task): ?>
                            <?php if ($task['status'] == 'stopped'): ?>
                                <div class="col-12">
                                    <div class="card card-sm">
                                        <div class="card-status-top bg-red"></div>
                                        <div class="card-body">
                                            <h3 class="card-title"><?= $task['title']; ?></h3>
                                            <div class="text-muted mb-2"><?php echo createExcerpt($task["description"]); ?>
                                            </div>
                                            <?php
                                            $sql = "SELECT * FROM task_assets WHERE task_id=:task_id ORDER BY updated_at ASC;";
                                            $statement = $pdo->prepare($sql);
                                            $statement->bindValue(':task_id', $task['id']);
                                            $statement->execute();
                                            $task_assets = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            ?>
                                            <div class="container" style="max-width: 100%; padding: 0;">
                                                <div class="row" style=" margin-right: 0; margin-left: 0;">
                                                    <?php
                                                    if (count($task_assets) > 0):
                                                        foreach ($task_assets as $asset):
                                                            if ($asset['type'] == 'image/jpeg' || $asset['type'] == 'image/png'):
                                                                ?>
                                                                <div class="col-md-3 col-sm-6 col-xs-12" style="padding:0;">
                                                                    <!-- <img src="./static/projects/dashboard-1.png" class="rounded object-cover"
                                                                alt=""> -->
                                                                    <img src="<?php echo CONSTANTS['site_url'] . 'uploads/' . $asset['location']; ?>"
                                                                        style="width: 100%; height: auto;" />
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="avatar-list avatar-list-stacked">
                                                            <span class="avatar avatar-xs avatar-rounded">EP</span>
                                                            <span class="avatar avatar-xs avatar-rounded"
                                                                style="background-image: url(./static/avatars/002f.jpg)"></span>
                                                            <span class="avatar avatar-xs avatar-rounded"
                                                                style="background-image: url(./static/avatars/003f.jpg)"></span>
                                                            <span class="avatar avatar-xs avatar-rounded">HS</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto text-muted">
                                                        <button class="switch-icon switch-icon-scale"
                                                            data-bs-toggle="switch-icon">
                                                            <span class="switch-icon-a text-muted">
                                                                <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M19.5 12.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                                                </svg>
                                                            </span>
                                                            <span class="switch-icon-b text-red">
                                                                <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-filled"
                                                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M19.5 12.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                                                </svg>
                                                            </span>
                                                        </button>
                                                        7
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#"
                                                            class="link-muted"><!-- Download SVG icon from http://tabler-icons.io/i/message -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path
                                                                    d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4" />
                                                                <line x1="8" y1="9" x2="16" y2="9" />
                                                                <line x1="8" y1="13" x2="14" y2="13" />
                                                            </svg>
                                                            2</a>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#"
                                                            class="link-muted"><!-- Download SVG icon from http://tabler-icons.io/i/share -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <circle cx="6" cy="12" r="3" />
                                                                <circle cx="18" cy="6" r="3" />
                                                                <circle cx="18" cy="18" r="3" />
                                                                <line x1="8.7" y1="10.7" x2="15.3" y2="7.3" />
                                                                <line x1="8.7" y1="13.3" x2="15.3" y2="16.7" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg">
                <h2 class="mb-3">Completed</h2>
                <div class="mb-4">
                    <div class="row row-cards">
                        <?php foreach ($tasks as $task): ?>
                            <?php if ($task['status'] == 'completed'): ?>
                                <div class="col-12">
                                    <div class="card card-sm">
                                        <div class="card-status-top bg-blue"></div>
                                        <div class="card-body">
                                            <h3 class="card-title"><?= $task['title']; ?></h3>
                                            <div class="text-muted mb-2"><?php echo createExcerpt($task["description"]); ?>
                                            </div>
                                            <?php
                                            $sql = "SELECT * FROM task_assets WHERE task_id=:task_id ORDER BY updated_at ASC;";
                                            $statement = $pdo->prepare($sql);
                                            $statement->bindValue(':task_id', $task['id']);
                                            $statement->execute();
                                            $task_assets = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            ?>
                                            <div class="container" style="max-width: 100%; padding: 0;">
                                                <div class="row" style=" margin-right: 0; margin-left: 0;">
                                                    <?php
                                                    if (count($task_assets) > 0):
                                                        foreach ($task_assets as $asset):
                                                            if ($asset['type'] == 'image/jpeg' || $asset['type'] == 'image/png'):
                                                                ?>
                                                                <div class="col-md-3 col-sm-6 col-xs-12" style="padding:0;">
                                                                    <!-- <img src="./static/projects/dashboard-1.png" class="rounded object-cover"
                                                                alt=""> -->
                                                                    <img src="<?php echo CONSTANTS['site_url'] . 'uploads/' . $asset['location']; ?>"
                                                                        style="width: 100%; height: auto;" />
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="avatar-list avatar-list-stacked">
                                                            <span class="avatar avatar-xs avatar-rounded">EP</span>
                                                            <span class="avatar avatar-xs avatar-rounded"
                                                                style="background-image: url(./static/avatars/002f.jpg)"></span>
                                                            <span class="avatar avatar-xs avatar-rounded"
                                                                style="background-image: url(./static/avatars/003f.jpg)"></span>
                                                            <span class="avatar avatar-xs avatar-rounded">HS</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto text-muted">
                                                        <button class="switch-icon switch-icon-scale"
                                                            data-bs-toggle="switch-icon">
                                                            <span class="switch-icon-a text-muted">
                                                                <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M19.5 12.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                                                </svg>
                                                            </span>
                                                            <span class="switch-icon-b text-red">
                                                                <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-filled"
                                                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path
                                                                        d="M19.5 12.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                                                </svg>
                                                            </span>
                                                        </button>
                                                        7
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#"
                                                            class="link-muted"><!-- Download SVG icon from http://tabler-icons.io/i/message -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path
                                                                    d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4" />
                                                                <line x1="8" y1="9" x2="16" y2="9" />
                                                                <line x1="8" y1="13" x2="14" y2="13" />
                                                            </svg>
                                                            2</a>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#"
                                                            class="link-muted"><!-- Download SVG icon from http://tabler-icons.io/i/share -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <circle cx="6" cy="12" r="3" />
                                                                <circle cx="18" cy="6" r="3" />
                                                                <circle cx="18" cy="18" r="3" />
                                                                <line x1="8.7" y1="10.7" x2="15.3" y2="7.3" />
                                                                <line x1="8.7" y1="13.3" x2="15.3" y2="16.7" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'partials/footer.php'; ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var el;
        window.TomSelect && (new TomSelect(el = document.getElementById('project_id'), {
            copyClassesToDropdown: false,
            dropdownClass: 'dropdown-menu ts-dropdown',
            optionClass: 'dropdown-item',
            controlInput: '<input>',
            render: {
                item: function (data, escape) {
                    if (data.customProperties) {
                        return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                    }
                    return '<div>' + escape(data.text) + '</div>';
                },
                option: function (data, escape) {
                    if (data.customProperties) {
                        return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                    }
                    return '<div>' + escape(data.text) + '</div>';
                },
            },
            //     onChange: function (e) {
            //         var roleId = this.getValue();
            //         var url = new URL(window.location.href);
            //         url.searchParams.set('role_id', roleId);
            //         window.location.href = url.href;
            //     }
        }));
    });

    // Project Selected %
    document.getElementById("project_id").addEventListener("change", function (event) {
        project_id = this.value;
        var currentUrl = window.location.href;
        var newUrl;
        if (currentUrl.indexOf('?') !== -1) {
            // URL already has a query string
            newUrl = currentUrl.replace(/(project_id=)[^\&]+/, '$1' + project_id);
        } else {
            // URL doesn't have a query string
            newUrl = currentUrl + '?project_id=' + project_id;
        }
        window.location.href = newUrl;
    });

    document.addEventListener("DOMContentLoaded", function () {
        var clearButton = document.getElementById("clearFilter");

        clearButton.addEventListener("click", function () {
            var url = new URL(window.location.href);
            url.search = "";
            window.location.href = url.href;
        });
    });
</script>
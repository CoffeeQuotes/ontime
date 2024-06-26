<?php
require '../vendor/autoload.php';
use System\Connection;

require __DIR__ . '/../functions.php';
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require __DIR__ . '/../constants.php');
}
$page_title = "Teams";
$page_desc = "Configure roles to define the groups of authorities for you project";
//data-bs-toggle="modal" data-bs-target="#createTeamModal"
$page_target_modal = "#createTeamModal";
$pdo = Connection::getInstance();
$sql = "SELECT * FROM teams WHERE status='active' ORDER BY updated_at DESC";
$statement = $pdo->prepare($sql);
$statement->execute();
$teams = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'partials/head.php'; ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Teams</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>color</th>
                                    <!-- <th>description</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teams as $team): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex py-1 align-items-center">
                                                <span class="avatar me-2"
                                                    style="background-image: url(<?= '../uploads/' . $team['logo']; ?>)"></span>
                                                <div class="flex-fill">
                                                    <div class="font-weight-medium"><?= ucfirst($team['team_name']) ?></div>
                                                    <div class="text-muted"><a href="#"
                                                            class="text-reset"><?= createExcerpt($team['description']) ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="form-colorinput-color <?= 'bg-' . $team['color'] ?> rounded-circle"></span>
                                        </td>
                                        <!-- <td></td> -->
                                        <td></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="createTeamModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Create a new Team
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="Close"></button>
            </div>
            <form id="createTeamForm" action="add-team.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row mb-3 align-items-end">
                        <div class="col-auto">
                            <label for="file-upload" class="avatar avatar-upload rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                <span class="avatar-upload-text">Add</span>
                            </label>
                            <input id="file-upload" name="logo" type="file" style="display: none;" />
                        </div>

                        <div class="col">
                            <label class="form-label">
                                Team name
                            </label>
                            <input type="text" name="team_name" id="team_name" class="form-control" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pick your team color</label>
                        <div class="row g-2">
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="dark" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-dark"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput form-colorinput-light">
                                    <input name="color" type="radio" value="white" class="form-colorinput-input"
                                        checked />
                                    <span class="form-colorinput-color bg-white"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="blue" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-blue"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="azure" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-azure"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="indigo" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-indigo"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="purple" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-purple"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="pink" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-pink"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="red" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-red"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="orange" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-orange"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="yellow" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-yellow"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="lime" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-lime"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Additional info</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="team" class="btn btn-primary">Add Team</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'partials/footer.php'; ?>
<script>
    document.getElementById('file-upload').addEventListener('change', function () {
        var fileInput = document.getElementById('file-upload');
        var fileName = document.querySelector('.avatar-upload-text');
        fileName.textContent = fileInput.files[0].name;
    });
</script>
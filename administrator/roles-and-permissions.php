<?php
require '../vendor/autoload.php';
use System\Connection;

require __DIR__ . '/../functions.php';
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require __DIR__ . '/../constants.php');
}
$page_title = "Roles &amp; Permissions";
$page_desc = "Configure roles to define the groups of authorities for you project";
$pdo = Connection::getInstance();
$sql = "SELECT * FROM roles;";
$statement = $pdo->prepare($sql);
$statement->execute();
$roles = $statement->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM profiles  ORDER BY updated_at DESC;";
$statement = $pdo->prepare($sql);
$statement->execute();
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

?>
<?php include 'partials/head.php'; ?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">System Roles</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>Users</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($roles as $role): ?>
                                    <tr>

                                        <td>
                                            <div><?= ucfirst($role['role_name']); ?></div>
                                            <div class="text-muted"><?= $role['description']; ?></div>
                                        </td>
                                        <td>
                                            <div class="d-flex py-1 align-items-center">
                                                <?php
                                                $role_id = $role['id'];
                                                // echo $role_id;
                                                $sql = "SELECT user_roles.*, users.*, profiles.* FROM user_roles LEFT JOIN users ON users.id = user_roles.user_id LEFT JOIN profiles ON users.id = profiles.user_id WHERE role_id = :role_id;";
                                                $statement = $pdo->prepare($sql);
                                                $statement->bindValue(':role_id', $role_id, PDO::PARAM_INT);
                                                $statement->execute();
                                                $user_roles = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                $counter = 0; // Counter variable to keep track of the number of records displayed
                                                foreach ($user_roles as $user_role):
                                                    if ($counter < 4) { // Display up to 4 records
                                                        ?>
                                                        <span class="avatar me-2"
                                                            title="<?= $user_role['firstname'] . ' ' . $user_role['lastname'] ?>"
                                                            style="background-image: url('<?php echo CONSTANTS['site_url'] . 'uploads/' . $user_role['picture']; ?>')"></span>
                                                        <?php
                                                        $counter++;
                                                    } else {
                                                        $remaining_records = count($user_roles) - 4;
                                                        echo "+$remaining_records";
                                                        break; // Exit the loop after displaying "+2", "+3", etc.
                                                    }
                                                endforeach;
                                                ?>
                                            </div>

                                        </td>
                                        <td class="text-muted">
                                            <a href="javascript:void(0)" class="btn btn-primary assignMemberBtn"
                                                data-bs-toggle="modal" data-bs-target="#assignMemberModal"
                                                data-role="<?= $role['id'] ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                    <path d="M16 19h6" />
                                                    <path d="M19 16v6" />
                                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                                </svg>
                                                &nbsp; Assign member
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="my-5 container-xl">
        <div class="myrow row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Custom Roles</h3>
                        <a class="btn btn-success" href="javascript:void(0)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-playlist-add">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M19 8h-14" />
                                <path d="M5 12h9" />
                                <path d="M11 16h-6" />
                                <path d="M15 16h6" />
                                <path d="M18 13v6" />
                            </svg>&nbsp;
                            Create a custom role
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="assignMemberModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="header-title">Assign member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="role_id" id="roleId" value="" />
                    <div class="mb-3">
                        <label class="form-label">Select Member</label>
                        <select type="text" class="form-select" placeholder="Select a date" id="select-people"
                            name="user_id" value="">
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['user_id'] ?>"
                                    data-custom-properties="&lt;span class=&quot;avatar avatar-xs&quot; style=&quot;background-image: url(<?= "../uploads/" . $user['picture']; ?>)&quot;&gt;&lt;/span&gt;">
                                    <?= $user['firstname'] . ' ' . $user['lastname'] ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                            <path d="M16 19h6" />
                            <path d="M19 16v6" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                        </svg>
                        &nbsp;Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
<script>
    var assignMemeberBtn = document.querySelectorAll(".assignMemberBtn");
    assignMemeberBtn.forEach(assignMemeberBtn => {
        assignMemeberBtn.addEventListener('click', function (event) {
            var clickedButton = event.target;
            var role_id = clickedButton.getAttribute('data-role');
            // alert(role_id);
            var roleInputField = document.getElementById("roleId");
            roleInputField.value = role_id;
            var data = { role_id: role_id };
            fetch('fetch-user-roles.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json' // Specify the content type as JSON
                },
                body: JSON.stringify(data) // Convert data to JSON string
            }).then(response => response.json()).then(data => {
                console.log(data.user_ids)

            });
        });
    });
</script>
<script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
        var el;
        window.TomSelect && (new TomSelect(el = document.getElementById('select-people'), {
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
        }));
    });
    // @formatter:on
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('assignForm').addEventListener('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            fetch('assign-role.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.success !== '') {
                        document.getElementById('assignForm').reset();
                        location.reload();
                    }
                })
                .catch(error => {
                    // console.error('Error:', error);
                });
        });
    });
</script>
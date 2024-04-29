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
?>
<?php include 'partials/head.php'; ?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
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
                                                $sql = "SELECT user_roles.*, users.*, profiles.*
            FROM user_roles
            LEFT JOIN users ON users.id = user_roles.user_id
            LEFT JOIN profiles ON users.id = profiles.user_id
            WHERE role_id = :role_id;";
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
                                            User
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
</div>
<?php include 'partials/footer.php'; ?>
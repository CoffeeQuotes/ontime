<?php
require __DIR__ . '/../vendor/autoload.php';
use System\Connection;

require __DIR__ . '/../functions.php';
$pdo = Connection::getInstance();
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require __DIR__ . '/../constants.php');
}

$page_title = "Assign Permissions";
$page_desc = "Assign permissions to the the role.";
$role_id = $_GET['p'] ?? '';
// $enabledPermission = array();
// Get all permissions from database
$sql = "SELECT * FROM permissions ORDER BY id ASC;";
$statement = $pdo->prepare($sql);
$statement->execute();
$permissions = $statement->fetchAll(PDO::FETCH_ASSOC);
// Group permissions by type
$permissionsByType = [];
foreach ($permissions as $permission) {
    $permissionsByType[$permission['type']][] = $permission;
}
// Get the assigned role_persmission for current role id // Suppose admin
// $sql = "SELECT permission_id FROM role_permissions WHERE role_id=:role_id";
// $statement = $pdo->prepare($sql);
// $statement->bindValue(':role_id', $role_id);
// $statement->execute();
// $result = $statement->fetchAll(PDO::FETCH_ASSOC);
// $enabledPermission = flattenArray($result);
$enabledPermission = getPermissionsForRole($pdo, $role_id);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // We get the values and insert those in roles_permissions
    $role_id = $_POST['role_id'] ?? '';
    $permissions = $_POST['permissions'] ?? array();
    if (isset($role_id) && isset($permissions)) {
        if ($role_id != '') {
            try {
                // Begin a transaction
                $pdo->beginTransaction();

                // Delete permissions that are no longer selected
                $sqlDelete = "DELETE FROM role_permissions WHERE role_id = :role_id AND permission_id NOT IN (" . implode(",", $permissions) . ")";
                $statementDelete = $pdo->prepare($sqlDelete);
                $statementDelete->bindValue(':role_id', $role_id);
                $statementDelete->execute();

                // Insert new permissions or existing permissions
                foreach ($permissions as $permission_id) {
                    $sqlInsert = "INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)
                                  ON DUPLICATE KEY UPDATE role_id=role_id"; // Do nothing on duplicate
                    $statementInsert = $pdo->prepare($sqlInsert);
                    $statementInsert->bindValue(':role_id', $role_id);
                    $statementInsert->bindValue(':permission_id', $permission_id);
                    $statementInsert->execute();
                }

                // Commit the transaction if all insertions succeed
                $pdo->commit();
                $enabledPermission = getPermissionsForRole($pdo, $role_id);
                $success = "Permissions updated successfully!";
                $message = "Your permissions have been updated.";
            } catch (PDOException $e) {
                // Rollback the transaction if any operation fails
                $pdo->rollBack();

                $failed = "Failed to update permissions.";
                $message = "Transaction failed: " . $e->getMessage();
            }
        }
    }
}
function getPermissionsForRole($pdo, $role_id)
{
    $sql = "SELECT permission_id FROM role_permissions WHERE role_id=:role_id";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':role_id', $role_id);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return flattenArray($result);
}
?>
<?php include 'partials/head.php'; ?>
<div class="page-body">
    <div class="container-xl">
        <row class="row row-cards">
            <div class="col-12">
                <div class="col">
                    <?php if (isset($success) || isset($failed)): ?>
                        <div class="alert alert-<?php
                        if (isset($success) && $success != '') {
                            echo "success";
                        } elseif (isset($failed) && $failed != '') {
                            echo "danger";
                        } else {
                            echo "";
                        }
                        ?> alert-dismissible fade show" role="alert">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="alert-title"><?= ($success) ?? $failed; ?></h4>
                                    <div class="text-secondary"><?= $message; ?></div>
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    <?php endif; ?>
                    <form class="card" method="POST" action="">
                        <input type="hidden" name="role_id" value="<?= $role_id ?>" />
                        <div class="card-header">
                            <h3 class="card-title">Manage Permission</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <?php
                                foreach ($permissionsByType as $type => $typePermissions): ?>
                                    <label class="form-label">Manage <?= ucfirst($type) ?> </label>
                                    <div>
                                        <?php foreach ($typePermissions as $permission): ?>
                                            <label class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                                    value="<?= $permission['id'] ?>" <?= in_array($permission['id'], $enabledPermission) ? 'checked' : '' ?> />
                                                <span class="form-check-label"><?= $permission['description'] ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </row>
    </div>
</div>
<?php include 'partials/footer.php'; ?>
<script>
    // Wait for the document to be ready
    document.addEventListener("DOMContentLoaded", function () {
        // Select all alert elements
        var alerts = document.querySelectorAll('.alert');

        // Loop through each alert
        alerts.forEach(function (alert) {
            // Set a timeout to fade out each alert after 5 seconds (5000 milliseconds)
            setTimeout(function () {
                alert.classList.remove("show"); // Remove the 'show' class
                alert.classList.add("fade"); // Add the 'fade' class to trigger the CSS transition
                // After the transition duration (0.5 seconds), remove the alert from the DOM
                setTimeout(function () {
                    alert.remove();
                }, 300);
            }, 3000); // 5000 milliseconds = 5 seconds
        });
    });
</script>
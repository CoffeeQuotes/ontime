<?php
require __DIR__ . '/../functions.php';
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require __DIR__ . '/../constants.php');
}
$page_title = "Assign Permissions";
$page_desc = "Assign permissions to the the role.";
$role_id = $_GET['p'] ?? '';
?>
<?php include 'partials/head.php'; ?>
<div class="page-body">
    <div class="container-xl">
        <row class="row row-cards">
            <div class="col-12">
                <form action="" class="card" method="post">
                </form>
            </div>
        </row>
    </div>
</div>
<?php include 'partials/footer.php'; ?>
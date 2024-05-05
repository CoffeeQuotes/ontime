<?php
require '../vendor/autoload.php';
use System\Connection;

require __DIR__ . '/../functions.php';
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require __DIR__ . '/../constants.php');
}
$page_title = "Users";
$page_desc = "Manage different users.";
$pdo = Connection::getInstance();

$page_size = 10;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $page_size;

$role_id = $_GET['role_id'] ?? '';
$sql = "SELECT * FROM roles WHERE status = 'active'";
$statement = $pdo->prepare($sql);
$statement->execute();
$roles = $statement->fetchAll(PDO::FETCH_ASSOC);
$groupedRoles = [];
foreach ($roles as $item) {
    $groupedRoles[$item['type']][] = $item;
}
// Count total number of records
$sqlCount = "SELECT COUNT(*) as total FROM users u";
if (!empty($role_id)) {
    $sqlCount .= " LEFT JOIN user_roles ur ON u.id = ur.user_id WHERE ur.role_id = :role_id";
    $statement = $pdo->prepare($sqlCount);
    $statement->bindValue(':role_id', $role_id);
} else {
    $statement = $pdo->prepare($sqlCount);
}
$statement->execute();
$total_records = $statement->fetch(PDO::FETCH_ASSOC)['total'];
// echo $total_records;
$total_pages = ceil($total_records / $page_size);
$sql = "SELECT u.*, p.* FROM users u LEFT JOIN profiles p ON u.id = p.user_id";
if (!empty($role_id)) {
    $sql .= " LEFT JOIN user_roles ur ON u.id = ur.user_id WHERE ur.role_id = :role_id_main";
} else {
    $sql .= " WHERE 1=1";
}
$sql .= " ORDER BY p.updated_at DESC LIMIT $page_size OFFSET $offset";

$statement = $pdo->prepare($sql);
if (!empty($role_id)) {
    $statement->bindValue(':role_id_main', $role_id);
}
$statement->execute();
$users = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'partials/head.php'; ?>
<div class="page-body">
    <div class="container-xl">
        <div class="d-flex mb-3 justify-content-end align-items-center gap-2">
            <label class="form-label">Select Role</label>
            <select type="text" class="form-select" placeholder="Select a role" id="select-tags" value="">
                <option value="" selected disabled></option>
                <?php foreach ($groupedRoles as $type => $items): ?>
                    <optgroup label="<?= ucfirst($type) ?>">
                        <?php foreach ($items as $item): ?>
                            <option value="<?= $item['id'] ?>" <?php if (isset($role_id) && $role_id == $item['id']):
                                  echo 'selected';
                              endif; ?>><?= ucfirst($item['role_name']); ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
            <button id="clearFilter" class="btn btn-default">Clear</button>
        </div>
        <div class="row row-cards">
            <?php foreach ($users as $user): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card" style="min-height: 370px;">
                        <div class="card-body p-4 text-center">
                            <span class="avatar avatar-xl mb-3 avatar-rounded" style="background-image: url('<?php if ($user['picture'])
                                echo CONSTANTS['site_url'] . 'uploads/' . $user['picture'];
                            else
                                echo CONSTANTS['site_url'] . 'uploads/na_logo.jpg' ?>')"></span>
                                <h3 class="m-0 mb-1"><a
                                        href="#"><?= ($user['firstname']) ? ucfirst($user['firstname']) : "<i>@" . $user['username'] . "</i><br/>" ?>
                                    <?= ($user['lastname']) ? $user['lastname'] : $user['email'] ?></a></h3>
                            <div class="text-muted">
                                <?= ($user['designation']) ? ucfirst($user['designation']) : 'Profile not updated yet.' ?>
                            </div>
                            <?php
                            $user_roles = get_user_roles($user['user_id'], $pdo);
                            $colors = ['blue', 'azure', 'indigo', 'purple', 'pink', 'red', 'orange', 'yellow', 'lime', 'green', 'teal', 'cyan', 'muted'];
                            ?>

                            <div class="mt-3">
                                <?php
                                $displayed_roles = array_slice($user_roles, 0, 3); // Get the first three records
                                $remaining_roles = array_slice($user_roles, 3); // Get the remaining records
                            
                                // Display the first three records
                                foreach ($displayed_roles as $key => $ur):
                                    ?>
                                    <span
                                        class="badge m-1 bg-<?= $colors[rand(0, count($colors) - 1)] ?>-lt"><?= ucfirst($ur) ?></span>
                                <?php endforeach; ?>

                                <!-- Button to trigger popover -->
                                <?php if (!empty($remaining_roles)): ?>
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="popover"
                                        title="Additional Roles" data-bs-trigger="hover"
                                        data-bs-content="<?= ucfirst(implode(', ', $remaining_roles)) ?>">
                                        +<?= count($remaining_roles) ?> more
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d-flex">
                            <a href="<?= "mailto:" . $user['email'] ?>"
                                class="card-btn"><!-- Download SVG icon from http://tabler-icons.io/i/mail -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-muted" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                    <polyline points="3 7 12 13 21 7" />
                                </svg>
                                Email</a>
                            <a href="<?= "tel:" . $user['phone'] ?>"
                                class="card-btn"><!-- Download SVG icon from http://tabler-icons.io/i/phone -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-muted" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                                </svg>
                                Call</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="d-flex mt-4">
            <ul class="pagination ms-auto">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="<?= "?page=" . ($page - 1) . (!empty($role_id) ? "&role_id=" . urlencode($role_id) : "") ?>"
                            tabindex="-1" aria-disabled="true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <polyline points="15 6 9 12 15 18"></polyline>
                            </svg>
                            prev
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link"
                            href="<?= "?page=" . $i . (!empty($role_id) ? "&role_id=" . urlencode($role_id) : "") ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="<?= "?page=" . ($page + 1) . (!empty($role_id) ? "&role_id=" . urlencode($role_id) : "") ?>">
                            next
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <polyline points="9 6 15 12 9 18"></polyline>
                            </svg>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<?php include 'partials/footer.php'; ?>
<script>
    // Initialize Bootstrap popover
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
</script>
<script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
        var el;
        window.TomSelect && (new TomSelect(el = document.getElementById('select-tags'), {
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
            onChange: function (e) {
                var roleId = this.getValue();
                var url = new URL(window.location.href);
                url.searchParams.set('role_id', roleId);
                window.location.href = url.href;
            }
        }));
    });
    // @formatter:on
    document.addEventListener("DOMContentLoaded", function () {
        var clearButton = document.getElementById("clearFilter");

        clearButton.addEventListener("click", function () {
            var url = new URL(window.location.href);
            url.search = "";
            window.location.href = url.href;
        });
    });
</script>
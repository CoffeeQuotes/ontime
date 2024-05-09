<?php
include '../vendor/autoload.php';
use System\Connection;

require '../functions.php';
// Define constants if not defined
if (!defined('CONSTANTS')) {
    define('CONSTANTS', require '../constants.php');
}
$pdo = Connection::getInstance();
$page_title = "Projects";
$page_desc = "Create and manage projects";
$filters = [];
// if request post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $formCategories = isset($_POST['form-categories']) ? $_POST['form-categories'] : '';
    $formPrivate = isset($_POST['form-private']) ? $_POST['form-private'] : '';
    $formPriority = isset($_POST['form-priority']) ? $_POST['form-priority'] : '';
    $formStatus = isset($_POST['form-status']) ? $_POST['form-status'] : '';
    $formClient = isset($_POST['client']) ? $_POST['client'] : '';
    $filters = [
        'category' => $formCategories,
        'access' => $formPrivate,
        'priority' => $formPriority,
        'status' => $formStatus,
        'client' => $formClient
    ];
}

$sql = "SELECT * FROM categories WHERE status='active' ORDER BY updated_at DESC;";
$statement = $pdo->prepare($sql);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM clients WHERE status='active' ORDER BY updated_at DESC;";
$statement = $pdo->prepare($sql);
$statement->execute();
$clients = $statement->fetchAll(PDO::FETCH_ASSOC);

// $sql = "SELECT * FROM projects ORDER BY updated_at DESC;";
list($sql, $params) = buildQuery($filters);
echo $sql;
// var_dump($params);
$statement = $pdo->prepare($sql);
$statement->execute($params);
$projects = $statement->fetchAll(PDO::FETCH_ASSOC);
function buildQuery($filters)
{
    $sql = "SELECT * FROM projects";
    $sqlWhere = ""; // Initialize the WHERE clause

    $params = [];

    if (!empty($filters['category'])) {
        $categoryPlaceholders = implode(',', array_fill(0, count($filters['category']), '?'));
        $sqlWhere .= " WHERE category_id IN ($categoryPlaceholders)";
        $params = array_merge($params, $filters['category']);
    }

    if (!empty($filters['access'])) {
        $sqlWhere .= " AND access = ?";
        $params[] = $filters['access'];
    }

    if (!empty($filters['priority'])) {
        $sqlWhere .= " AND priority = ?";
        $params[] = $filters['priority'];
    }

    if (!empty($filters['status'])) {
        $sqlWhere .= " AND status = ?";
        $params[] = $filters['status'];
    }

    if (!empty($filters['client'])) {
        $sqlWhere .= " AND client_id = ?";
        $params[] = $filters['client'];
    }

    // Append the WHERE clause to the SQL query
    $sql .= $sqlWhere;

    return [$sql, $params];
}

?>

<?php include 'partials/head.php'; ?>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row g-4">
            <div class="col-md-3">
                <form action="" id="filterForm" method="post" autocomplete="off" novalidate class="sticky-top">
                    <div class="form-label">Categories</div>
                    <div class="mb-4">
                        <?php foreach ($categories as $category): ?>
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" name="form-categories[]"
                                    value="<?= $category['id'] ?>" <?php
                                      if (isset($formCategories) && in_array($category['id'], $formCategories)) {
                                          echo "checked";
                                      }
                                      ?>>
                                <span class="form-check-label"><?= $category['category_name'] ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-label">Private</div>
                    <div class="mb-4">
                        <label class="form-check form-switch">
                            <input class="form-check-input" name="form-private" type="checkbox" <?php
                            if (isset($formPrivate) && $formPrivate == 'on'):
                                echo "checked";
                            endif;
                            ?>>
                            <span class="form-check-label form-check-label-on">On</span>
                            <span class="form-check-label form-check-label-off">Off</span>
                        </label>
                    </div>
                    <div class="form-label">Project priority</div>
                    <div class="mb-4">
                        <?php foreach (CONSTANTS['project_priorities'] as $key => $value): ?>
                            <label class="form-check">
                                <input type="radio" class="form-check-input" name="form-priority" value="<?= $key ?>"
                                    <?=
                                        (isset($formPriority) && $formPriority == $key) ? 'checked' : '';
                                    ?>>
                                <span class="form-check-label"><?= $value ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-label">Project status</div>
                    <div class="mb-4">
                        <?php foreach (CONSTANTS['status'] as $key => $value): ?>
                            <label class="form-check">
                                <input type="radio" class="form-check-input" name="form-status" value="<?= $key ?>"
                                    <?= (isset($formStatus) && $formStatus == $key) ? 'checked' : ''; ?>>
                                <span class="form-check-label"><?= $value ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-label">Clients</div>
                    <div class="mb-4">
                        <select class="form-select" name="client">
                            <option value="">All</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= $client['id'] ?>" <?= (isset($formClient) && $formClient == $client['id']) ? 'selected' : ''; ?>><?= $client['client_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mt-5">
                        <button class="btn btn-primary w-100">
                            Confirm changes
                        </button>
                        <button href="#" id="resetButton" class="btn btn-link w-100">
                            Reset to defaults
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-9">
                <div class="row row-cards">
                    <div class="space-y">
                        <?php foreach ($projects as $project): ?>
                            <div class="card">
                                <div class="row g-0">
                                    <div class="col-auto">
                                        <div class="card-body">
                                            <div class="avatar avatar-md"
                                                style="background-image: url(./static/jobs/job-1.jpg)"></div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card-body ps-0">
                                            <div class="row">
                                                <div class="col">
                                                    <h3 class="mb-0"><a href="#"><?= $project['project_name'] ?></a></h3>
                                                </div>
                                                <div class="col-auto fs-3 text-green">
                                                    <?= formatMoney($project['budget'], $project['currency']); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md">
                                                    <div
                                                        class="mt-3 list-inline list-inline-dots mb-0 text-muted d-sm-block d-none">
                                                        <div class="list-inline-item">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/building-community -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-bolt">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0l8 -11" />
                                                            </svg>
                                                            <?= ucfirst($project['priority']); ?>
                                                        </div>
                                                        <div class="list-inline-item">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/license -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-status-change">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M6 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                                <path d="M18 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                                <path d="M6 12v-2a6 6 0 1 1 12 0v2" />
                                                                <path d="M15 9l3 3l3 -3" />
                                                            </svg>
                                                            <?= ucfirst($project['status']) ?>
                                                        </div>
                                                        <div class="list-inline-item">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/map-pin -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-access-point">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M12 12l0 .01" />
                                                                <path d="M14.828 9.172a4 4 0 0 1 0 5.656" />
                                                                <path d="M17.657 6.343a8 8 0 0 1 0 11.314" />
                                                                <path d="M9.168 14.828a4 4 0 0 1 0 -5.656" />
                                                                <path d="M6.337 17.657a8 8 0 0 1 0 -11.314" />
                                                            </svg>
                                                            <?= ucfirst($project['access']) ?>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 list mb-0 text-muted d-block d-sm-none">
                                                        <div class="list-item">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/building-community -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline"
                                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path
                                                                    d="M8 9l5 5v7h-5v-4m0 4h-5v-7l5 -5m1 1v-6a1 1 0 0 1 1 -1h10a1 1 0 0 1 1 1v17h-8" />
                                                                <line x1="13" y1="7" x2="13" y2="7.01" />
                                                                <line x1="17" y1="7" x2="17" y2="7.01" />
                                                                <line x1="17" y1="11" x2="17" y2="11.01" />
                                                                <line x1="17" y1="15" x2="17" y2="15.01" />
                                                            </svg>
                                                            CMS Max
                                                        </div>
                                                        <div class="list-item">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/license -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline"
                                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path
                                                                    d="M15 21h-9a3 3 0 0 1 -3 -3v-1h10v2a2 2 0 0 0 4 0v-14a2 2 0 1 1 2 2h-2m2 -4h-11a3 3 0 0 0 -3 3v11" />
                                                                <line x1="9" y1="7" x2="13" y2="7" />
                                                                <line x1="9" y1="11" x2="13" y2="11" />
                                                            </svg>
                                                            Full-time
                                                        </div>
                                                        <div class="list-item">
                                                            <!-- Download SVG icon from http://tabler-icons.io/i/map-pin -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline"
                                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <circle cx="12" cy="11" r="3" />
                                                                <path
                                                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                                                            </svg>
                                                            Remote / USA
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-auto">
                                                    <div class="mt-3 badges">
                                                        <?php
                                                        $sql = "SELECT * FROM tags WHERE project_id=:project_id ORDER BY updated_at DESC;";
                                                        $statement = $pdo->prepare($sql);
                                                        $statement->bindValue(':project_id', $project['id']);
                                                        $statement->execute();
                                                        $tags = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                        $count = 0;
                                                        ?>
                                                        <?php foreach ($tags as $tag): ?>
                                                            <?php if ($count < 3): ?>
                                                                <a href="javascript:void(0)"
                                                                    class="badge badge-outline text-muted border fw-normal badge-pill"><?= ucfirst($tag['name']); ?></a>
                                                                <?php $count++; else: ?>
                                                                <a href="javascript:void(0)"
                                                                    class="badge badge-outline text-muted border fw-normal badge-pill">...</a>
                                                                <?php break; endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'partials/footer.php'; ?>

<script>
    document.getElementById('resetButton').addEventListener('click', function (event) {
        event.preventDefault(); // Prevent the default link behavior

        // Reset the form
        document.getElementById('filterForm').reset();

        // Clear the variables
        window.location.href = "<?php echo $_SERVER['PHP_SELF']; ?>";
    });
</script>
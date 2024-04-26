<?php
require 'vendor/autoload.php';
require 'functions.php';
use System\Connection;
use System\SessionManager;

$session = new SessionManager();
$pdo = Connection::getInstance();

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

$sql = 'SELECT * FROM tasks WHERE public=:public ORDER BY updated_at DESC;';
$statement = $pdo->prepare($sql);
$statement->bindValue(':public', true, PDO::PARAM_BOOL);
$statement->execute();
$tasks = $statement->fetchAll(PDO::FETCH_ASSOC);

// print_r($tasks);
?>
<?php include 'partials/header.php'; ?>
<div class="center-50">
    <div class="flex flex-column">
        <?php foreach ($tasks as $task): ?>
            <div class="public-task">
                <h2><?= $task['title'] ?></h2>
                <p><?php echo createExcerpt($task["description"]); ?></p>
                <div class="flex justify-content-between">
                    <div class="pswp-gallery flex" id="gallery">
                        <?php
                        $sql = "SELECT * FROM task_assets WHERE task_id=:task_id ORDER BY updated_at ASC;";
                        $statement = $pdo->prepare($sql);
                        $statement->bindValue(':task_id', $task['id']);
                        $statement->execute();
                        $task_assets = $statement->fetchAll(PDO::FETCH_ASSOC);
                        if (count($task_assets) > 0) {
                            foreach ($task_assets as $asset) {
                                if ($asset['type'] == 'image/jpeg' || $asset['type'] == 'image/png') {
                                    $uploadsDirectory = __DIR__ . "/uploads/";
                                    $imageFilename = $asset["location"]; // Construct the full path to the image
                                    $imagePath = $uploadsDirectory . $imageFilename; // Get the dimensions of the image
                                    $imageSize = getimagesize($imagePath);
                                    if ($imageSize !== false) {
                                        $width = $imageSize[0];
                                        // Width of the image
                                        $height = $imageSize[1];
                                        // Height of the image
                                    }
                                    ?>
                                    <div class="pswp-gallery__item">
                                        <a href="<?php echo CONSTANTS["site_url"] .
                                            "uploads/" .
                                            $asset["location"]; ?>" data-pswp-width="<?= $width ?>"
                                            data-pswp-height="<?= $height ?>" target="_blank">
                                            <img src="<?php echo CONSTANTS["site_url"] .
                                                "uploads/" .
                                                $asset["location"]; ?>" width="200" />
                                        </a>
                                        <div class="pswp-caption-content">
                                            <h2><?= $asset["caption"] ?></h2>
                                            <p><?= $asset["description"] ?></p>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script type="module">
    import PhotoSwipeLightbox from 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.2/photoswipe-lightbox.esm.min.js';
    import PhotoSwipeDynamicCaption from 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe-dynamic-caption-plugin/1.2.7/photoswipe-dynamic-caption-plugin.esm.js';

    const smallScreenPadding = {
        top: 0, bottom: 0, left: 0, right: 0
    };
    const largeScreenPadding = {
        top: 30, bottom: 30, left: 0, right: 0
    };
    const lightbox = new PhotoSwipeLightbox({
        gallerySelector: '#gallery',
        childSelector: '.pswp-gallery__item',

        // optionaly adjust viewport
        paddingFn: (viewportSize) => {
            return viewportSize.x < 700 ? smallScreenPadding : largeScreenPadding
        },
        pswpModule: () => import('https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.4.2/photoswipe.esm.min.js')
    });

    const captionPlugin = new PhotoSwipeDynamicCaption(lightbox, {
        mobileLayoutBreakpoint: 700,
        type: 'auto',
        mobileCaptionOverlapRatio: 1
    });
    lightbox.on('uiRegister', function () {
        lightbox.pswp.ui.registerElement({
            name: 'download-button',
            order: 8,
            isButton: true,
            tagName: 'a',

            // SVG with outline
            html: {
                isCustomSVG: true,
                inner: '<path d="M20.5 14.3 17.1 18V10h-2.2v7.9l-3.4-3.6L10 16l6 6.1 6-6.1ZM23 23H9v2h14Z" id="pswp__icn-download"/>',
                outlineID: 'pswp__icn-download'
            },

            // Or provide full svg:
            // html: '<svg width="32" height="32" viewBox="0 0 32 32" aria-hidden="true" class="pswp__icn"><path d="M20.5 14.3 17.1 18V10h-2.2v7.9l-3.4-3.6L10 16l6 6.1 6-6.1ZM23 23H9v2h14Z" /></svg>',

            // Or provide any other markup:
            // html: '<i class="fa-solid fa-download"></i>' 

            onInit: (el, pswp) => {
                el.setAttribute('download', '');
                el.setAttribute('target', '_blank');
                el.setAttribute('rel', 'noopener');

                pswp.on('change', () => {
                    console.log('change');
                    el.href = pswp.currSlide.data.src;
                });
            }
        });
    });
    lightbox.init();
</script>
<?php include 'partials/footer.php'; ?>
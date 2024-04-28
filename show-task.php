<?php
require "vendor/autoload.php";
use System\Connection;
use System\SessionManager;

$session = new SessionManager();
require "functions.php";
if (!defined("CONSTANTS")) {
    define("CONSTANTS", require "constants.php");
}
if (!$session->get('logged_user')) {
    header("Location: " . CONSTANTS['site_url'] . "login.php");
}
if ($session->get('profile_incomplete')) {
    header("Location: " . CONSTANTS['site_url'] . "complete-profile.php");
}
$task_id = isset($_GET["task_id"]) ? intval($_GET["task_id"]) : 0;
if ($task_id === 0) {
    // Redirect to tasks
    $session->set("failed", "Error: Task not found!");
    header("Location: " . CONSTANTS["site_url"] . "tasks.php");
}
$pdo = Connection::getInstance();
$sql = 'SELECT tasks.*, task_assets.caption, task_assets.location, task_assets.description as asset_description, task_assets.type, task_assets.size 
FROM tasks 
LEFT JOIN task_assets ON tasks.id = task_assets.task_id 
WHERE tasks.id = :task_id AND tasks.public = :public;';
$statement = $pdo->prepare($sql);
$statement->bindValue(":task_id", $task_id, PDO::PARAM_INT);
$statement->bindValue(":public", true, PDO::PARAM_INT);
$statement->execute();
$task = $statement->fetchAll(PDO::FETCH_ASSOC);
if (!count($task) > 0) {
    $session->set("failed", "Error: Task not found.");
    header("Location: " . CONSTANTS["site_url"] . "tasks.php");
}
$sql =
    "SELECT task_comments.*, users.username  FROM task_comments LEFT JOIN users ON task_comments.user_id = users.id  WHERE task_id=:task_id ORDER BY updated_at DESC";
$statement = $pdo->prepare($sql);
$statement->bindValue(":task_id", $task_id);
$statement->execute();
$comments = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include "partials/header.php"; ?>
<div class="center-50">
    <h1><?= $task[0]["title"] ?></h1>
    <p><?= $task[0]["description"] ?></p>
    <p><small> <b>Task deadline : </b><i> <?= date(
        "F j, Y, g:i a",
        strtotime($task[0]["deadline"])
    ) ?></i></small> </p>
    <?php if (
        $task[0]["location"] &&
        ($task[0]["type"] == "image/jpeg" ||
            $task[0]["type"] == "image/png")
    ): ?>
        <h3>Assets</h3>
        <div class="pswp-gallery flex" id="gallery">
            <?php foreach ($task as $asset):
                if (
                    $asset["type"] == "image/png" ||
                    $asset["type"] == "image/jpeg"
                ):
                    // Path to the image file
        
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
                            $asset["location"]; ?>" data-pswp-width="<?= $width ?>" data-pswp-height="<?= $height ?>"
                            target="_blank">
                            <img src="<?php echo CONSTANTS["site_url"] .
                                "uploads/" .
                                $asset["location"]; ?>" width="200" />
                        </a>
                        <div class="pswp-caption-content">
                            <h2><?= $asset["caption"] ?></h2>
                            <p><?= $asset["asset_description"] ?></p>
                        </div>
                    </div>

                    <?php
                endif;
            endforeach; ?>
        </div>
    <?php endif; ?>

    <h2>Comments</h2>
    <div>
        <form id="commentForm">
            <input type="hidden" name="task_id" id="task_id" value="<?= $task_id ?>" />
            <textarea id="comment_text" name="comment_text" style="width: 100%;"></textarea>
            <input type="hidden" name="comment_id" id="comment_id" value="0" />
            <button type="submit" id="postComment">Post</button>
        </form>
        <span id="comment_message"></span>
        <br />
    </div>
    <div id="display_comment"></div>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('commentForm').addEventListener('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            fetch('add-task-comment.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success !== '') {
                        // simplemde.codemirror.reset();
                        document.getElementById('commentForm').reset();
                        simplemde.value("");
                        document.getElementById('comment_message').innerHTML = data.success;
                        document.getElementById('comment_id').value = 0;
                        loadComment();
                    }
                })
                .catch(error => {
                    // console.error('Error:', error);
                });
        });

        loadComment();

        function loadComment() {
            task_id = document.getElementById('task_id').value;
            data = { task_id: task_id };
            fetch('fetch-task-comment.php', {
                method: 'POST',
                body: JSON.stringify(data)
            })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('display_comment').innerHTML = data;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('reply')) {
                var commentId = event.target.id;
                document.getElementById('comment_id').value = commentId;
                simplemde.codemirror.focus();
            }
        });
    });
    var simplemde = new SimpleMDE({ element: document.getElementById("comment_text") });

</script>
<?php include "partials/footer.php"; ?>
<?php require 'vendor/autoload.php'; ?>
<?php use System\SessionManager; ?>
<?php $session = new SessionManager();
// Define constants if not defined

if (!defined('CONSTANTS')) {
    define('CONSTANTS', require 'constants.php');
}
if (!$session->get('logged_user')) {
    header("Location: " . CONSTANTS['site_url'] . "login.php");
}
if ($session->get("success")) {
    $success = $session->get("success");
    $session->delete("success");
}
if ($session->get("failed")) {
    $failed = $session->get("failed");
    $session->delete("failed");
}
if ($session->get("task_id")) {
    $task_id = $session->get("task_id");
    $session->delete("task_id");
}
if ($session->get("errors")) {
    $errors = $session->get("errors");
    $session->delete("errors");
}
?>
<?php include 'partials/header.php'; ?>
<?php
if (isset($errors)):
    ?>
    <ul class="error-alert">
        <?php
        foreach ($errors as $error): ?>
            <li><?= $error ?></li>
        <?php
        endforeach;
        ?>
    </ul>
    <?php
endif;
?>
<p class="success-alert"><?php echo $success ?? ""; ?></p>
<p class="error-alert"><?php echo $failed ?? ""; ?></p>
<form method="post" action="process-task-assets.php" enctype="multipart/form-data" class="upload-task-form">
    <input type="hidden" name="task_id" value="<?= $task_id ?? 11 ?>">
    <div id="uploadForm">
        <fieldset>
            <legend>Upload task assets</legend>
            <div class="form-row">
                <label for="location">Choose Photo/Media</label>
                <input type="file" name="location[]" class="file-input" onchange="displayFileName(this)"
                    accept="image/*, video/*, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
                <div class="file-name-display"></div>
            </div>
            <div class="form-row">
                <label for="caption">Caption</label>
                <input type="text" name="caption[]" />
            </div>
            <div class="form-row">
                <label for="description">Description</label>
                <input type="text" name="description[]" />
            </div>
        </fieldset>
    </div>
    <div class="p-10">
        <a class="button small-button dark-button" id="addMore"> <i class="fa fa-plus"></i></a>
        <input class="button small-button primary-button" type="submit" value="Upload" />
    </div>
</form>
<div class="p-10">
</div>
<?php include 'partials/footer.php'; ?>

<script>
    document.getElementById('addMore').addEventListener('click', function () {
        var form = document.getElementById('uploadForm');
        var fieldset = form.querySelector('fieldset');

        // Clone the fieldset
        var clonedFieldset = fieldset.cloneNode(true);

        // Reset values in the cloned fieldset
        var inputs = clonedFieldset.querySelectorAll('input');
        inputs.forEach(function (input) {
            input.value = '';
        });

        // Create and append a delete button
        var deleteButton = document.createElement('button');
        deleteButton.innerHTML = "<i class='fa fa-trash'></i>";
        deleteButton.type = 'button';
        deleteButton.addEventListener('click', function () {
            form.removeChild(clonedFieldset);
        });
        clonedFieldset.appendChild(deleteButton);

        // Append the cloned fieldset to the form
        form.appendChild(clonedFieldset);
    });

    function displayFileName(input) {
        var files = input.files;
        var parentFieldset = input.closest('fieldset');
        var fileNameDisplay = parentFieldset.querySelector(".file-name-display");
        fileNameDisplay.innerHTML = ""; // Clear previous content

        if (files.length > 0) {
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var iconClass = getFileIconClass(file.name);
                var fileTypeIcon = `<i class="${iconClass}"></i>`;
                fileNameDisplay.innerHTML += `<div>${fileTypeIcon} ${file.name}</div>`;
            }
        } else {
            fileNameDisplay.textContent = "No file selected";
        }
    }

    function getFileIconClass(fileName) {
        var extension = fileName.split('.').pop().toLowerCase();
        switch (extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
                return 'fa fa-file-image-o'; // Example icon for image files
            case 'mp4':
            case 'avi':
            case 'mov':
                return 'fa fa-file-video-o'; // Example icon for video files
            case 'pdf':
                return 'fa fa-file-pdf-o'; // Example icon for PDF files
            case 'doc':
            case 'docx':
                return 'fa fa-file-word-o'; // Example icon for Word documents
            default:
                return 'fa fa-file-o'; // Default icon for other file types
        }
    }

</script>
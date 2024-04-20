<?php
// Serve file
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads';

$filePath = $uploadDir . '/'.$asset['location'];
print_r($filePath);
if (file_exists($filePath)) {
    header('Content-Type: ' . mime_content_type($filePath));
    readfile($filePath);
    exit;
} else {
    http_response_code(404);
    exit('File not found');
}

<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
if (preg_match('#^/(attached_assets|database|includes|scripts|\.git|\.local|\.agents)(/|$)#', $path)) {
    http_response_code(404);
    exit;
}
if ($path !== '/' && is_file(__DIR__ . $path)) {
    return false;
}
require __DIR__ . '/index.php';

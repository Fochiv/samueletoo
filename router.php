<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
if (preg_match('#^/(attached_assets|database|includes|scripts|\.git|\.local|\.agents)(/|$)#', $path)) {
    http_response_code(404);
    exit;
}
$adminRoutes = [
    '/admin' => 'admin',
    '/admin/' => 'admin',
    '/admin/index.php' => 'admin',
    '/admin/login.php' => 'admin-login',
    '/admin/logout.php' => 'admin-logout',
    '/admin/applications.php' => 'admin-applications',
    '/admin/application.php' => 'admin-application',
    '/admin/settings.php' => 'admin-settings',
    '/admin/testimonials.php' => 'admin-testimonials',
];
if (isset($adminRoutes[$path])) {
    $_GET['page'] = $adminRoutes[$path];
    require __DIR__ . '/index.php';
    exit;
}
if (preg_match('#^/admin(?:/|$)#', $path)) {
    http_response_code(404);
    exit;
}
if ($path !== '/' && is_file(__DIR__ . $path)) {
    return false;
}
require __DIR__ . '/index.php';

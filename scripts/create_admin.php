<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$localConfigPath = __DIR__ . '/../includes/config.local.php';
if (is_file($localConfigPath)) {
    $localConfig = require $localConfigPath;
    if (is_array($localConfig)) {
        foreach (['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'] as $configKey) {
            if (isset($localConfig[$configKey]) && getenv($configKey) === false) {
                putenv($configKey . '=' . (string)$localConfig[$configKey]);
            }
        }
    }
}

$host = getenv('DB_HOST') ?: '';
$name = getenv('DB_NAME') ?: '';
$user = getenv('DB_USER') ?: '';
$email = strtolower(trim((string)(getenv('ADMIN_EMAIL') ?: '')));
$password = (string)(getenv('ADMIN_PASSWORD') ?: '');
$display = trim((string)(getenv('ADMIN_NAME') ?: 'Administrateur'));

if (!$host || !$name || !$user || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 14) {
    fwrite(STDERR, "Configurez DB_HOST, DB_NAME, DB_USER, DB_PASSWORD, ADMIN_EMAIL et ADMIN_PASSWORD (14 caractères minimum) dans l’environnement.\n");
    exit(1);
}

$pdo = new PDO(
    sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $host, $name),
    $user,
    getenv('DB_PASSWORD') ?: '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]
);
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO admins (email,password_hash,display_name) VALUES (?,?,?) ON DUPLICATE KEY UPDATE password_hash=VALUES(password_hash),display_name=VALUES(display_name)');
$stmt->execute([$email, $hash, $display]);
fwrite(STDOUT, "Compte administrateur initialisé pour {$email}.\n");

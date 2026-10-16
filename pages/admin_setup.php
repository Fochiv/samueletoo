<?php
$dbReady = false;
$adminExists = false;
try {
    $adminExists = (bool)db()->query('SELECT id FROM admins LIMIT 1')->fetchColumn();
    $dbReady = true;
} catch (Throwable) {
    $dbReady = false;
}

if ($adminExists) {
    redirect(url('admin-login'));
}

$adminEmail = strtolower(trim((string)(getenv('ADMIN_EMAIL') ?: '')));
$adminPassword = (string)(getenv('ADMIN_PASSWORD') ?: '');
$credentialsReady = filter_var($adminEmail, FILTER_VALIDATE_EMAIL) && strlen($adminPassword) >= 14;
$flash = get_flash();
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><base href="/"><title>Initialiser l’administration — Fondation Samuel Eto’o</title><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"><link rel="stylesheet" href="assets/css/app.css"></head><body class="admin-login-page">
<main class="login-card">
  <a class="brand login-brand" href="index.php"><img src="logo.png" alt=""><span>FONDATION SAMUEL ETO’O</span></a>
  <p class="eyebrow eyebrow-dark"><span></span>PREMIÈRE CONNEXION</p>
  <h1>Initialiser l’administration</h1>
  <p>Cette page crée le compte administrateur configuré pour ce site. Elle sera désactivée dès qu’un compte existe.</p>
  <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div><?php endif; ?>
  <?php if (!$dbReady): ?>
    <div class="alert alert-error">La base MySQL n’est pas prête. Configurez les variables DB_HOST, DB_NAME, DB_USER et DB_PASSWORD, puis importez database/schema.sql.</div>
  <?php elseif (!$credentialsReady): ?>
    <div class="alert alert-error">Configurez ADMIN_EMAIL et ADMIN_PASSWORD dans les secrets de l’hébergement. Le mot de passe doit contenir au moins 14 caractères.</div>
  <?php else: ?>
    <div class="alert alert-success">Le compte sera créé pour : <?= e($adminEmail) ?>. Le mot de passe reste dans les secrets et sera enregistré sous forme de hash.</div>
  <?php endif; ?>
  <form method="post" action="<?= e(url('admin-setup')) ?>"><?= csrf_field() ?><input type="hidden" name="action" value="admin_setup"><button class="button button-green button-wide" type="submit" <?= (!$dbReady || !$credentialsReady) ? 'disabled' : '' ?>>Créer le compte administrateur <i class="bi bi-arrow-right"></i></button></form>
  <a class="login-back" href="<?= e(url('admin-login')) ?>"><i class="bi bi-arrow-left"></i> Retour à la connexion</a>
</main></body></html>

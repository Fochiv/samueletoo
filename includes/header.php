<?php
$lang = $_SESSION['lang'] ?? 'fr';
$flash = get_flash();
?>
<!doctype html>
<html lang="<?= e($lang) ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#211f3b">
  <meta name="description" content="<?= e(tr('Programme d’accompagnement de projets de la Fondation Samuel Eto’o. Présentez votre projet et suivez votre candidature.', 'Samuel Eto’o Foundation project support programme. Submit a project and track your application.')) ?>">
  <title><?= e(tr('Fondation Samuel Eto’o — Programme projets', 'Samuel Eto’o Foundation — Projects programme')) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
<header class="site-header">
  <div class="container nav-wrap">
    <a class="brand" href="index.php" aria-label="Accueil Fondation Samuel Eto’o">
      <img src="logo.png" alt="" class="brand-mark">
      <span><?= e(tr('FONDATION SAMUEL ETO’O', 'SAMUEL ETO’O FOUNDATION')) ?></span>
    </a>
    <nav class="main-nav">
      <a href="<?= e(url('home')) ?>"><?= e(tr('Accueil', 'Home')) ?></a>
      <a href="<?= e(url('about')) ?>"><?= e(tr('La fondation', 'The foundation')) ?></a>
      <a href="<?= e(url('program')) ?>"><?= e(tr('Le programme', 'Programme')) ?></a>
      <a href="<?= e(url('criteria')) ?>"><?= e(tr('Critères', 'Eligibility')) ?></a>
      <a href="<?= e(url('faq')) ?>">FAQ</a>
      <a href="<?= e(url('contact')) ?>"><?= e(tr('Contact', 'Contact')) ?></a>
      <a class="nav-track" href="<?= e(url('track')) ?>"><?= e(tr('Suivre mon dossier', 'Track application')) ?></a>
    </nav>
    <div class="header-actions">
      <a class="lang-switch" href="<?= e(url($_GET['page'] ?? 'home', ['lang' => $lang === 'fr' ? 'en' : 'fr'])) ?>" lang="<?= $lang === 'fr' ? 'en' : 'fr' ?>" aria-label="<?= e(tr('Passer en anglais', 'Switch to French')) ?>"><?= $lang === 'fr' ? 'EN' : 'FR' ?></a>
      <button class="menu-toggle" aria-label="<?= e(tr('Ouvrir le menu', 'Open menu')) ?>" aria-expanded="false"><i class="bi bi-list"></i></button>
    </div>
  </div>
</header>
<?php if ($flash): ?>
  <div class="container flash-wrap"><div class="alert alert-<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div></div>
<?php endif; ?>
<main>

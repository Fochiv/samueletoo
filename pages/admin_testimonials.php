<?php
$items = db()->query('SELECT id, display_name, role, quote, is_published, sort_order FROM testimonials ORDER BY sort_order, id DESC')->fetchAll();
$flash = get_flash();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><base href="/">
  <title>Témoignages — Fondation Samuel Eto’o</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/app.css">
</head>
<body class="admin-body">
  <aside class="admin-sidebar">
    <a class="brand brand-light" href="index.php"><img src="logo.png" alt=""><span>FONDATION <b>SAMUEL ETO’O</b></span></a>
    <nav>
      <a href="<?= e(url('admin')) ?>"><i class="bi bi-grid"></i> Tableau de bord</a>
      <a href="<?= e(url('admin-applications')) ?>"><i class="bi bi-folder2-open"></i> Candidatures</a>
      <a class="current" href="<?= e(url('admin-testimonials')) ?>"><i class="bi bi-chat-quote"></i> Témoignages</a>
      <a href="<?= e(url('admin-settings')) ?>"><i class="bi bi-gear"></i> Paramètres</a>
      <a href="index.php"><i class="bi bi-box-arrow-up-right"></i> Voir le site</a>
    </nav>
    <a class="admin-logout" href="<?= e(url('admin-logout')) ?>"><i class="bi bi-box-arrow-left"></i> Déconnexion</a>
  </aside>
  <main class="admin-main">
    <header class="admin-top"><div><p class="eyebrow eyebrow-dark"><span></span>CONTENU DU SITE</p><h1>Témoignages</h1><p>Publiez uniquement des témoignages authentiques avec l’accord explicite des personnes concernées.</p></div></header>
    <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
    <form class="admin-panel testimonial-editor" method="post" action="<?= e(url('admin-testimonials')) ?>">
      <?= csrf_field() ?><input type="hidden" name="action" value="admin_save_testimonial">
      <h2>Ajouter un témoignage</h2>
      <div class="form-grid">
        <label class="field">Nom affiché<input name="display_name" maxlength="120" required></label>
        <label class="field">Rôle ou projet (facultatif)<input name="role" maxlength="150"></label>
        <label class="field full-field">Témoignage<textarea name="quote" rows="4" maxlength="1500" required></textarea></label>
        <label class="field">Ordre d’affichage<input type="number" name="sort_order" min="0" value="0"></label>
        <label class="publish-check"><input type="checkbox" name="is_published"> Publier sur la page d’accueil</label>
      </div>
      <button class="button button-green" type="submit">Ajouter <i class="bi bi-plus-lg"></i></button>
    </form>
    <section class="testimonial-admin-list">
      <?php if (!$items): ?>
        <div class="admin-panel empty-state"><i class="bi bi-chat-square-text"></i><h3>Aucun témoignage</h3><p>Les témoignages publiés s’afficheront sur la page d’accueil.</p></div>
      <?php endif; ?>
      <?php foreach ($items as $item): ?>
        <article class="admin-panel testimonial-edit-card">
          <div class="testimonial-edit-heading"><h2><?= e($item['display_name']) ?></h2><span class="status-pill"><?= $item['is_published'] ? 'Publié' : 'Brouillon' ?></span></div>
          <form method="post" action="<?= e(url('admin-testimonials')) ?>" class="testimonial-editor">
            <?= csrf_field() ?><input type="hidden" name="action" value="admin_save_testimonial"><input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
            <div class="form-grid">
              <label class="field">Nom affiché<input name="display_name" maxlength="120" value="<?= e($item['display_name']) ?>" required></label>
              <label class="field">Rôle ou projet<input name="role" maxlength="150" value="<?= e($item['role']) ?>"></label>
              <label class="field full-field">Témoignage<textarea name="quote" rows="4" maxlength="1500" required><?= e($item['quote']) ?></textarea></label>
              <label class="field">Ordre d’affichage<input type="number" name="sort_order" min="0" value="<?= (int)$item['sort_order'] ?>"></label>
              <label class="publish-check"><input type="checkbox" name="is_published" <?= $item['is_published'] ? 'checked' : '' ?>> Publier sur la page d’accueil</label>
            </div>
            <div class="testimonial-actions"><button class="button button-green" type="submit">Enregistrer</button></form>
            <form method="post" action="<?= e(url('admin-testimonials')) ?>" onsubmit="return confirm('Supprimer ce témoignage ?')"><?= csrf_field() ?><input type="hidden" name="action" value="admin_delete_testimonial"><input type="hidden" name="id" value="<?= (int)$item['id'] ?>"><button class="button button-quiet" type="submit"><i class="bi bi-trash"></i> Supprimer</button></form></div>
        </article>
      <?php endforeach; ?>
    </section>
  </main>
</body>
</html>

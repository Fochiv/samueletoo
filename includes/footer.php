<?php $settings = site_settings(); ?>
</main>
<footer class="site-footer">
  <div class="container footer-main">
    <div class="footer-brand">
      <a class="brand brand-light" href="index.php"><img src="logo.png" alt=""><span>FONDATION <b>SAMUEL ETO’O</b></span></a>
      <p><?= e(tr('Accompagner les idées qui créent des opportunités durables.', 'Supporting ideas that create lasting opportunities.')) ?></p>
      <nav class="footer-socials" aria-label="<?= e(tr('Réseaux sociaux', 'Social media')) ?>">
        <?php if (!empty($settings['whatsapp'])): $footerWhatsapp = preg_replace('/\D+/', '', $settings['whatsapp']); ?>
          <a href="https://wa.me/<?= e($footerWhatsapp) ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp — <?= e($settings['whatsapp']) ?>" title="WhatsApp"><i class="bi bi-whatsapp" aria-hidden="true"></i></a>
        <?php endif; ?>
        <?php if (!empty($settings['facebook_url'])): ?>
          <a href="<?= e($settings['facebook_url']) ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook" title="Facebook"><i class="bi bi-facebook" aria-hidden="true"></i></a>
        <?php endif; ?>
        <?php if (!empty($settings['x_url'])): ?>
          <a href="<?= e($settings['x_url']) ?>" target="_blank" rel="noopener noreferrer" aria-label="X" title="X"><i class="bi bi-twitter-x" aria-hidden="true"></i></a>
        <?php endif; ?>
      </nav>
    </div>
    <div><h3><?= e(tr('Explorer', 'Explore')) ?></h3><a href="<?= e(url('about')) ?>"><?= e(tr('La fondation', 'The foundation')) ?></a><a href="<?= e(url('program')) ?>"><?= e(tr('Le programme', 'Programme')) ?></a><a href="<?= e(url('criteria')) ?>"><?= e(tr('Critères', 'Eligibility')) ?></a></div>
    <div><h3><?= e(tr('Candidature', 'Application')) ?></h3><a href="<?= e(url('apply')) ?>"><?= e(tr('Soumettre un projet', 'Submit a project')) ?></a><a href="<?= e(url('track')) ?>"><?= e(tr('Suivre un dossier', 'Track an application')) ?></a><a href="<?= e(url('faq')) ?>">FAQ</a></div>
    <div class="footer-cta"><h3><?= e(tr('Un projet en tête ?', 'Have a project in mind?')) ?></h3><p><?= e(tr('Découvrez le programme et préparez votre candidature.', 'Explore the programme and prepare your application.')) ?></p><a class="button button-green" href="<?= e(url('apply')) ?>"><?= e(tr('Présenter mon projet', 'Present my project')) ?> <i class="bi bi-arrow-up-right"></i></a></div>
  </div>
  <div class="container footer-bottom"><span>© <?= date('Y') ?> Fondation Samuel Eto’o — <?= e(tr('Tous droits réservés', 'All rights reserved')) ?></span></div>
</footer>
<button class="back-to-top" type="button" aria-label="<?= e(tr('Retour en haut de page', 'Back to top')) ?>" aria-hidden="true" tabindex="-1"><i class="bi bi-arrow-up"></i></button>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="assets/js/app.js" defer></script>
</body>
</html>

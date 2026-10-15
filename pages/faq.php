<section class="page-hero"><div class="container"><p class="eyebrow"><span></span>FAQ</p><h1><?= e(tr('Questions fréquentes.', 'Frequently asked questions.')) ?></h1><p><?= e(tr('Les informations essentielles avant de présenter votre projet.', 'Key information before you present your project.')) ?></p></div></section>
<section class="section section-paper"><div class="container faq-list"><?php
$faqs = [
['Qui peut candidater ?','Entrepreneurs, porteurs de projets, PME, startups, artisans, agriculteurs et certaines associations peuvent présenter un dossier, sous réserve des critères du programme.'],
['Quels projets sont concernés ?','Le programme reçoit des projets dans plusieurs domaines, notamment l’agriculture, l’entrepreneuriat, l’innovation, la santé, l’éducation et l’impact social.'],
['Combien peut-on demander ?','Le montant demandé doit correspondre à un besoin expliqué et à un budget cohérent. Les limites éventuelles dépendent des règles du programme en vigueur.'],
['Comment les dossiers sont-ils étudiés ?','Les dossiers sont examinés selon les critères, la faisabilité, l’impact et les ressources disponibles. La soumission ne garantit aucun financement.'],
['Combien de temps dure l’étude ?','Le délai peut varier selon le nombre de candidatures et les vérifications nécessaires. Le statut peut être consulté avec le numéro de dossier.'],
['Comment suivre sa candidature ?','Utilisez la page « Suivre ma candidature » avec le numéro de dossier et l’e-mail ou le téléphone fourni lors de la soumission.'],
['Comment serai-je contacté ?','Les coordonnées fournies dans le formulaire pourront être utilisées pour communiquer au sujet de votre candidature.'],
['Le dépôt d’une candidature est-il payant ?','Le formulaire de candidature sur cette plateforme est gratuit. Méfiez-vous de toute demande de paiement pour garantir un financement.'],
['Puis-je modifier mon dossier ?','Pour demander une correction après l’envoi, contactez l’équipe en indiquant votre numéro de dossier.'],
];
foreach ($faqs as $i => [$q,$a]): ?><details class="faq-item"><summary><span><?= e(tr($q,$q)) ?></span><i class="bi bi-plus"></i></summary><p><?= e(tr($a,$a)) ?></p></details><?php endforeach; ?>
</div></section>

# Plateforme — Fondation Samuel Eto’o

Application PHP/MySQL pensée pour PHP + MySQL + Apache (hébergement mutualisé) et exécutable localement avec le serveur PHP intégré.

## Lancer l’aperçu sur Replit

Le site public peut s’afficher sans base configurée. Les formulaires, le suivi et l’administration nécessitent une base MySQL.

## Installation MySQL

1. Créez une base MySQL UTF-8 sur l’hébergement.
2. Importez `database/schema.sql` dans cette base.
3. Définissez `DB_HOST`, `DB_NAME`, `DB_USER` et `DB_PASSWORD` dans les variables d’environnement ou les secrets de l’hébergement. Ne publiez jamais ces valeurs dans le code. Sur Replit, les secrets de production se règlent dans **Publishing → Adjust settings → Secrets**; republiez après les avoir modifiés.
4. Définissez `ADMIN_EMAIL` et `ADMIN_PASSWORD` dans les secrets de l’hébergement. Le mot de passe doit contenir au moins 14 caractères; `ADMIN_NAME` est facultatif. N’inscrivez pas de mot de passe dans le code.
5. Après le déploiement, ouvrez `/admin/setup.php` et cliquez sur **Créer le compte administrateur**. La page n’est utilisable que si la base est prête, qu’aucun administrateur n’existe et que les identifiants sont configurés. Le mot de passe est haché avec `password_hash()` avant d’être stocké en base.
6. Une fois le compte créé, `/admin/setup.php` redirige vers la connexion. Connectez-vous sur `/admin/login.php` (ou `/admin/`) avec `ADMIN_EMAIL` et le mot de passe configuré. Vous pouvez ensuite supprimer le secret `ADMIN_PASSWORD`; le hash du compte reste en base.

Sur Hostinger, définissez le répertoire public du site sur la racine de ce projet et vérifiez que PHP 8.1+ ainsi que PDO MySQL sont activés. Si votre offre ne permet pas les variables d’environnement, créez `includes/config.local.php` (fichier ignoré par Git, avec des permissions restreintes) qui retourne un tableau associatif contenant les clés `DB_HOST`, `DB_NAME`, `DB_USER` et `DB_PASSWORD`.

L’espace d’administration utilise les chemins `/admin/`, `/admin/login.php`, `/admin/setup.php`, `/admin/logout.php`, `/admin/applications.php`, `/admin/application.php`, `/admin/settings.php` et `/admin/testimonials.php`. L’initialisation depuis le navigateur remplace toute commande de terminal pour la première connexion. `scripts/create_admin.php` reste disponible comme méthode alternative pour les administrateurs techniques.

## À savoir

- L’application utilise des requêtes PDO préparées, des jetons CSRF, l’échappement HTML, des sessions HTTP-only, une expiration de session et une vérification des mots de passe hachés.
- La page de confidentialité est une base de travail : faites-la vérifier et adaptez-la à la politique officielle avant toute collecte réelle.
- Les coordonnées WhatsApp, téléphone, adresse et réseaux sociaux sont configurables depuis l’administration.
- L’espace d’administration comprend des filtres et un export CSV, l’historique des changements, des graphiques issus des candidatures enregistrées, le suivi des montants approuvés et la publication de témoignages authentiques.
- Les témoignages ne sont pas préremplis : ne publiez que des citations réelles avec l’autorisation de la personne concernée.
- Les textes évitent de promettre qu’une candidature donnera droit à un financement.

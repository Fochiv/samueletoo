# Plateforme — Fondation Samuel Eto’o

Application PHP/MySQL pensée pour PHP + MySQL + Apache (hébergement mutualisé) et exécutable localement avec le serveur PHP intégré.

## Lancer l’aperçu sur Replit

Le site public peut s’afficher sans base configurée. Les formulaires, le suivi et l’administration nécessitent une base MySQL.

## Installation MySQL

1. Créez une base MySQL UTF-8 sur l’hébergement.
2. Importez `database/schema.sql` dans cette base.
3. Définissez `DB_HOST`, `DB_NAME`, `DB_USER` et `DB_PASSWORD` comme variables d’environnement de l’hébergement. Ne publiez jamais le fichier contenant ces valeurs.
4. Pour initialiser un administrateur, définissez temporairement `ADMIN_EMAIL`, `ADMIN_PASSWORD` (14 caractères minimum) et éventuellement `ADMIN_NAME`, puis exécutez `php scripts/create_admin.php` depuis le terminal. Le mot de passe est enregistré avec `password_hash()`. Aucun mot de passe par défaut n’est livré.
5. Supprimez les variables d’environnement de création d’administrateur après l’initialisation.

Sur Hostinger, définissez le répertoire public du site sur la racine de ce projet et vérifiez que PHP 8.1+ ainsi que PDO MySQL sont activés. Si votre offre ne permet pas les variables d’environnement, créez `includes/config.local.php` (fichier ignoré par Git, avec des permissions restreintes) qui retourne un tableau associatif contenant les clés `DB_HOST`, `DB_NAME`, `DB_USER` et `DB_PASSWORD`.

## À savoir

- L’application utilise des requêtes PDO préparées, des jetons CSRF, l’échappement HTML, des sessions HTTP-only, une expiration de session et une vérification des mots de passe hachés.
- La page de confidentialité est une base de travail : faites-la vérifier et adaptez-la à la politique officielle avant toute collecte réelle.
- Les coordonnées WhatsApp, téléphone, adresse et réseaux sociaux sont configurables depuis l’administration.
- L’espace d’administration comprend des filtres et un export CSV, l’historique des changements, des graphiques issus des candidatures enregistrées, le suivi des montants approuvés et la publication de témoignages authentiques.
- Les témoignages ne sont pas préremplis : ne publiez que des citations réelles avec l’autorisation de la personne concernée.
- Les textes évitent de promettre qu’une candidature donnera droit à un financement.

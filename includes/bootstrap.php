<?php
declare(strict_types=1);

$localConfigPath = __DIR__ . '/config.local.php';
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

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'httponly' => true,
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'),
        'samesite' => 'Lax',
        'path' => '/',
    ]);
    session_start();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function e(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function tr(string $fr, string $en): string {
    if (($_SESSION['lang'] ?? 'fr') !== 'en') return $fr;
    if ($en !== $fr) return $en;
    static $dictionary = [
        'La fondation'=>'The foundation','Le programme'=>'The programme','Critères'=>'Eligibility','Suivre mon dossier'=>'Track my application',
        'Soumettre un projet'=>'Submit a project','Suivre un dossier'=>'Track an application','Candidature confidentielle'=>'Confidential application',
        'Suivi par numéro de dossier'=>'Track by application reference','Agriculture'=>'Agriculture','Entrepreneuriat'=>'Entrepreneurship',
        'Innovation'=>'Innovation','Santé'=>'Health','Éducation'=>'Education','Impact social'=>'Social impact','Artisanat'=>'Crafts',
        'Autre'=>'Other','Achat de matériel'=>'Equipment purchase','Achat de marchandises'=>'Goods purchase','Construction'=>'Construction',
        'Aménagement'=>'Renovation','Élevage'=>'Livestock','Transport'=>'Transport','Recrutement'=>'Hiring','Marketing'=>'Marketing',
        'Technologie'=>'Technology','Formation'=>'Training','Idée'=>'Idea','En préparation'=>'In preparation','Déjà lancé'=>'Already launched',
        'En développement'=>'In development','Entreprise existante'=>'Existing business','Nom'=>'Last name','Prénom'=>'First name',
        'Date de naissance'=>'Date of birth','Sexe'=>'Gender','Femme'=>'Female','Homme'=>'Male','Choisir'=>'Choose',
        'Nationalité'=>'Nationality','Pays de résidence'=>'Country of residence','Région'=>'Region','Ville'=>'City','Quartier'=>'Neighbourhood',
        'Adresse'=>'Address','Téléphone'=>'Phone','Numéro WhatsApp'=>'WhatsApp number','Adresse e-mail'=>'Email address',
        'Situation matrimoniale'=>'Marital status','Situation professionnelle'=>'Employment status','Niveau d’études'=>'Education level',
        'Nom de l’entreprise'=>'Business name','Date de création'=>'Date established','Secteur'=>'Sector','Localisation'=>'Location',
        'Nombre d’employés'=>'Number of employees','Numéro d’immatriculation'=>'Registration number','Secteur d’activité'=>'Sector',
        'Sélectionner'=>'Select','Pays du projet'=>'Project country','Région / ville'=>'Region / city',
        'Localisation du projet'=>'Project location','Stade du projet'=>'Project stage','Montant demandé'=>'Amount requested',
        'Devise'=>'Currency','Apport personnel'=>'Personal contribution','Coût total du projet'=>'Total project cost',
        'Vérification administrative'=>'Administrative check','En étude'=>'Under review','Présélectionnée'=>'Shortlisted',
        'Approuvée'=>'Approved','Refusée'=>'Declined','Accompagnement en cours'=>'Support in progress','Clôturée'=>'Closed',
        'Candidature reçue'=>'Application received','Étude du projet'=>'Project review','Soumis le'=>'Submitted',
        'Mis à jour le'=>'Updated','Dossier'=>'Application','Les notes internes de l’équipe ne sont pas affichées ici.'=>'Internal team notes are not shown here.',
        'Votre candidature a bien été enregistrée.'=>'Your application has been received.',
        'Conservez précieusement ce numéro. Il pourra être utilisé pour suivre votre candidature.'=>'Keep this reference safe. You will need it to track your application.',
        'Retour à l’accueil'=>'Back to home','Candidature enregistrée'=>'Application received',
        'Le dépôt d’une candidature ne garantit pas un financement.'=>'Submitting an application does not guarantee funding.',
        'Qui peut candidater ?'=>'Who can apply?',
        'Entrepreneurs, porteurs de projets, PME, startups, artisans, agriculteurs et certaines associations peuvent présenter un dossier, sous réserve des critères du programme.'=>'Entrepreneurs, project leaders, small businesses, startups, craftspeople, farmers and some associations may apply, subject to programme criteria.',
        'Quels projets sont concernés ?'=>'What projects are eligible?',
        'Le programme reçoit des projets dans plusieurs domaines, notamment l’agriculture, l’entrepreneuriat, l’innovation, la santé, l’éducation et l’impact social.'=>'The programme considers projects in areas including agriculture, entrepreneurship, innovation, health, education and social impact.',
        'Combien peut-on demander ?'=>'How much can I request?',
        'Le montant demandé doit correspondre à un besoin expliqué et à un budget cohérent. Les limites éventuelles dépendent des règles du programme en vigueur.'=>'The requested amount should match a clear need and a realistic budget. Any limits depend on current programme rules.',
        'Comment les dossiers sont-ils étudiés ?'=>'How are applications reviewed?',
        'Les dossiers sont examinés selon les critères, la faisabilité, l’impact et les ressources disponibles. La soumission ne garantit aucun financement.'=>'Applications are reviewed based on criteria, feasibility, impact and available resources. Submission does not guarantee funding.',
        'Combien de temps dure l’étude ?'=>'How long does the review take?',
        'Le délai peut varier selon le nombre de candidatures et les vérifications nécessaires. Le statut peut être consulté avec le numéro de dossier.'=>'Timing varies with application volume and required checks. You can check the status with your application reference.',
        'Comment suivre sa candidature ?'=>'How can I track my application?',
        'Utilisez la page « Suivre ma candidature » avec le numéro de dossier et l’e-mail ou le téléphone fourni lors de la soumission.'=>'Use the tracking page with your reference and the email or phone provided when applying.',
        'Comment serai-je contacté ?'=>'How will I be contacted?',
        'Les coordonnées fournies dans le formulaire pourront être utilisées pour communiquer au sujet de votre candidature.'=>'The contact details in your form may be used to communicate about your application.',
        'Le dépôt d’une candidature est-il payant ?'=>'Does it cost anything to apply?',
        'Le formulaire de candidature sur cette plateforme est gratuit. Méfiez-vous de toute demande de paiement pour garantir un financement.'=>'Applying through this platform is free. Be cautious of anyone asking for payment to guarantee funding.',
        'Puis-je modifier mon dossier ?'=>'Can I change my application?',
        'Pour demander une correction après l’envoi, contactez l’équipe en indiquant votre numéro de dossier.'=>'To request a correction after submission, contact the team and include your application reference.',
        'Candidat'=>'Applicant','Activité'=>'Activity','Projet'=>'Project','Budget'=>'Budget','Validation'=>'Consent',
        'Entrepreneurs'=>'Entrepreneurs','Porteurs de projets'=>'Project leaders','PME et petites entreprises'=>'Small businesses and SMEs',
        'Startups'=>'Startups','Artisans'=>'Craftspeople','Agriculteurs'=>'Farmers',
        'Associations ou autres structures, si le programme les autorise'=>'Associations or other organizations, if permitted by the programme',
        'Production, transformation, élevage et chaînes de valeur durables.'=>'Production, processing, livestock and sustainable value chains.',
        'Petites entreprises, artisanat et initiatives créatrices d’emplois.'=>'Small businesses, crafts and job-creating initiatives.',
        'Solutions technologiques et services répondant à des besoins locaux.'=>'Technology solutions and services addressing local needs.',
        'Actions qui favorisent le bien-être et l’accès aux services essentiels.'=>'Initiatives that support wellbeing and access to essential services.',
        'Formation, apprentissage et développement de compétences.'=>'Education, training and skills development.',
        'Initiatives associatives et communautaires, selon les règles du programme.'=>'Community and non-profit initiatives, subject to programme rules.',
        'Nouvelle'=>'New','Reçue'=>'Received','Vérification administrative'=>'Administrative check',
        'Informations complémentaires'=>'More information requested','En étude'=>'Under review',
        'Présélectionnée'=>'Shortlisted','Approuvée'=>'Approved','Refusée'=>'Declined',
        'Accompagnement en cours'=>'Support in progress','Clôturée'=>'Closed',
        'Vous pouvez aussi soumettre un projet dans un autre domaine. Consultez les critères avant de candidater.'=>'You may submit a project in another field. Review the eligibility criteria first.',
    ];
    return $dictionary[$fr] ?? $en;
}

function url(string $page, array $params = []): string {
    return 'index.php?' . http_build_query(['page' => $page] + $params);
}

function redirect(string $location): never {
    header('Location: ' . $location);
    exit;
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="' . e($_SESSION['csrf']) . '">';
}

function verify_csrf(): void {
    if (!hash_equals((string)($_SESSION['csrf'] ?? ''), (string)($_POST['csrf'] ?? ''))) {
        http_response_code(419);
        exit('La session a expiré. Rechargez la page et réessayez.');
    }
}

function flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array {
    $message = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $message;
}

function render_admin_flash(): void {
    $message = get_flash();
    if ($message) {
        echo '<div class="alert alert-' . e($message['type']) . '">' . e($message['message']) . '</div>';
    }
}

function valid_date(string $value): bool {
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) return false;
    $parsed = DateTime::createFromFormat('!Y-m-d', $value);
    $errors = DateTime::getLastErrors();
    return $parsed !== false
        && $parsed->format('Y-m-d') === $value
        && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0));
}

function return_application_error(string $message): never {
    $saved = $_POST;
    unset($saved['csrf'], $saved['action']);
    $_SESSION['old_application'] = $saved;
    flash('error', $message);
    redirect(url('apply'));
}

function db(): PDO {
    static $pdo;
    if ($pdo instanceof PDO) return $pdo;

    $host = getenv('DB_HOST') ?: '';
    $name = getenv('DB_NAME') ?: '';
    $user = getenv('DB_USER') ?: '';
    if ($host === '' || $name === '' || $user === '') {
        throw new PDOException('Database configuration is missing.');
    }
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $host, $name);
    $pdo = new PDO($dsn, $user, getenv('DB_PASSWORD') ?: '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 5,
    ]);
    return $pdo;
}

function site_settings(): array {
    static $settings;
    if (isset($settings)) return $settings;
    try {
        $rows = db()->query('SELECT setting_key, setting_value FROM settings')->fetchAll();
        $settings = [];
        foreach ($rows as $row) $settings[$row['setting_key']] = $row['setting_value'];
    } catch (Throwable) {
        $settings = [];
    }
    return $settings;
}

function require_admin(): void {
    if (empty($_SESSION['admin_id'])) redirect(url('admin-login'));
    if (time() - (int)($_SESSION['admin_last_activity'] ?? time()) > 1800) {
        $_SESSION = [];
        session_destroy();
        redirect(url('admin-login'));
    }
    $_SESSION['admin_last_activity'] = time();
}

function status_labels(): array {
    return [
        'new' => 'Nouvelle',
        'received' => 'Reçue',
        'verification' => 'Vérification administrative',
        'information' => 'Informations complémentaires',
        'review' => 'En étude',
        'shortlisted' => 'Présélectionnée',
        'approved' => 'Approuvée',
        'rejected' => 'Refusée',
        'support' => 'Accompagnement en cours',
        'closed' => 'Clôturée',
    ];
}

function public_testimonials(): array {
    try {
        return db()->query('SELECT display_name, role, quote FROM testimonials WHERE is_published = 1 ORDER BY sort_order, id DESC LIMIT 6')->fetchAll();
    } catch (Throwable) {
        return [];
    }
}

function submit_application(): never {
    $required = ['first_name', 'last_name', 'email', 'phone', 'project_name', 'sector', 'project_description', 'country', 'amount_requested', 'currency'];
    foreach ($required as $field) {
        if (trim((string)($_POST[$field] ?? '')) === '') {
            return_application_error(tr('Veuillez remplir tous les champs obligatoires.', 'Please complete all required fields.'));
        }
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        return_application_error(tr('Veuillez saisir une adresse e-mail valide.', 'Please enter a valid email address.'));
    }
    foreach (['birth_date', 'business_start_date'] as $dateField) {
        if (!empty($_POST[$dateField]) && !valid_date((string)$_POST[$dateField])) {
            return_application_error(tr('Une date saisie n’est pas valide.', 'One of the dates entered is invalid.'));
        }
    }
    if (!in_array((string)($_POST['currency'] ?? ''), ['XAF', 'EUR', 'USD'], true)
        || !in_array((string)($_POST['project_stage'] ?? ''), ['idea', 'preparation', 'launched', 'development', 'existing_business'], true)) {
        return_application_error(tr('Vérifiez la devise et le stade du projet.', 'Check the currency and project stage.'));
    }
    foreach (['consent_accuracy', 'consent_privacy', 'consent_processing'] as $field) {
        if (empty($_POST[$field])) {
            return_application_error(tr('Les trois consentements sont obligatoires pour envoyer le dossier.', 'All three consent checkboxes are required to submit.'));
        }
    }
    $uses = array_values(array_filter((array)($_POST['funding_use'] ?? []), fn($v) => is_string($v) && mb_strlen($v) < 80));
    if (!$uses) {
        return_application_error(tr('Sélectionnez au moins une utilisation prévue du financement.', 'Select at least one intended use of funding.'));
    }
    if (!is_numeric($_POST['amount_requested'] ?? null) || (float)($_POST['amount_requested'] ?? 0) <= 0 || mb_strlen(trim((string)($_POST['project_description'] ?? ''))) < 40) {
        return_application_error(tr('Vérifiez le montant demandé et saisissez une description de projet plus détaillée.', 'Check the requested amount and provide a more detailed project description.'));
    }

    $pdo = db();
    $pdo->beginTransaction();
    try {
        $insertSql = 'INSERT INTO applications
          (reference, first_name, last_name, birth_date, gender, nationality, country, region, city, neighborhood, address, phone, whatsapp, email, marital_status, professional_status, education_level,
           has_activity, business_name, business_start_date, business_sector, business_description, business_location, employee_count, annual_revenue, registration_number, promoter_experience,
           project_name, sector, project_country, project_region, project_city, project_location, project_description, project_stage,
           amount_requested, currency, personal_contribution, total_cost, funding_use, status, consent_accuracy, consent_privacy, consent_processing)
          VALUES (NULL,';
        $values = [
            trim($_POST['first_name']), trim($_POST['last_name']), ($_POST['birth_date'] ?? '') ?: null, $_POST['gender'] ?? null,
            trim($_POST['nationality'] ?? ''), trim($_POST['country']), trim($_POST['region'] ?? ''), trim($_POST['city'] ?? ''),
            trim($_POST['neighborhood'] ?? ''), trim($_POST['address'] ?? ''), trim($_POST['phone']), trim($_POST['whatsapp'] ?? ''),
            trim($_POST['email']), trim($_POST['marital_status'] ?? ''), trim($_POST['professional_status'] ?? ''), trim($_POST['education_level'] ?? ''),
            ($_POST['has_activity'] ?? 'no') === 'yes' ? 1 : 0, trim($_POST['business_name'] ?? ''), ($_POST['business_start_date'] ?? '') ?: null,
            trim($_POST['business_sector'] ?? ''), trim($_POST['business_description'] ?? ''), trim($_POST['business_location'] ?? ''),
            ($_POST['employee_count'] ?? '') !== '' ? (int)($_POST['employee_count'] ?? 0) : null, trim($_POST['annual_revenue'] ?? ''),
            trim($_POST['registration_number'] ?? ''), trim($_POST['promoter_experience'] ?? ''),
            trim($_POST['project_name']), trim($_POST['sector']), trim($_POST['project_country'] ?? $_POST['country']),
            trim($_POST['project_region'] ?? ''), trim($_POST['project_city'] ?? ''), trim($_POST['project_location'] ?? ''),
            trim($_POST['project_description']), trim($_POST['project_stage'] ?? 'idea'),
            (float)$_POST['amount_requested'], trim($_POST['currency']), (float)($_POST['personal_contribution'] ?? 0),
            (float)($_POST['total_cost'] ?? 0), json_encode($uses, JSON_UNESCAPED_UNICODE), 'new', 1, 1, 1,
        ];
        $stmt = $pdo->prepare($insertSql . implode(',', array_fill(0, count($values), '?')) . ')');
        $stmt->execute($values);
        $id = (int)$pdo->lastInsertId();
        $reference = 'SE-' . date('Y') . '-' . str_pad((string)$id, 6, '0', STR_PAD_LEFT);
        $pdo->prepare('UPDATE applications SET reference = ? WHERE id = ?')->execute([$reference, $id]);
        $pdo->prepare('INSERT INTO application_events (application_id, status, note, admin_id) VALUES (?, ?, ?, NULL)')
            ->execute([$id, 'new', 'Candidature reçue']);
        $pdo->commit();
        $_SESSION['submitted_reference'] = $reference;
        redirect(url('confirmation'));
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function find_application_for_candidate(string $reference, string $contact): array|false {
    $stmt = db()->prepare('SELECT id, reference, first_name, last_name, project_name, status, created_at, updated_at
        FROM applications WHERE reference = ? AND (email = ? OR phone = ? OR whatsapp = ?) LIMIT 1');
    $stmt->execute([$reference, $contact, $contact, $contact]);
    return $stmt->fetch() ?: false;
}

function admin_login(): never {
    $now = time();
    if (($_SESSION['login_lock_until'] ?? 0) > $now) {
        flash('error', tr('Trop de tentatives. Réessayez dans quelques minutes.', 'Too many attempts. Try again in a few minutes.'));
        redirect(url('admin-login'));
    }
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $stmt = db()->prepare('SELECT id, email, password_hash, display_name FROM admins WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $admin = $stmt->fetch();
    if (!$admin || !password_verify((string)($_POST['password'] ?? ''), $admin['password_hash'])) {
        $_SESSION['login_attempts'] = (int)($_SESSION['login_attempts'] ?? 0) + 1;
        if ($_SESSION['login_attempts'] >= 5) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['login_lock_until'] = $now + 900;
        }
        flash('error', tr('Adresse e-mail ou mot de passe incorrect.', 'Incorrect email or password.'));
        redirect(url('admin-login'));
    }
    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int)$admin['id'];
    $_SESSION['admin_name'] = $admin['display_name'];
    $_SESSION['login_attempts'] = 0;
    redirect(url('admin'));
}

function admin_update_application(): never {
    $id = (int)($_POST['id'] ?? 0);
    $status = (string)($_POST['status'] ?? '');
    $note = trim((string)($_POST['internal_note'] ?? ''));
    $approvedRaw = trim((string)($_POST['amount_approved'] ?? ''));
    if ($approvedRaw !== '' && (!is_numeric($approvedRaw) || (float)$approvedRaw < 0)) {
        flash('error', 'Le montant approuvé doit être un nombre positif ou nul.');
        redirect(url('admin-application', ['id' => $id]));
    }
    $approvedAmount = $approvedRaw === '' ? null : (float)$approvedRaw;
    if (!array_key_exists($status, status_labels())) {
        flash('error', 'Statut invalide.');
        redirect(url('admin-applications'));
    }
    $pdo = db();
    $pdo->beginTransaction();
    $pdo->prepare('UPDATE applications SET status = ?, internal_note = ?, amount_approved = ?, updated_at = NOW() WHERE id = ?')->execute([$status, $note, $approvedAmount, $id]);
    $pdo->prepare('INSERT INTO application_events (application_id, status, note, admin_id) VALUES (?, ?, ?, ?)')
        ->execute([$id, $status, 'Mise à jour par l’administration', $_SESSION['admin_id']]);
    $pdo->commit();
    flash('success', 'Le dossier a été mis à jour.');
    redirect(url('admin-application', ['id' => $id]));
}

function save_testimonial(): never {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim((string)($_POST['display_name'] ?? ''));
    $role = trim((string)($_POST['role'] ?? ''));
    $quote = trim((string)($_POST['quote'] ?? ''));
    $published = isset($_POST['is_published']) ? 1 : 0;
    $order = max(0, (int)($_POST['sort_order'] ?? 0));
    if ($name === '' || $quote === '' || mb_strlen($name) > 120 || mb_strlen($role) > 150 || mb_strlen($quote) > 1500) {
        flash('error', 'Vérifiez le nom et le témoignage (1 500 caractères maximum).');
        redirect(url('admin-testimonials'));
    }
    if ($id > 0) {
        db()->prepare('UPDATE testimonials SET display_name=?, role=?, quote=?, is_published=?, sort_order=? WHERE id=?')
            ->execute([$name,$role,$quote,$published,$order,$id]);
    } else {
        db()->prepare('INSERT INTO testimonials (display_name,role,quote,is_published,sort_order) VALUES (?,?,?,?,?)')
            ->execute([$name,$role,$quote,$published,$order]);
    }
    flash('success', 'Le témoignage a été enregistré.');
    redirect(url('admin-testimonials'));
}

function delete_testimonial(): never {
    $id = (int)($_POST['id'] ?? 0);
    db()->prepare('DELETE FROM testimonials WHERE id=?')->execute([$id]);
    flash('success', 'Le témoignage a été supprimé.');
    redirect(url('admin-testimonials'));
}

function export_applications_csv(): never {
    $where = [];
    $params = [];
    $search = trim((string)($_POST['q'] ?? ''));
    $status = (string)($_POST['status'] ?? '');
    $sector = trim((string)($_POST['sector'] ?? ''));
    $from = (string)($_POST['from'] ?? '');
    $to = (string)($_POST['to'] ?? '');
    if ($search !== '') {
        $where[] = '(reference LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR project_name LIKE ? OR phone LIKE ?)';
        for ($i = 0; $i < 5; $i++) $params[] = '%' . $search . '%';
    }
    if (array_key_exists($status, status_labels())) {
        $where[] = 'status = ?';
        $params[] = $status;
    }
    if ($sector !== '') {
        $where[] = 'sector = ?';
        $params[] = $sector;
    }
    foreach (['from' => $from, 'to' => $to] as $direction => $date) {
        if (!valid_date($date)) continue;
        if ($direction === 'from') {
            $where[] = 'created_at >= ?';
            $params[] = $date . ' 00:00:00';
        } else {
            $where[] = 'created_at < DATE_ADD(?, INTERVAL 1 DAY)';
            $params[] = $date . ' 00:00:00';
        }
    }
    $sql = 'SELECT reference, first_name, last_name, phone, email, project_name, sector, amount_requested, amount_approved, currency, status, created_at FROM applications'
        . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY created_at DESC';
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="candidatures-' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['Référence','Prénom','Nom','Téléphone','E-mail','Projet','Secteur','Montant demandé','Montant approuvé','Devise','Statut','Date de soumission'], ';');
    while ($row = $stmt->fetch()) {
        $row = array_map(static function ($value) {
            $value = (string)$value;
            return preg_match('/^[\s]*[=+\-@]/u', $value) ? "'" . $value : $value;
        }, array_values($row));
        fputcsv($out, $row, ';');
    }
    fclose($out);
    exit;
}

function admin_update_settings(): never {
    $allowed = ['official_email', 'phone', 'whatsapp', 'address', 'facebook_url', 'instagram_url', 'x_url'];
    $stmt = db()->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
    foreach ($allowed as $key) {
        $value = trim((string)($_POST[$key] ?? ''));
        if (mb_strlen($value) > 250) continue;
        if ($key === 'official_email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Adresse e-mail invalide.');
            redirect(url('admin-settings'));
        }
        if (str_ends_with($key, '_url') && $value !== '') {
            $scheme = strtolower((string)parse_url($value, PHP_URL_SCHEME));
            if (!filter_var($value, FILTER_VALIDATE_URL) || $scheme !== 'https') {
                flash('error', 'Les liens des réseaux sociaux doivent être des adresses HTTPS valides.');
                redirect(url('admin-settings'));
            }
        }
        $stmt->execute([$key, $value]);
    }
    flash('success', 'Paramètres enregistrés.');
    redirect(url('admin-settings'));
}

function save_contact_message(): never {
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($message) < 10) {
        flash('error', tr('Vérifiez votre nom, votre adresse e-mail et votre message.', 'Check your name, email address and message.'));
        redirect(url('contact'));
    }
    db()->prepare('INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)')->execute([$name, $email, $message]);
    flash('success', tr('Votre message a été envoyé. Merci.', 'Your message has been received. Thank you.'));
    redirect(url('contact'));
}

if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'], true)) {
    $_SESSION['lang'] = $_GET['lang'];
}

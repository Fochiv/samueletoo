<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$page = $_GET['page'] ?? 'home';
$adminPages = ['admin-login', 'admin-setup', 'admin', 'admin-applications', 'admin-application', 'admin-settings', 'admin-testimonials', 'admin-logout'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'submit_application') {
            submit_application();
        } elseif ($action === 'track_application') {
            $_SESSION['track_result'] = find_application_for_candidate(
                trim((string)($_POST['reference'] ?? '')),
                trim((string)($_POST['contact'] ?? ''))
            );
            redirect('index.php?page=track');
        } elseif ($action === 'admin_login') {
            admin_login();
        } elseif ($action === 'admin_setup') {
            admin_setup();
        } elseif ($action === 'admin_update_application') {
            require_admin();
            admin_update_application();
        } elseif ($action === 'admin_update_settings') {
            require_admin();
            admin_update_settings();
        } elseif ($action === 'admin_export_csv') {
            require_admin();
            export_applications_csv();
        } elseif ($action === 'admin_save_testimonial') {
            require_admin();
            save_testimonial();
        } elseif ($action === 'admin_delete_testimonial') {
            require_admin();
            delete_testimonial();
        } elseif ($action === 'contact') {
            save_contact_message();
        } else {
            http_response_code(400);
            exit('Action inconnue.');
        }
    } catch (PDOException $e) {
        error_log('Database operation failed: ' . $e->getMessage());
        flash('error', tr('La base de données n’est pas encore configurée. Ajoutez les paramètres MySQL du fichier .env.example pour activer cette fonctionnalité.', 'The database is not configured yet. Add the MySQL settings from .env.example to enable this feature.'));
        if ($action === 'submit_application') {
            $_SESSION['old_application'] = $_POST;
            unset($_SESSION['old_application']['csrf'], $_SESSION['old_application']['action']);
        }
        $failurePage = match ($action) {
            'submit_application' => 'apply',
            'admin_login' => 'admin-login',
            'admin_setup' => 'admin-setup',
            'admin_update_application', 'admin_update_settings', 'admin_export_csv', 'admin_save_testimonial', 'admin_delete_testimonial' => 'admin',
            'track_application' => 'track',
            default => 'contact',
        };
        redirect(url($failurePage));
    } catch (Throwable $e) {
        error_log('Request failed: ' . $e->getMessage());
        flash('error', tr('Une erreur est survenue. Veuillez réessayer.', 'Something went wrong. Please try again.'));
        redirect(url(match ($action) {
            'submit_application' => 'apply',
            'admin_login' => 'admin-login',
            'admin_setup' => 'admin-setup',
            'admin_update_application', 'admin_update_settings', 'admin_save_testimonial', 'admin_delete_testimonial' => 'admin',
            'admin_export_csv' => 'admin-applications',
            'track_application' => 'track',
            default => 'contact',
        }));
    }
}

if ($page === 'admin-logout') {
    require_admin();
    $_SESSION = [];
    session_destroy();
    redirect('index.php');
}

if (in_array($page, $adminPages, true)) {
    if (!in_array($page, ['admin-login', 'admin-setup'], true)) {
        require_admin();
    }
    require __DIR__ . '/pages/' . str_replace('-', '_', $page) . '.php';
    exit;
}

$allowedPages = ['home', 'about', 'program', 'criteria', 'apply', 'track', 'faq', 'contact', 'privacy', 'confirmation'];
if (!in_array($page, $allowedPages, true)) {
    http_response_code(404);
    $page = 'home';
}

require __DIR__ . '/includes/header.php';
require __DIR__ . '/pages/' . $page . '.php';
require __DIR__ . '/includes/footer.php';

<?php
$pdo = db();
$labels = status_labels();
$search = trim((string)($_GET['q'] ?? ''));
$status = (string)($_GET['status'] ?? '');
$sector = trim((string)($_GET['sector'] ?? ''));
$dateFrom = (string)($_GET['from'] ?? '');
$dateTo = (string)($_GET['to'] ?? '');
$validDate = static fn(string $value): bool => $value === '' || valid_date($value);
if (!$validDate($dateFrom)) $dateFrom = '';
if (!$validDate($dateTo)) $dateTo = '';
$sectors = $pdo->query("SELECT DISTINCT sector FROM applications WHERE sector <> '' ORDER BY sector")->fetchAll(PDO::FETCH_COLUMN);
$pageNum = max(1, (int)($_GET['p'] ?? 1));
$limit = 20;
$where = [];
$params = [];
if ($search !== '') {
    $where[] = '(reference LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR project_name LIKE ? OR phone LIKE ?)';
    for ($i=0;$i<5;$i++) $params[] = '%' . $search . '%';
}
if (isset($labels[$status])) { $where[] = 'status = ?'; $params[] = $status; }
if ($sector !== '' && in_array($sector, $sectors, true)) { $where[] = 'sector = ?'; $params[] = $sector; }
if ($dateFrom !== '') { $where[] = 'created_at >= ?'; $params[] = $dateFrom . ' 00:00:00'; }
if ($dateTo !== '') { $where[] = 'created_at < DATE_ADD(?, INTERVAL 1 DAY)'; $params[] = $dateTo . ' 00:00:00'; }
$clause = $where ? ' WHERE '.implode(' AND ', $where) : '';
$countStmt = $pdo->prepare('SELECT COUNT(*) FROM applications'.$clause);
$countStmt->execute($params);
$totalRows = (int)$countStmt->fetchColumn();
$stmt = $pdo->prepare('SELECT id, reference, first_name, last_name, phone, project_name, sector, amount_requested, currency, status, created_at, updated_at FROM applications'.$clause.' ORDER BY created_at DESC LIMIT '.$limit.' OFFSET '.(($pageNum-1)*$limit));
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><base href="/"><title>Candidatures — Fondation Samuel Eto’o</title><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"><link rel="stylesheet" href="assets/css/app.css"></head><body class="admin-body"><aside class="admin-sidebar"><a class="brand brand-light" href="index.php"><img src="logo.png" alt=""><span>FONDATION <b>SAMUEL ETO’O</b></span></a><nav><a href="<?= e(url('admin')) ?>"><i class="bi bi-grid"></i> Tableau de bord</a><a class="current" href="<?= e(url('admin-applications')) ?>"><i class="bi bi-folder2-open"></i> Candidatures</a><a href="<?= e(url('admin-settings')) ?>"><i class="bi bi-gear"></i> Paramètres</a><a href="index.php"><i class="bi bi-box-arrow-up-right"></i> Voir le site</a></nav><a class="admin-logout" href="<?= e(url('admin-logout')) ?>"><i class="bi bi-box-arrow-left"></i> Déconnexion</a></aside>
<main class="admin-main"><header class="admin-top"><div><p class="eyebrow eyebrow-dark"><span></span>GESTION DES DOSSIERS</p><h1>Candidatures</h1><p><?= e((string)$totalRows) ?> dossier<?= $totalRows===1?'':'s' ?> trouvé<?= $totalRows===1?'':'s' ?>.</p></div></header>
<section class="admin-panel"><form class="filter-bar" method="get"><input type="hidden" name="page" value="admin-applications"><label class="search-field"><i class="bi bi-search"></i><input name="q" value="<?= e($search) ?>" placeholder="Référence, candidat, projet ou téléphone"></label><select name="status"><option value="">Tous les statuts</option><?php foreach($labels as $key=>$label): ?><option value="<?= e($key) ?>" <?= $status===$key?'selected':'' ?>><?= e($label) ?></option><?php endforeach; ?></select><select name="sector"><option value="">Tous les secteurs</option><?php foreach($sectors as $sectorName): ?><option value="<?= e($sectorName) ?>" <?= $sector===$sectorName?'selected':'' ?>><?= e($sectorName) ?></option><?php endforeach; ?></select><input type="date" name="from" aria-label="Date de début" value="<?= e($dateFrom) ?>"><input type="date" name="to" aria-label="Date de fin" value="<?= e($dateTo) ?>"><button class="button button-green" type="submit">Filtrer</button></form>
<form method="post" class="export-form"><?= csrf_field() ?><input type="hidden" name="action" value="admin_export_csv"><?php foreach(['q'=>$search,'status'=>$status,'sector'=>$sector,'from'=>$dateFrom,'to'=>$dateTo] as $key=>$value): ?><input type="hidden" name="<?= e($key) ?>" value="<?= e($value) ?>"><?php endforeach; ?><button class="button button-quiet" type="submit"><i class="bi bi-download"></i> Export CSV</button></form>
<?php if(!$rows): ?><div class="empty-state"><i class="bi bi-search"></i><h3>Aucun dossier correspondant</h3><p>Essayez de modifier les filtres.</p></div><?php else: ?><div class="table-scroll"><table class="data-table"><thead><tr><th>Référence</th><th>Candidat</th><th>Téléphone</th><th>Projet</th><th>Secteur</th><th>Montant demandé</th><th>Statut</th><th>Soumis</th><th>Modifié</th></tr></thead><tbody><?php foreach($rows as $row): ?><tr><td><a href="<?= e(url('admin-application',['id'=>$row['id']])) ?>"><?= e($row['reference']) ?></a></td><td><?= e($row['first_name'].' '.$row['last_name']) ?></td><td><?= e($row['phone']) ?></td><td><?= e($row['project_name']) ?></td><td><?= e($row['sector']) ?></td><td><?= e(number_format((float)$row['amount_requested'],0,',',' ')) ?> <?= e($row['currency']) ?></td><td><span class="status-pill"><?= e($labels[$row['status']]??$row['status']) ?></span></td><td><?= e(date('d/m/Y',strtotime($row['created_at']))) ?></td><td><?= e(date('d/m/Y',strtotime($row['updated_at']))) ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
<?php if($totalRows>$limit): ?><nav class="pagination"><?php for($i=1;$i<=ceil($totalRows/$limit);$i++): ?><a class="<?= $i===$pageNum?'active':'' ?>" href="<?= e(url('admin-applications',['q'=>$search,'status'=>$status,'sector'=>$sector,'from'=>$dateFrom,'to'=>$dateTo,'p'=>$i])) ?>"><?= $i ?></a><?php endfor; ?></nav><?php endif; ?></section></main></body></html>

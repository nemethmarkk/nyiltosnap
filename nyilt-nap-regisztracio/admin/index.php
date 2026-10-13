<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/icons.php';
require_admin();

$pdo = db();
$flashMessage = take_flash();
$summary = $pdo->query("SELECT COUNT(*) AS total,
  COALESCE(SUM(is_graduating = 1), 0) AS graduating,
  COALESCE(SUM(is_graduating = 0), 0) AS non_graduating,
  COALESCE(SUM(accommodation = 'dormitory'), 0) AS dormitory,
  COALESCE(SUM(accommodation = 'commuter'), 0) AS commuter,
  COALESCE(SUM(accommodation = 'dormitory' AND is_graduating = 1), 0) AS graduating_dormitory,
  COALESCE(SUM(accommodation = 'commuter' AND is_graduating = 1), 0) AS graduating_commuter,
  COALESCE(SUM(accommodation = 'dormitory' AND is_graduating = 0), 0) AS non_graduating_dormitory,
  COALESCE(SUM(accommodation = 'commuter' AND is_graduating = 0), 0) AS non_graduating_commuter,
  COUNT(DISTINCT school_name) AS schools,
  COALESCE(SUM(DATE(created_at) = CURDATE()), 0) AS today,
  COALESCE(SUM(created_at >= (CURDATE() - INTERVAL 6 DAY)), 0) AS last7,
  MIN(created_at) AS first_at,
  MAX(created_at) AS last_at,
  CURDATE() AS db_today
  FROM registrations")->fetch();
$selectionTotal = (int)$pdo->query('SELECT COUNT(*) FROM registration_specialties')->fetchColumn();
$dailyRaw = $pdo->query("SELECT DATE(created_at) AS d, COUNT(*) AS c FROM registrations WHERE created_at >= (CURDATE() - INTERVAL 13 DAY) GROUP BY DATE(created_at)")->fetchAll(PDO::FETCH_KEY_PAIR);
$daily = [];
$baseDay = new DateTimeImmutable((string)$summary['db_today']);
for ($i = 13; $i >= 0; $i--) {
    $day = $baseDay->modify("-{$i} day");
    $daily[] = ['date' => $day, 'count' => (int)($dailyRaw[$day->format('Y-m-d')] ?? 0)];
}
$topSchools = $pdo->query("SELECT school_name, COUNT(*) AS total_count,
  COALESCE(SUM(is_graduating = 1), 0) AS graduating_count,
  COALESCE(SUM(accommodation = 'dormitory'), 0) AS dormitory_count
  FROM registrations GROUP BY school_name ORDER BY total_count DESC, school_name ASC LIMIT 8")->fetchAll();
$specialtyStats = $pdo->query("SELECT s.id, s.name,
  COUNT(rs.registration_id) AS total_count,
  COALESCE(SUM(r.is_graduating = 1), 0) AS graduating_count,
  COALESCE(SUM(r.accommodation = 'dormitory'), 0) AS dormitory_count,
  COALESCE(SUM(r.accommodation = 'dormitory' AND r.is_graduating = 1), 0) AS graduating_dormitory_count,
  s.slug
  FROM specialties s
  LEFT JOIN registration_specialties rs ON rs.specialty_id = s.id
  LEFT JOIN registrations r ON r.id = rs.registration_id
  WHERE s.slug IN ('cnc-programozo', 'mechatronikai-technikus', 'mechatronikus-karbantarto', 'penzugyi-szamviteli-ugyintezo', 'szerszam-es-keszulekgyarto', 'szoftverfejleszto-es-tesztelo')
  GROUP BY s.id, s.name, s.slug, s.sort_order
  ORDER BY s.sort_order, s.name")->fetchAll();
usort($specialtyStats, static fn($a, $b) => ((int)$b['total_count'] <=> (int)$a['total_count']) ?: 0);

$search = trim((string)($_GET['q'] ?? ''));
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$where = '';
$params = [];
if ($search !== '') {
    $where = " WHERE (r.full_name LIKE ? OR r.school_name LIKE ? OR EXISTS (
      SELECT 1 FROM registration_specialties search_rs
      INNER JOIN specialties search_s ON search_s.id = search_rs.specialty_id
      WHERE search_rs.registration_id = r.id AND search_s.name LIKE ?
    ))";
    $like = '%' . $search . '%';
    $params = [$like, $like, $like];
}
$countStmt = $pdo->prepare('SELECT COUNT(*) FROM registrations r' . $where);
$countStmt->execute($params);
$filteredCount = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($filteredCount / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$listSql = "SELECT r.id, r.full_name, r.school_name, r.accommodation, r.is_graduating, r.created_at,
  GROUP_CONCAT(DISTINCT s.name ORDER BY s.sort_order, s.name SEPARATOR '||') AS specialty_names
  FROM registrations r
  LEFT JOIN registration_specialties rs ON rs.registration_id = r.id
  LEFT JOIN specialties s ON s.id = rs.specialty_id" . $where . "
  GROUP BY r.id, r.full_name, r.school_name, r.accommodation, r.is_graduating, r.created_at
  ORDER BY r.created_at DESC, r.id DESC
  LIMIT ? OFFSET ?";
$listStmt = $pdo->prepare($listSql);
$position = 1;
foreach ($params as $param) {
    $listStmt->bindValue($position++, $param, PDO::PARAM_STR);
}
$listStmt->bindValue($position++, $perPage, PDO::PARAM_INT);
$listStmt->bindValue($position, $offset, PDO::PARAM_INT);
$listStmt->execute();
$registrations = $listStmt->fetchAll();
$csrf = csrf_token();
$logoExists = is_file(__DIR__ . '/../assets/logo.png');
$adminUsername = (string)($_SESSION['admin_username'] ?? 'Admin');
require __DIR__ . '/../views/admin-dashboard.php';

<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/bootstrap.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !valid_csrf(isset($_POST['csrf_token']) ? (string)$_POST['csrf_token'] : null)) {
    flash('error', 'A törlés nem sikerült: a munkamenet lejárt. Frissítsd az oldalt, majd próbáld újra.');
    redirect('index.php');
}
$id = filter_var($_POST['registration_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id) {
    flash('error', 'Érvénytelen jelentkezésazonosító.');
    redirect('index.php');
}
try {
    $stmt = db()->prepare('DELETE FROM registrations WHERE id = ?');
    $stmt->execute([(int)$id]);
    flash($stmt->rowCount() ? 'success' : 'error', $stmt->rowCount() ? 'A jelentkezést töröltük.' : 'A jelentkezés nem található, lehet, hogy már törölték.');
} catch (Throwable $exception) {
    error_log('Jelentkezés törlési hiba: ' . $exception->getMessage());
    flash('error', 'Nem sikerült törölni a jelentkezést. Próbáld meg újra.');
}
$redirectPage = max(1, (int)($_POST['page'] ?? 1));
$redirectQuery = trim((string)($_POST['q'] ?? ''));
$url = 'index.php?page=' . $redirectPage;
if ($redirectQuery !== '') $url .= '&q=' . rawurlencode($redirectQuery);
redirect($url . '#registrations');

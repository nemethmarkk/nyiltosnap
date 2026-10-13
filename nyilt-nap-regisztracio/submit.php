<?php
declare(strict_types=1);
require_once __DIR__ . '/app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$fullName = trim((string)($_POST['full_name'] ?? ''));
$schoolName = trim((string)($_POST['school_name'] ?? ''));
$accommodation = (string)($_POST['accommodation'] ?? '');
$isGraduating = (string)($_POST['is_graduating'] ?? '');
$rawSpecialties = $_POST['specialties'] ?? [];
$website = trim((string)($_POST['website'] ?? ''));

$old = [
    'full_name' => $fullName,
    'school_name' => $schoolName,
    'accommodation' => $accommodation,
    'is_graduating' => $isGraduating,
    'specialties' => is_array($rawSpecialties) ? array_map('strval', $rawSpecialties) : [],
];

$fail = static function (string $message) use ($old): never {
    $_SESSION['old_registration'] = $old;
    flash('error', $message);
    redirect('index.php#jelentkezes');
};

if (!valid_csrf(isset($_POST['csrf_token']) ? (string)$_POST['csrf_token'] : null)) {
    $fail('A munkamenet lejárt. Kérjük, töltsd be újra az oldalt, majd küldd el ismét a jelentkezést.');
}
if ($website !== '') {
    // Egyszerű honeypot mező a gépi beküldések kiszűrésére.
    redirect('index.php?sent=1');
}
if (text_length($fullName) < 2 || text_length($fullName) > 160) {
    $fail('Kérjük, add meg a teljes neved (2–160 karakter).');
}
if (text_length($schoolName) < 2 || text_length($schoolName) > 200) {
    $fail('Kérjük, add meg annak az iskolának a nevét, ahonnan érkezel.');
}
if (!in_array($accommodation, ['dormitory', 'commuter'], true)) {
    $fail('Kérjük, válaszd ki, kollégista vagy bejárós lennél-e.');
}
if (!in_array($isGraduating, ['0', '1'], true)) {
    $fail('Kérjük, jelöld meg, hogy végzős vagy-e.');
}
if (($_POST['privacy'] ?? '') !== '1') {
    $fail('A jelentkezés elküldéséhez kérjük, vedd tudomásul az adatkezelési tájékoztatót.');
}
if (!is_array($rawSpecialties) || count($rawSpecialties) < 1 || count($rawSpecialties) > 20) {
    $fail('Válassz legalább egy számodra érdekes szakmát. Többet is megjelölhetsz.');
}

$specialtyIds = array_values(array_unique(array_filter(array_map(static function ($value) {
    if (!is_scalar($value) || !ctype_digit((string)$value)) {
        return null;
    }
    return (int)$value;
}, $rawSpecialties), static fn($value) => is_int($value) && $value > 0)));
if (!$specialtyIds) {
    $fail('Kérjük, válassz legalább egy szakmát a listából.');
}

try {
    $pdo = db();
    $marks = implode(',', array_fill(0, count($specialtyIds), '?'));
    $validStmt = $pdo->prepare("SELECT id FROM specialties WHERE id IN ($marks)");
    $validStmt->execute($specialtyIds);
    $validIds = array_map('intval', $validStmt->fetchAll(PDO::FETCH_COLUMN));
    if (count($validIds) !== count($specialtyIds)) {
        $fail('A kiválasztott szakmák listája időközben megváltozott. Frissítsd az oldalt, és próbáld újra.');
    }

    $pdo->beginTransaction();
    $insert = $pdo->prepare('INSERT INTO registrations (full_name, school_name, accommodation, is_graduating) VALUES (?, ?, ?, ?)');
    $insert->execute([$fullName, $schoolName, $accommodation, (int)$isGraduating]);
    $registrationId = (int)$pdo->lastInsertId();

    $link = $pdo->prepare('INSERT INTO registration_specialties (registration_id, specialty_id) VALUES (?, ?)');
    foreach ($validIds as $specialtyId) {
        $link->execute([$registrationId, $specialtyId]);
    }
    $pdo->commit();

    unset($_SESSION['old_registration']);
    flash('success', 'Sikeresen kitöltötted!');
    redirect('index.php#jelentkezes');
} catch (Throwable $exception) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Mentési hiba: ' . $exception->getMessage());
    $fail('Most nem sikerült elmenteni a kitöltést. Kérjük, próbáld meg újra egy kicsit később.');
}

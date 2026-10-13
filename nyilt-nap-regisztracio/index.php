<?php
declare(strict_types=1);
require_once __DIR__ . '/app/bootstrap.php';

try {
    $wantedSpecialties = [
        ['CNC Programozó', 'cnc-programozo', 10],
        ['Mechatronikai technikus', 'mechatronikai-technikus', 20],
        ['Mechatronikus karbantartó', 'mechatronikus-karbantarto', 30],
        ['Pénzügyi-számviteli ügyintéző', 'penzugyi-szamviteli-ugyintezo', 40],
        ['Szerszám- és készülékgyártó', 'szerszam-es-keszulekgyarto', 50],
        ['Szoftverfejlesztő és -tesztelő', 'szoftverfejleszto-es-tesztelo', 60],
    ];
    $upsert = db()->prepare('INSERT INTO specialties (name, slug, sort_order) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name), sort_order = VALUES(sort_order)');
    foreach ($wantedSpecialties as [$name, $slug, $order]) {
        $upsert->execute([$name, $slug, $order]);
    }
    $names = array_column($wantedSpecialties, 0);
    $placeholders = implode(',', array_fill(0, count($names), '?'));
    $specialtyQuery = db()->prepare("SELECT id, name, slug FROM specialties WHERE name IN ($placeholders) ORDER BY sort_order, name");
    $specialtyQuery->execute($names);
    $specialties = $specialtyQuery->fetchAll();
} catch (Throwable $exception) {
    http_response_code(503);
    $databaseUnavailable = true;
    $specialties = [];
}

$flashMessage = take_flash();
$old = $_SESSION['old_registration'] ?? [];
unset($_SESSION['old_registration']);
$csrf = csrf_token();
$logoExists = has_school_logo();
require __DIR__ . '/views/home.php';

<?php
declare(strict_types=1);
require_once __DIR__ . '/app/bootstrap.php';

try {
    $adminExists = (int)db()->query('SELECT COUNT(*) FROM admins')->fetchColumn() > 0;
} catch (Throwable $exception) {
    $databaseError = true;
    $adminExists = false;
}

$errorMessage = '';
if (!$adminExists && empty($databaseError) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $passwordConfirm = (string)($_POST['password_confirm'] ?? '');

    if (!valid_csrf(isset($_POST['csrf_token']) ? (string)$_POST['csrf_token'] : null)) {
        $errorMessage = 'Lejárt a munkamenet, töltsd be újra az oldalt.';
    } elseif (!preg_match('/^[a-zA-Z0-9_.-]{3,40}$/', $username)) {
        $errorMessage = 'A felhasználónév 3–40 karakter lehet: angol betű, szám, pont, kötőjel vagy aláhúzás.';
    } elseif (strlen($password) < 12) {
        $errorMessage = 'A jelszó legyen legalább 12 karakter hosszú.';
    } elseif ($password !== $passwordConfirm) {
        $errorMessage = 'A két jelszó nem egyezik.';
    } else {
        try {
            $stmt = db()->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)');
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
            flash('success', 'Az adminfiók elkészült. Most már bejelentkezhetsz.');
            redirect('admin/login.php');
        } catch (Throwable $exception) {
            error_log('Admin telepítési hiba: ' . $exception->getMessage());
            $errorMessage = 'Nem sikerült létrehozni a fiókot. Ellenőrizd az adatbázist, vagy próbálj másik felhasználónevet.';
        }
    }
}

$csrf = csrf_token();
$logoExists = has_school_logo();
?>
<!doctype html><html lang="hu"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Első beállítás · <?= e(SCHOOL_NAME) ?></title><link rel="stylesheet" href="assets/app.css"></head>
<body class="setup-page"><main class="login-card">
  <a class="brand" href="index.php"><?php if ($logoExists): ?><img class="brand-logo" src="assets/logo.png" alt="Logó"><?php else: ?><span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 36 36"><path d="M5 13.5 18 6l13 7.5L18 21 5 13.5Z"/><path d="M10 17v7.2c4.9 4.1 11.1 4.1 16 0V17M31 14v8"/></svg></span><?php endif; ?><span class="brand-copy"><strong><?= e(SCHOOL_NAME) ?></strong><small>ADMIN BEÁLLÍTÁS</small></span></a>
  <?php if (!empty($databaseError)): ?>
    <h1>Kapcsoljuk össze az adatbázist.</h1><p class="login-lead">Az alkalmazás nem éri el az adatbázist, vagy még nem futott le az adatbázis-séma importálása.</p><div class="setup-card-note">1. Indítsd el a MySQL-t.<br>2. Importáld a <code>database/schema.sql</code> fájlt phpMyAdminban.<br>3. Ellenőrizd az <code>app/config.php</code> adatbázis-adatait, majd frissítsd ezt az oldalt.</div><a class="button button-submit" href="install.php">Újrapróbálom <span>→</span></a>
  <?php elseif ($adminExists): ?>
    <h1>Az oldal már be van állítva.</h1><p class="login-lead">Már létezik adminfiók ehhez az oldalhoz. Biztonsági okból új fiók erről az oldalról nem hozható létre.</p><a class="button button-submit" href="admin/login.php">Belépés az adminba <span>→</span></a><div class="login-foot"><a class="back-home" href="index.php">← Főoldal</a><span>Első beállítás kész</span></div>
  <?php else: ?>
    <h1>Első lépés: adminfiók.</h1><p class="login-lead">Hozz létre egy adminfiókot, amellyel később meg tudod nézni a jelentkezéseket és a statisztikákat.</p>
    <div class="setup-card-note">A beállító oldal csak addig enged új fiókot létrehozni, amíg egyetlen admin sem létezik. Sikeres beállítás után töröld az <code>install.php</code> fájlt a szerverről.</div>
    <?php if ($errorMessage !== ''): ?><div class="notice notice-error" role="alert"><?= e($errorMessage) ?></div><?php endif; ?>
    <form method="post" action="install.php"><input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
      <div class="field"><label for="username">Admin felhasználónév</label><input type="text" id="username" name="username" maxlength="40" autocomplete="username" value="<?= e($_POST['username'] ?? '') ?>" required></div>
      <div class="field" style="margin-top:17px"><label for="password">Jelszó (legalább 12 karakter)</label><input type="password" id="password" name="password" autocomplete="new-password" minlength="12" required></div>
      <div class="field" style="margin-top:17px"><label for="password_confirm">Jelszó újra</label><input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password" minlength="12" required></div>
      <button class="button button-submit" type="submit">Adminfiók létrehozása <span>→</span></button>
    </form><div class="login-foot"><a class="back-home" href="index.php">← Főoldal</a><span>Biztonságos első beállítás</span></div>
  <?php endif; ?>
</main></body></html>

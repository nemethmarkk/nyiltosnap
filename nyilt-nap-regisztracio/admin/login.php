<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/bootstrap.php';

if (admin_logged_in()) {
    redirect('index.php');
}
$errorMessage = '';
$flashMessage = take_flash();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if (!valid_csrf(isset($_POST['csrf_token']) ? (string)$_POST['csrf_token'] : null)) {
        $errorMessage = 'Lejárt a munkamenet. Frissítsd az oldalt, majd próbáld újra.';
    } else {
        // Az admin belépési adatok az app/config.php fájlban vannak előre beállítva.
        // A jelszó hash-ként van tárolva, nem olvasható formában.
        $usernameMatches = hash_equals(ADMIN_USERNAME, $username);
        $passwordMatches = password_verify($password, ADMIN_PASSWORD_HASH);
        if ($usernameMatches && $passwordMatches) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = 1;
            $_SESSION['admin_username'] = ADMIN_USERNAME;
            redirect('index.php');
        }
        $errorMessage = 'A felhasználónév vagy a belépési kód nem megfelelő.';
    }
}
$csrf = csrf_token();
$logoExists = is_file(__DIR__ . '/../assets/logo.png');
?>
<!doctype html><html lang="hu"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin belépés · NTSZKI</title><link rel="stylesheet" href="../assets/app.css"></head>
<body class="login-page"><main class="login-card">
  <a class="brand" href="../index.php"><?php if ($logoExists): ?><img class="brand-logo" src="../assets/logo.png" alt="Logó"><?php else: ?><span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 36 36"><path d="M5 13.5 18 6l13 7.5L18 21 5 13.5Z"/><path d="M10 17v7.2c4.9 4.1 11.1 4.1 16 0V17M31 14v8"/></svg></span><?php endif; ?><span class="brand-copy"><strong><?= e(SCHOOL_NAME) ?></strong><small>ADMIN FELÜLET</small></span></a>
  <h1>Jó újra látni.</h1><p class="login-lead">Jelentkezz be, hogy áttekintsd a nyílt nap regisztrációit és a szakmánkénti statisztikákat.</p>
  <?php if ($flashMessage): ?><div class="notice notice-<?= e($flashMessage['type']) ?>" role="status"><?= e($flashMessage['message']) ?></div><?php endif; ?>
  <?php if ($errorMessage !== ''): ?><div class="notice notice-error" role="alert"><?= e($errorMessage) ?></div><?php endif; ?>
  <form method="post" action="login.php"><input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
    <div class="field"><label for="username">Felhasználónév</label><input type="text" name="username" id="username" autocomplete="username" maxlength="40" value="<?= e($_POST['username'] ?? ADMIN_USERNAME) ?>" required></div>
    <div class="field"><label for="password">Belépési kód</label><input type="password" name="password" id="password" autocomplete="current-password" required></div>
    <button class="button button-submit" type="submit">Belépés az adminba <span>→</span></button>
  </form><div class="login-foot"><a class="back-home" href="../index.php">← Vissza a főoldalra</a><span>Védett adminisztráció</span></div>
</main></body></html>

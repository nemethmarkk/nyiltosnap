<?php
declare(strict_types=1);

// A helyi fejlesztői környezethez tartozó adatbázis-beállítások.
// Éles szerveren ezeket környezeti változókból is megadhatod.
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_NAME', getenv('DB_NAME') ?: 'nyilt_nap');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

// Az iskola neve a fejlécben és az adminfelületen jelenik meg.
define('SCHOOL_NAME', getenv('SCHOOL_NAME') ?: 'NTSZKI');

// Előre beállított admin-hozzáférés. Éles szerveren érdemes környezeti változóval felülírni.
define('ADMIN_USERNAME', getenv('ADMIN_USERNAME') ?: 'admin');
define('ADMIN_PASSWORD_HASH', getenv('ADMIN_PASSWORD_HASH') ?: '$2y$12$hOBqmBvtNmudHjppdGwEW..mi.6Ru2h7NwmwebFnBBMfWONoOu0iq');
define('SCHOOL_SUBTITLE', 'Találd meg a hozzád illő szakmát!');

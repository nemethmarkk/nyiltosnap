<?php
$iconSvg = static function (string $name): string {
    $icons = [
        'sparkles' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 1.7 5.3L19 10l-5.3 1.7L12 17l-1.7-5.3L5 10l5.3-1.7L12 3Z"/><path d="m19 15 .9 2.1L22 18l-2.1.9L19 21l-.9-2.1L16 18l2.1-.9L19 15Z"/></svg>',
        'cpu' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9 2v4m6-4v4M9 18v4m6-4v4M2 9h4m-4 6h4m12-6h4m-4 6h4"/></svg>',
        'wrench' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4L14 13l-3-3 3.7-3.7Z"/></svg>',
        'calculator' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="2" width="14" height="20" rx="2"/><path d="M8 6h8v4H8zM8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/></svg>',
        'ruler' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 17 14-14 4 4L7 21zM14 6l4 4m-7-1 2 2m-5 1 2 2"/></svg>',
        'monitor' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4M8 9l2 2-2 2M12 13h4"/></svg>',
        'server' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="3" width="16" height="8" rx="2"/><rect x="4" y="13" width="16" height="8" rx="2"/><path d="M8 7h.01M8 17h.01M12 7h5M12 17h5"/></svg>',
        'cog' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="m19.4 15 .1.1 1.1.8-1.2 2.1-1.3-.5a7.6 7.6 0 0 1-1.6.9l-.2 1.4h-2.5l-.2-1.4a7.6 7.6 0 0 1-1.6-.9l-1.3.5-1.2-2.1 1.1-.8a7 7 0 0 1 0-1.8l-1.1-.8 1.2-2.1 1.3.5a7.6 7.6 0 0 1 1.6-.9l.2-1.4h2.5l.2 1.4a7.6 7.6 0 0 1 1.6.9l1.3-.5 1.2 2.1-1.1.8a7 7 0 0 1-.1 1.7Z" transform="translate(-1 -1)"/></svg>',
        'zap' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m13 2-9 12h7l-1 8 10-13h-7l0-7Z"/></svg>',
        'shopping-bag' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 8h14l1 13H4L5 8Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/></svg>',
        'compass' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m15.5 8.5-2.2 4.8-4.8 2.2 2.2-4.8 4.8-2.2Z"/></svg>',
        'home' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 10 9-7 9 7M5 9v12h14V9M9 21v-7h6v7"/></svg>',
        'building' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M9 7h1M14 7h1M9 11h1M14 11h1M9 15h1M14 15h1M10 21v-3h4v3"/></svg>',
        'graduation' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m2 9 10-5 10 5-10 5L2 9Z"/><path d="M6 11v5c4 3 8 3 12 0v-5M22 9v6"/></svg>',
        'route' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="6" cy="18" r="2"/><circle cx="18" cy="6" r="2"/><path d="M8 18h3a3 3 0 0 0 3-3v-3a3 3 0 0 1 3-3h1"/></svg>',
        'message' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8v.5Z"/></svg>',
        'lock' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 1 1 8 0v3M12 14v3"/></svg>',
        'check' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>',
        'heart' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/></svg>',
    ];
    return $icons[$name] ?? $icons['sparkles'];
};
$tips = [
    ['icon' => 'compass', 'title' => 'Nem kell már most tudnod a választ.', 'text' => 'A nyílt nap pont arra jó, hogy kipróbálj és megismerj több lehetőséget. A kíváncsiság tökéletes kezdés.'],
    ['icon' => 'message', 'title' => 'Kérdezz bátran!', 'text' => 'Gondold át, mi érdekel, és kérdezd meg a diákokat vagy tanárokat arról, milyen a szakma a mindennapokban.'],
    ['icon' => 'route', 'title' => 'A saját erősségeidből indulj ki.', 'text' => 'Szeretsz alkotni, szerelni, emberekkel foglalkozni vagy problémákat megoldani? Ezek jó nyomok lehetnek.'],
    ['icon' => 'heart', 'title' => 'Több szakma is érdekelhet.', 'text' => 'Nem kell egyetlen lehetőség mellett döntened már most. Jelölj meg több szakmát, és fedezd fel őket élőben.'],
];
?>
<!doctype html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#5d58dc">
  <meta name="description" content="NTSZKI">
  <title>NTSZKI</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/home.css">
</head>
<body class="public-page ntsz-public">
  <div class="nts-background-shape nts-shape-one" aria-hidden="true"></div>
  <div class="nts-background-shape nts-shape-two" aria-hidden="true"></div>

  <header class="nts-header">
    <a class="nts-brand" href="index.php" aria-label="NTSZKI nyílt nap – főoldal">
      <?php if ($logoExists): ?>
        <img class="nts-logo" src="assets/logo.png" alt="NTSZKI logó">
      <?php else: ?>
        <span class="nts-logo-fallback" aria-hidden="true"><?= $iconSvg('graduation') ?></span>
      <?php endif; ?>
      <span class="nts-brand-copy"><strong>NTSZKI</strong><small>CSEPREG</small></span>
    </a>
  </header>

  <main class="nts-main" id="jelentkezes">

    <?php if ($flashMessage): ?>
      <div class="nts-notice nts-notice-<?= e($flashMessage['type']) ?>" role="status">
        <span class="nts-notice-icon"><?= $flashMessage['type'] === 'success' ? $iconSvg('check') : $iconSvg('sparkles') ?></span>
        <span><?= e($flashMessage['message']) ?></span>
      </div>
    <?php endif; ?>

    <?php if (!empty($databaseUnavailable)): ?>
      <div class="nts-form-card nts-database-error">
        <span class="nts-field-icon"><?= $iconSvg('message') ?></span>
        <h2>Még egy gyors technikai lépés.</h2>
        <p>Az adatbázis jelenleg nem érhető el. Importáld a <code>database/schema.sql</code> fájlt a MySQL-adatbázisba, majd ellenőrizd az <code>app/config.php</code> kapcsolati beállításait.</p>
      </div>
    <?php else: ?>
      <div class="nts-tip-line" aria-live="polite"><span class="nts-tip-line-label">TIPP</span><span id="simpleTip">Nem kell már most tudnod a választ – a nyílt nap pont a felfedezésről szól.</span></div>
      <div class="nts-layout">
        <section class="nts-form-card" aria-label="Nyílt napi regisztrációs űrlap">
          <form action="submit.php" method="post" id="registrationForm" class="nts-form">
            <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
            <div class="nts-hp-field" aria-hidden="true"><label for="website">Weboldal</label><input type="text" id="website" name="website" tabindex="-1" autocomplete="off"></div>

            <div class="nts-fields-two">
              <div class="nts-field">
                <label for="full_name">Hogy hívnak? <span>*</span></label>
                <div class="nts-input-wrap"><span class="nts-input-icon"><?= $iconSvg('heart') ?></span><input type="text" id="full_name" name="full_name" autocomplete="name" maxlength="160" minlength="2" placeholder="Pl. Kovács Anna" value="<?= e($old['full_name'] ?? '') ?>" required></div>
                <small>A teljes nevedet írd be.</small>
              </div>
              <div class="nts-field">
                <label for="school_name">Melyik iskolából érkezel? <span>*</span></label>
                <div class="nts-input-wrap"><span class="nts-input-icon"><?= $iconSvg('building') ?></span><input type="text" id="school_name" name="school_name" maxlength="200" minlength="2" placeholder="Az általános iskolád neve" value="<?= e($old['school_name'] ?? '') ?>" required></div>
                <small></small>
              </div>
            </div>

            <fieldset class="nts-fieldset nts-specialties-field">
              <div class="nts-section-title-row"><legend>Melyik szakmák érdekelnek? <span>*</span></legend><span class="nts-selection-count" id="selectionCount">0 kiválasztva</span></div>
              <p class="nts-field-hint">Többet is választhatsz – most még csak ismerkedünk a lehetőségekkel.</p>
              <?php if (!$specialties): ?>
                <p class="nts-field-hint">Jelenleg nincs szakma beállítva. Add hozzá őket az adatbázis <code>specialties</code> táblájához.</p>
              <?php else: ?>
                <div class="nts-specialty-grid" id="specialtyGrid">
                  <?php foreach ($specialties as $specialty):
                    $checked = in_array((string)$specialty['id'], array_map('strval', $old['specialties'] ?? []), true);
                    $slug = (string)($specialty['slug'] ?? '');
                    if (str_contains($slug, 'cnc')) { $specialtyIcon = 'cpu'; }
                    elseif (str_contains($slug, 'mechatronikai')) { $specialtyIcon = 'cog'; }
                    elseif (str_contains($slug, 'mechatronikus')) { $specialtyIcon = 'wrench'; }
                    elseif (str_contains($slug, 'penzugyi')) { $specialtyIcon = 'calculator'; }
                    elseif (str_contains($slug, 'szerszam')) { $specialtyIcon = 'ruler'; }
                    elseif (str_contains($slug, 'szoftver')) { $specialtyIcon = 'monitor'; }
                    else { $specialtyIcon = 'sparkles'; }
                  ?>
                    <label class="nts-specialty-option<?= $checked ? ' is-selected' : '' ?>">
                      <input type="checkbox" name="specialties[]" value="<?= (int)$specialty['id'] ?>" <?= $checked ? 'checked' : '' ?>>
                      <?php $imageFile = 'assets/szakmak/' . preg_replace('/[^a-z0-9\-]/', '', $slug) . '.png'; ?>
                      <span class="nts-specialty-media">
                        <?php if ($slug !== '' && is_file(__DIR__ . '/../' . $imageFile)): ?>
                          <img src="<?= e($imageFile) ?>" alt="" loading="lazy" decoding="async">
                        <?php else: ?>
                          <span class="nts-specialty-icon"><?= $iconSvg($specialtyIcon) ?></span>
                        <?php endif; ?>
                      </span>
                      <span class="nts-specialty-body">
                        <span class="nts-specialty-name"><?= e($specialty['name']) ?></span>
                        <span class="nts-specialty-check"><?= $iconSvg('check') ?></span>
                      </span>
                    </label>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <div class="nts-inline-error" id="specialtyError" aria-live="polite"></div>
            </fieldset>

            <fieldset class="nts-fieldset">
              <legend>Hogyan tervezel bejárni? <span>*</span></legend>
              <p class="nts-field-hint">Így jobban tudjuk, milyen lehetőségek érdekelhetnek.</p>
              <div class="nts-choice-grid">
                <label class="nts-choice-option<?= ($old['accommodation'] ?? '') === 'commuter' ? ' is-selected' : '' ?>">
                  <input type="radio" name="accommodation" value="commuter" <?= ($old['accommodation'] ?? '') === 'commuter' ? 'checked' : '' ?> required>
                  <span class="nts-choice-icon nts-icon-blue"><?= $iconSvg('home') ?></span><span class="nts-choice-copy"><strong>Bejárós lennék</strong><small>Naponta beutaznék</small></span><span class="nts-choice-indicator"></span>
                </label>
                <label class="nts-choice-option<?= ($old['accommodation'] ?? '') === 'dormitory' ? ' is-selected' : '' ?>">
                  <input type="radio" name="accommodation" value="dormitory" <?= ($old['accommodation'] ?? '') === 'dormitory' ? 'checked' : '' ?> required>
                  <span class="nts-choice-icon nts-icon-lilac"><?= $iconSvg('building') ?></span><span class="nts-choice-copy"><strong>Kollégista lennék</strong><small>Kollégiumban laknék</small></span><span class="nts-choice-indicator"></span>
                </label>
              </div>
            </fieldset>

            <fieldset class="nts-fieldset">
              <legend>Végzős vagy ebben a tanévben? <span>*</span></legend>
              <div class="nts-choice-grid">
                <label class="nts-choice-option<?= ($old['is_graduating'] ?? '') === '1' ? ' is-selected' : '' ?>">
                  <input type="radio" name="is_graduating" value="1" <?= ($old['is_graduating'] ?? '') === '1' ? 'checked' : '' ?> required>
                  <span class="nts-choice-icon nts-icon-mint"><?= $iconSvg('graduation') ?></span><span class="nts-choice-copy"><strong>Igen, végzős vagyok</strong><small>Idén fejezem be az általánost</small></span><span class="nts-choice-indicator"></span>
                </label>
                <label class="nts-choice-option<?= ($old['is_graduating'] ?? '') === '0' ? ' is-selected' : '' ?>">
                  <input type="radio" name="is_graduating" value="0" <?= ($old['is_graduating'] ?? '') === '0' ? 'checked' : '' ?> required>
                  <span class="nts-choice-icon nts-icon-peach"><?= $iconSvg('route') ?></span><span class="nts-choice-copy"><strong>Még nem</strong><small>Előre tájékozódom</small></span><span class="nts-choice-indicator"></span>
                </label>
              </div>
            </fieldset>

            <div class="nts-privacy-row">
              <label class="nts-privacy-label"><input type="checkbox" name="privacy" value="1" required><span class="nts-privacy-custom"><?= $iconSvg('check') ?></span><span>Elolvastam, és tudomásul veszem, hogy a megadott adataimat a nyílt nap szervezése céljából kezelik. <i>*</i></span></label>
            </div>
            <button class="nts-submit-button" type="submit" id="submitButton">Elküldöm</button>
            <p class="nts-submit-note"><?= $iconSvg('lock') ?> Az adataidat kizárólag a nyílt nap szervezéséhez használjuk.</p>
          </form>
        </section>


      </div>
    <?php endif; ?>
    <footer class="nts-footer"><span>© <?= date('Y') ?> NTSZKI</span><span class="nts-footer-separator" aria-hidden="true"></span><span>Nyitott ajtók. Új lehetőségek.</span></footer>
  </main>
  <script src="assets/app.js" defer></script>
</body>
</html>

<!doctype html><html lang="hu"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"><meta name="theme-color" content="#5d58dc"><title>Admin áttekintő · <?= e(SCHOOL_NAME) ?></title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="../assets/app.css"><link rel="stylesheet" href="../assets/admin.css"></head>
<body class="admin-body ad-page">
<?php
$total = (int)$summary['total'];
$tile = static function (string $icon, string $tone, string $label, string $value, string $sub, string $extra = ''): string {
    return '<article class="ad-kpi ad-tone-' . $tone . '"><span class="ad-kpi-icon">' . icon($icon) . '</span><div class="ad-kpi-copy"><span class="ad-kpi-label">' . e($label) . '</span><strong class="ad-kpi-value">' . e($value) . '</strong><span class="ad-kpi-sub">' . $sub . '</span></div>' . $extra . '</article>';
};
$split = static function (string $aLabel, int $a, string $aTone, string $bLabel, int $b, string $bTone, string $aIcon, string $bIcon) use ($total): string {
    $pa = pct($a, $a + $b); $pb = $a + $b > 0 ? 100 - $pa : 0;
    return '<div class="ad-split-bar" role="img" aria-label="' . e($aLabel . ': ' . $a . ', ' . $bLabel . ': ' . $b) . '"><span class="ad-seg ad-bg-' . $aTone . '" style="width:' . $pa . '%"></span><span class="ad-seg ad-bg-' . $bTone . '" style="width:' . $pb . '%"></span></div>'
      . '<div class="ad-split-legend"><div class="ad-legend-item"><span class="ad-legend-icon ad-tone-' . $aTone . '">' . icon($aIcon) . '</span><span class="ad-legend-text"><small>' . e($aLabel) . '</small><strong>' . $a . ' fő</strong></span><em>' . $pa . '%</em></div>'
      . '<div class="ad-legend-item"><span class="ad-legend-icon ad-tone-' . $bTone . '">' . icon($bIcon) . '</span><span class="ad-legend-text"><small>' . e($bLabel) . '</small><strong>' . $b . ' fő</strong></span><em>' . $pb . '%</em></div></div>';
};
$maxSpecialty = 0; foreach ($specialtyStats as $item) { $maxSpecialty = max($maxSpecialty, (int)$item['total_count']); }
$maxDaily = max(1, ...array_column($daily, 'count'));
$maxSchool = 0; foreach ($topSchools as $item) { $maxSchool = max($maxSchool, (int)$item['total_count']); }
$weekdays = ['H', 'K', 'Sze', 'Cs', 'P', 'Szo', 'V'];
$avgPick = $total > 0 ? number_format($selectionTotal / $total, 1, ',', '') : '0';
$lastAt = $summary['last_at'] ? date('Y. m. d. H:i', strtotime((string)$summary['last_at'])) : '–';
?>
<header class="ad-header">
  <a class="ad-brand" href="../index.php"><?php if ($logoExists): ?><img class="ad-logo" src="../assets/logo.png" alt="Logó"><?php else: ?><span class="ad-logo-fallback" aria-hidden="true"><?= icon('graduation') ?></span><?php endif; ?><span class="ad-brand-copy"><strong><?= e(SCHOOL_NAME) ?></strong><small>NYÍLT NAP · ADMIN</small></span></a>
  <div class="ad-header-right">
    <span class="ad-user"><?= icon('user') ?><span><?= e($adminUsername) ?></span></span>
    <a class="ad-btn" href="../index.php" target="_blank" rel="noopener"><?= icon('external') ?><span>Űrlap megnyitása</span></a>
    <form action="logout.php" method="post"><input type="hidden" name="csrf_token" value="<?= e($csrf) ?>"><button class="ad-btn" type="submit"><?= icon('logout') ?><span>Kilépés</span></button></form>
  </div>
</header>

<main class="ad-main">
  <?php if ($flashMessage): ?><div class="admin-flash <?= e($flashMessage['type']) ?>" role="status"><?= e($flashMessage['message']) ?></div><?php endif; ?>

  <div class="ad-title">
    <div><span class="ad-eyebrow">ÁTTEKINTÉS</span><h1>Nyílt napi jelentkezések</h1></div>
    <nav class="ad-jump" aria-label="Ugrás a szakaszokhoz"><a href="#osszkep"><?= icon('chart') ?>Összkép</a><a href="#szakmak"><?= icon('layers') ?>Szakmák</a><a href="#idovonal"><?= icon('trend') ?>Alakulás</a><a href="#registrations"><?= icon('users') ?>Regisztrációk</a></nav>
  </div>

  <section class="ad-section" id="osszkep" aria-labelledby="h-osszkep">
    <h2 class="ad-section-title" id="h-osszkep"><span class="ad-h-icon"><?= icon('chart') ?></span>Összkép<small>A legfontosabb számok egy pillantásra</small></h2>
    <div class="ad-kpi-grid">
      <?= $tile('users', 'lilac', 'Összes jelentkező', (string)$total, 'Ma: <b>+' . (int)$summary['today'] . '</b> · elmúlt 7 nap: <b>+' . (int)$summary['last7'] . '</b>') ?>
      <?= $tile('building', 'blue', 'Különböző iskola', (string)(int)$summary['schools'], 'Ennyi intézményből érkeztek') ?>
      <?= $tile('graduation', 'mint', 'Végzős diák', (string)(int)$summary['graduating'], pct((int)$summary['graduating'], $total) . '% az összes jelentkezőből') ?>
      <?= $tile('home', 'peach', 'Kollégiumot igényel', (string)(int)$summary['dormitory'], pct((int)$summary['dormitory'], $total) . '% · ebből végzős: <b>' . (int)$summary['graduating_dormitory'] . '</b>') ?>
    </div>

    <div class="ad-profile-grid">
      <article class="ad-card">
        <h3><span class="ad-h-icon sm"><?= icon('graduation') ?></span>Évfolyam</h3>
        <?= $split('Végzős', (int)$summary['graduating'], 'mint', 'Még nem végzős', (int)$summary['non_graduating'], 'peach', 'graduation', 'route') ?>
      </article>
      <article class="ad-card">
        <h3><span class="ad-h-icon sm"><?= icon('home') ?></span>Hogyan járnának be?</h3>
        <?= $split('Bejárós', (int)$summary['commuter'], 'blue', 'Kollégista', (int)$summary['dormitory'], 'lilac', 'home', 'building') ?>
      </article>
      <article class="ad-card ad-card-matrix">
        <h3><span class="ad-h-icon sm"><?= icon('layers') ?></span>Évfolyam × lakhatás<small>Kapacitástervezéshez</small></h3>
        <div class="ad-matrix">
          <div class="ad-cell ad-cell-key ad-tone-lilac"><span><?= icon('building') ?>Végzős kollégista</span><strong><?= (int)$summary['graduating_dormitory'] ?></strong></div>
          <div class="ad-cell ad-tone-blue"><span><?= icon('home') ?>Végzős bejárós</span><strong><?= (int)$summary['graduating_commuter'] ?></strong></div>
          <div class="ad-cell ad-tone-peach"><span><?= icon('building') ?>Nem végzős kollégista</span><strong><?= (int)$summary['non_graduating_dormitory'] ?></strong></div>
          <div class="ad-cell ad-tone-mint"><span><?= icon('home') ?>Nem végzős bejárós</span><strong><?= (int)$summary['non_graduating_commuter'] ?></strong></div>
        </div>
      </article>
    </div>
  </section>

  <section class="ad-section" id="szakmak" aria-labelledby="h-szakmak">
    <h2 class="ad-section-title" id="h-szakmak"><span class="ad-h-icon"><?= icon('layers') ?></span>Érdeklődés szakmánként<small>Rangsor érdeklődés szerint · átlagosan <?= e($avgPick) ?> szakmát jelöltek meg jelentkezőnként (többet is választhattak)</small></h2>
    <?php if (!$specialtyStats): ?><div class="ad-card ad-empty">Még nincs szakma beállítva.</div><?php else: ?>
    <div class="ad-specialty-grid">
      <?php foreach ($specialtyStats as $rank => $stat):
        $slug = (string)$stat['slug'];
        $imageFile = 'assets/szakmak/' . preg_replace('/[^a-z0-9\-]/', '', $slug) . '.png';
        $hasImage = $slug !== '' && is_file(__DIR__ . '/../' . $imageFile);
        $count = (int)$stat['total_count'];
      ?>
      <article class="ad-spec">
        <div class="ad-spec-media">
          <?php if ($hasImage): ?><img src="../<?= e($imageFile) ?>" alt="" loading="lazy" decoding="async"><?php else: ?><span class="ad-spec-icon"><?= icon(specialty_icon_name($slug)) ?></span><?php endif; ?>
          <span class="ad-rank<?= $rank === 0 && $count > 0 ? ' is-top' : '' ?>"><?= $rank === 0 && $count > 0 ? icon('trophy') : '' ?>#<?= $rank + 1 ?></span>
        </div>
        <div class="ad-spec-body">
          <h3><?= e($stat['name']) ?></h3>
          <div class="ad-spec-main"><strong><?= $count ?></strong><span>érdeklődő<br><small><?= pct($count, $total) ?>%-a a jelentkezőknek</small></span></div>
          <div class="ad-bar"><i style="width:<?= pct($count, $maxSpecialty) ?>%"></i></div>
          <dl class="ad-spec-stats">
            <div><dt><?= icon('graduation') ?>Végzős</dt><dd><?= (int)$stat['graduating_count'] ?></dd></div>
            <div><dt><?= icon('building') ?>Kollégista</dt><dd><?= (int)$stat['dormitory_count'] ?></dd></div>
            <div><dt><?= icon('check') ?>Végzős + koll.</dt><dd><?= (int)$stat['graduating_dormitory_count'] ?></dd></div>
          </dl>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </section>

  <section class="ad-section" id="idovonal" aria-labelledby="h-ido">
    <h2 class="ad-section-title" id="h-ido"><span class="ad-h-icon"><?= icon('trend') ?></span>Alakulás és iskolák<small>Mikor érkeznek a jelentkezések, és honnan</small></h2>
    <div class="ad-two-col">
      <article class="ad-card">
        <h3><span class="ad-h-icon sm"><?= icon('calendar') ?></span>Jelentkezések az elmúlt 14 napban<small>Utolsó: <?= e($lastAt) ?></small></h3>
        <div class="ad-daily" role="img" aria-label="Napi jelentkezések az elmúlt 14 napban">
          <?php foreach ($daily as $d): $isToday = $d['date']->format('Y-m-d') === (string)$summary['db_today']; ?>
            <div class="ad-day<?= $isToday ? ' is-today' : '' ?>" title="<?= e($d['date']->format('Y. m. d.')) ?>: <?= $d['count'] ?> jelentkezés">
              <span class="ad-day-count"><?= $d['count'] ?: '' ?></span>
              <span class="ad-day-bar"><i style="height:<?= $d['count'] > 0 ? max(6, pct($d['count'], $maxDaily)) : 0 ?>%"></i></span>
              <span class="ad-day-label"><?= e($d['date']->format('m.d.')) ?><small><?= $weekdays[(int)$d['date']->format('N') - 1] ?></small></span>
            </div>
          <?php endforeach; ?>
        </div>
      </article>
      <article class="ad-card">
        <h3><span class="ad-h-icon sm"><?= icon('building') ?></span>Legtöbb jelentkező iskolánként<small><?= count($topSchools) ?> / <?= (int)$summary['schools'] ?> iskola</small></h3>
        <?php if (!$topSchools): ?><p class="ad-muted">Még nem érkezett jelentkezés.</p><?php else: ?>
        <ol class="ad-schools">
          <?php foreach ($topSchools as $school): ?>
            <li><div class="ad-school-row"><span class="ad-school-name"><?= e($school['school_name']) ?></span><span class="ad-school-meta"><?= icon('graduation') ?><?= (int)$school['graduating_count'] ?> <?= icon('building') ?><?= (int)$school['dormitory_count'] ?></span><strong><?= (int)$school['total_count'] ?></strong></div><div class="ad-bar slim"><i style="width:<?= pct((int)$school['total_count'], $maxSchool) ?>%"></i></div></li>
          <?php endforeach; ?>
        </ol>
        <p class="ad-legend-note"><?= icon('graduation') ?> végzős &nbsp; <?= icon('building') ?> kollégista</p>
        <?php endif; ?>
      </article>
    </div>
  </section>

  <section class="admin-panel" id="registrations" aria-labelledby="registrations-title"><div class="panel-heading"><div><h2 id="registrations-title"><span class="ad-h-icon"><?= icon('users') ?></span>Regisztrációk</h2><p>A név nem egyedi azonosító: az azonos nevű jelentkezések külön rekordként jelennek meg.</p></div><span class="panel-side-note">Legújabb elöl</span></div>
    <div class="registrations-tools"><form class="search-form" action="index.php#registrations" method="get"><div class="search-box"><input type="search" name="q" value="<?= e($search) ?>" placeholder="Keresés név, iskola vagy szakma alapján…" aria-label="Jelentkezések keresése"></div><button class="admin-button primary search-submit" type="submit"><?= icon('search') ?>Keresés</button><?php if ($search !== ''): ?><a class="admin-button" href="index.php#registrations">Törlés</a><?php endif; ?></form><span class="results-count"><?= $filteredCount ?> találat<?= $search !== '' ? ' a keresésre' : '' ?></span></div>
    <div class="table-wrap"><table class="admin-table"><thead><tr><th>Diák neve</th><th>Iskola</th><th>Érdeklődési körök</th><th>Tervezett lakhatás</th><th>Évfolyam</th><th>Regisztrált</th><th style="text-align:right">Művelet</th></tr></thead><tbody>
      <?php if (!$registrations): ?><tr><td colspan="7" class="table-empty"><?= $search !== '' ? 'Nincs találat erre a keresésre.' : 'Még nem érkezett jelentkezés.' ?></td></tr><?php else: foreach ($registrations as $registration): $names = $registration['specialty_names'] ? explode('||', $registration['specialty_names']) : []; ?><tr><td class="student-name"><?= e($registration['full_name']) ?><span class="ad-id">#<?= (int)$registration['id'] ?></span></td><td class="school-cell"><?= e($registration['school_name']) ?></td><td class="specialties-cell"><?php if ($names): foreach ($names as $name): ?><span class="mini-tag"><?= e($name) ?></span><?php endforeach; else: ?><span class="ad-muted">Nincs megadva</span><?php endif; ?></td><td><span class="status-pill <?= $registration['accommodation'] === 'dormitory' ? 'status-dorm' : 'status-commuter' ?>"><?= icon($registration['accommodation'] === 'dormitory' ? 'building' : 'home') ?><?= $registration['accommodation'] === 'dormitory' ? 'Kollégista' : 'Bejárós' ?></span></td><td><span class="status-pill <?= (int)$registration['is_graduating'] === 1 ? 'status-graduate' : 'status-not-graduate' ?>"><?= icon((int)$registration['is_graduating'] === 1 ? 'graduation' : 'route') ?><?= (int)$registration['is_graduating'] === 1 ? 'Végzős' : 'Még nem' ?></span></td><td class="date-cell"><?= e(date('Y. m. d. · H:i', strtotime((string)$registration['created_at']))) ?></td><td><div class="row-actions"><form action="delete.php" method="post" class="delete-form"><input type="hidden" name="csrf_token" value="<?= e($csrf) ?>"><input type="hidden" name="registration_id" value="<?= (int)$registration['id'] ?>"><input type="hidden" name="page" value="<?= $page ?>"><input type="hidden" name="q" value="<?= e($search) ?>"><button type="submit" class="icon-delete" title="Jelentkezés törlése" aria-label="<?= e($registration['full_name']) ?> jelentkezésének törlése"><?= icon('trash') ?></button></form></div></td></tr><?php endforeach; endif; ?>
    </tbody></table></div>
    <?php if ($totalPages > 1): ?><nav class="pagination" aria-label="Oldalválasztás"><span class="pagination-info"><?= $filteredCount ? $offset + 1 : 0 ?>–<?= min($offset + $perPage, $filteredCount) ?> / <?= $filteredCount ?> jelentkezés</span><div class="pagination-links"><?php $prev = max(1, $page - 1); $next = min($totalPages, $page + 1); ?><a class="page-link <?= $page <= 1 ? 'disabled' : '' ?>" href="?page=<?= $prev ?>&amp;q=<?= rawurlencode($search) ?>#registrations" aria-label="Előző oldal">‹</a><?php for ($p = max(1, $page - 2); $p <= min($totalPages, $page + 2); $p++): ?><a class="page-link <?= $p === $page ? 'active' : '' ?>" href="?page=<?= $p ?>&amp;q=<?= rawurlencode($search) ?>#registrations" <?= $p === $page ? 'aria-current="page"' : '' ?>><?= $p ?></a><?php endfor; ?><a class="page-link <?= $page >= $totalPages ? 'disabled' : '' ?>" href="?page=<?= $next ?>&amp;q=<?= rawurlencode($search) ?>#registrations" aria-label="Következő oldal">›</a></div></nav><?php endif; ?>
  </section>
  <footer class="admin-footer">Adminfelület · <?= e(SCHOOL_NAME) ?> · <a class="ad-footlink" href="../index.php">Vissza a nyílt nap oldalára</a></footer>
</main>
<script>
document.querySelectorAll('.delete-form').forEach(form => form.addEventListener('submit', event => {
  const name = form.closest('tr')?.querySelector('.student-name')?.childNodes?.[0]?.textContent?.trim() || 'Ez a jelentkezés';
  if (!window.confirm(`Biztosan törlöd ezt a jelentkezést: ${name}? A művelet nem vonható vissza.`)) event.preventDefault();
}));
</script>
</body></html>

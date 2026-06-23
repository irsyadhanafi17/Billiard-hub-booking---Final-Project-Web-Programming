<?php
// ============================================================
//  DATA MEJA BILLIARD — menggunakan Array PHP
// ============================================================

$meja_billiard = [
    [
        'id'          => 1,
        'nama'        => 'Meja 1',
        'kode'        => 'A-01',
        'tipe'        => 'Standard',
        'ukuran'      => '7 Feet',
        'kondisi'     => 'Baik',
        'harga_jam'   => 25000,
        'status'      => 'tersedia',
        'lokasi'      => 'Lantai 1 - Zona A',
        'fasilitas'   => ['Stick Set', 'Chalk', 'Triangle'],
    ],
    [
        'id'          => 2,
        'nama'        => 'Meja 2',
        'kode'        => 'A-02',
        'tipe'        => 'Standard',
        'ukuran'      => '7 Feet',
        'kondisi'     => 'Baik',
        'harga_jam'   => 25000,
        'status'      => 'dipakai',
        'lokasi'      => 'Lantai 1 - Zona A',
        'fasilitas'   => ['Stick Set', 'Chalk', 'Triangle'],
    ],
    [
        'id'          => 3,
        'nama'        => 'Meja 3',
        'kode'        => 'A-03',
        'tipe'        => 'Standard',
        'ukuran'      => '8 Feet',
        'kondisi'     => 'Baik',
        'harga_jam'   => 30000,
        'status'      => 'tersedia',
        'lokasi'      => 'Lantai 1 - Zona A',
        'fasilitas'   => ['Stick Set', 'Chalk', 'Triangle', 'Score Board'],
    ],
    [
        'id'          => 4,
        'nama'        => 'Meja 4',
        'kode'        => 'B-01',
        'tipe'        => 'VIP',
        'ukuran'      => '9 Feet',
        'kondisi'     => 'Sangat Baik',
        'harga_jam'   => 45000,
        'status'      => 'tersedia',
        'lokasi'      => 'Lantai 1 - Zona B (VIP)',
        'fasilitas'   => ['Stick Premium', 'Chalk', 'Triangle', 'Score Board', 'Sofa', 'AC'],
    ],
    [
        'id'          => 5,
        'nama'        => 'Meja 5',
        'kode'        => 'B-02',
        'tipe'        => 'VIP',
        'ukuran'      => '9 Feet',
        'kondisi'     => 'Sangat Baik',
        'harga_jam'   => 45000,
        'status'      => 'dipakai',
        'lokasi'      => 'Lantai 1 - Zona B (VIP)',
        'fasilitas'   => ['Stick Premium', 'Chalk', 'Triangle', 'Score Board', 'Sofa', 'AC'],
    ],
    [
        'id'          => 6,
        'nama'        => 'Meja 6',
        'kode'        => 'B-03',
        'tipe'        => 'VIP',
        'ukuran'      => '9 Feet',
        'kondisi'     => 'Baik',
        'harga_jam'   => 45000,
        'status'      => 'maintenance',
        'lokasi'      => 'Lantai 1 - Zona B (VIP)',
        'fasilitas'   => ['Stick Premium', 'Chalk', 'Triangle', 'Score Board', 'Sofa', 'AC'],
    ],
    [
        'id'          => 7,
        'nama'        => 'Meja 7',
        'kode'        => 'C-01',
        'tipe'        => 'Premium',
        'ukuran'      => '9 Feet',
        'kondisi'     => 'Sangat Baik',
        'harga_jam'   => 65000,
        'status'      => 'tersedia',
        'lokasi'      => 'Lantai 2 - Zona Premium',
        'fasilitas'   => ['Stick Tournament', 'Chalk', 'Triangle', 'Digital Score', 'Sofa', 'AC', 'Mini Bar'],
    ],
    [
        'id'          => 8,
        'nama'        => 'Meja 8',
        'kode'        => 'C-02',
        'tipe'        => 'Premium',
        'ukuran'      => '9 Feet',
        'kondisi'     => 'Sangat Baik',
        'harga_jam'   => 65000,
        'status'      => 'tersedia',
        'lokasi'      => 'Lantai 2 - Zona Premium',
        'fasilitas'   => ['Stick Tournament', 'Chalk', 'Triangle', 'Digital Score', 'Sofa', 'AC', 'Mini Bar'],
    ],
];

// ============================================================
//  FUNGSI HELPER
// ============================================================

/** Format angka ke Rupiah */
function formatRupiah(int $angka): string {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/** Hitung ringkasan statistik dari array meja */
function hitungStatistik(array $meja): array {
    $total      = count($meja);
    $tersedia   = 0;
    $dipakai    = 0;
    $maintenance = 0;

    foreach ($meja as $m) {
        if ($m['status'] === 'tersedia')    $tersedia++;
        elseif ($m['status'] === 'dipakai') $dipakai++;
        else                                 $maintenance++;
    }

    return compact('total', 'tersedia', 'dipakai', 'maintenance');
}

/** Filter meja berdasarkan tipe */
function filterByTipe(array $meja, string $tipe): array {
    if ($tipe === 'semua') return $meja;
    return array_filter($meja, fn($m) => strtolower($m['tipe']) === strtolower($tipe));
}

/** Filter meja berdasarkan status */
function filterByStatus(array $meja, string $status): array {
    if ($status === 'semua') return $meja;
    return array_filter($meja, fn($m) => $m['status'] === $status);
}

/** Urutkan meja berdasarkan harga */
function urutkanByHarga(array $meja, string $arah = 'asc'): array {
    usort($meja, fn($a, $b) => $arah === 'asc'
        ? $a['harga_jam'] <=> $b['harga_jam']
        : $b['harga_jam'] <=> $a['harga_jam']
    );
    return $meja;
}

/** Ambil meja berdasarkan ID */
function getMejaById(array $meja, int $id): ?array {
    foreach ($meja as $m) {
        if ($m['id'] === $id) return $m;
    }
    return null;
}

/** CSS class badge status */
function badgeStatus(string $status): string {
    return match ($status) {
        'tersedia'    => 'badge-tersedia',
        'dipakai'     => 'badge-dipakai',
        'maintenance' => 'badge-maintenance',
        default       => 'badge-tersedia',
    };
}

/** Label status */
function labelStatus(string $status): string {
    return match ($status) {
        'tersedia'    => '✅ Tersedia',
        'dipakai'     => '🔴 Dipakai',
        'maintenance' => '🔧 Maintenance',
        default       => ucfirst($status),
    };
}

// ============================================================
//  PROSES FILTER DARI GET
// ============================================================

$filterTipe   = $_GET['tipe']   ?? 'semua';
$filterStatus = $_GET['status'] ?? 'semua';
$sortHarga    = $_GET['sort']   ?? '';

$tampil = $meja_billiard;
$tampil = filterByTipe($tampil, $filterTipe);
$tampil = filterByStatus($tampil, $filterStatus);
if ($sortHarga === 'murah')  $tampil = urutkanByHarga($tampil, 'asc');
if ($sortHarga === 'mahal')  $tampil = urutkanByHarga($tampil, 'desc');

$stats = hitungStatistik($meja_billiard);

// Detail meja (jika ada ?detail=id)
$detailMeja = null;
if (isset($_GET['detail'])) {
    $detailMeja = getMejaById($meja_billiard, (int) $_GET['detail']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>List Meja Billiard — PHP Array</title>
<style>
/* ── RESET ─────────────────────────────────────────── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

/* ── TOKENS ─────────────────────────────────────────── */
:root{
  --felt:      #1e5631;
  --felt-mid:  #2d7a47;
  --felt-lite: #3da85e;
  --chalk:     #f0ede4;
  --cue:       #8b5e3c;
  --cue-lite:  #c4956a;
  --ball-red:  #c1121f;
  --ball-yel:  #e9c46a;
  --ball-blue: #1d4e89;
  --ink:       #1a1a1a;
  --ink-mid:   #555;
  --ink-lite:  #999;
  --white:     #fff;
  --surface:   #f7f5ef;
  --radius:    10px;
  --shadow:    0 2px 16px rgba(0,0,0,.1);
  --shadow-lg: 0 6px 32px rgba(0,0,0,.16);
}

body{
  font-family:'Segoe UI',system-ui,sans-serif;
  background:var(--surface);
  color:var(--ink);
  min-height:100vh;
}

/* ── HEADER ─────────────────────────────────────────── */
.header{
  background:linear-gradient(135deg,#0d2818 0%,var(--felt) 55%,var(--felt-mid) 100%);
  border-bottom:3px solid var(--ball-yel);
  padding:1.25rem 2rem;
}
.header-inner{
  max-width:1200px;margin:0 auto;
  display:flex;align-items:center;justify-content:space-between;
  gap:1rem;flex-wrap:wrap;
}
.brand{display:flex;align-items:center;gap:.9rem}
.brand-ball{
  width:46px;height:46px;border-radius:50%;
  background:radial-gradient(circle at 35% 35%,#fff 0%,var(--ball-red) 45%,#7a0007 100%);
  box-shadow:0 0 16px rgba(193,18,31,.5);
  display:flex;align-items:center;justify-content:center;
  font-weight:900;color:#fff;font-size:1.15rem;
  flex-shrink:0;
}
.brand-text h1{
  color:#fff;font-size:1.3rem;font-weight:800;
  letter-spacing:.5px;line-height:1.1;
}
.brand-text span{
  color:var(--ball-yel);font-size:.7rem;
  letter-spacing:2px;text-transform:uppercase;
}
.header-info{color:rgba(255,255,255,.6);font-size:.82rem;text-align:right}
.header-info strong{color:var(--ball-yel)}

/* ── PAGE BODY ─────────────────────────────────────────── */
.wrap{max-width:1200px;margin:0 auto;padding:2rem}

/* ── STATS ─────────────────────────────────────────── */
.stats-row{
  display:grid;grid-template-columns:repeat(4,1fr);
  gap:1rem;margin-bottom:2rem;
}
.stat{
  background:var(--white);border-radius:var(--radius);
  padding:1.1rem 1.4rem;box-shadow:var(--shadow);
  display:flex;align-items:center;gap:.9rem;
}
.stat-dot{
  width:48px;height:48px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:1.4rem;flex-shrink:0;
}
.dot-total    {background:#e8f5e9}
.dot-tersedia {background:#e0f7e9}
.dot-dipakai  {background:#fce4ec}
.dot-maint    {background:#fff3e0}
.stat-num{font-size:1.8rem;font-weight:800;color:var(--ink);line-height:1}
.stat-lbl{font-size:.73rem;color:var(--ink-lite);text-transform:uppercase;letter-spacing:.4px;margin-top:2px}

/* ── CONTROLS ─────────────────────────────────────────── */
.controls{
  background:var(--white);border-radius:var(--radius);
  padding:1rem 1.25rem;box-shadow:var(--shadow);
  display:flex;gap:.75rem;flex-wrap:wrap;align-items:center;
  margin-bottom:1.5rem;
}
.ctrl-label{font-size:.8rem;font-weight:600;color:var(--ink-mid);white-space:nowrap}
select,input{
  padding:.45rem .8rem;border:1.5px solid #ddd;
  border-radius:7px;font-size:.85rem;color:var(--ink);
  background:#fafaf8;outline:none;cursor:pointer;
  transition:border-color .2s;
}
select:focus,input:focus{border-color:var(--felt-mid)}
.btn{
  display:inline-flex;align-items:center;gap:5px;
  padding:.45rem 1rem;border:none;border-radius:7px;
  font-size:.83rem;font-weight:600;cursor:pointer;
  text-decoration:none;transition:all .18s;
}
.btn-green{background:var(--felt);color:#fff}
.btn-green:hover{background:#0d2818}
.btn-ghost{background:#eee;color:var(--ink-mid)}
.btn-ghost:hover{background:#ddd}
.btn-detail{background:var(--felt);color:#fff;font-size:.75rem;padding:.35rem .85rem}
.btn-detail:hover{background:#0d2818}

/* ── RESULT COUNT ─────────────────────────────────────── */
.result-info{
  font-size:.83rem;color:var(--ink-mid);
  margin-bottom:.9rem;padding-left:.2rem;
}
.result-info strong{color:var(--felt);font-size:1rem}

/* ── GRID KARTU MEJA ─────────────────────────────────── */
.meja-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(290px,1fr));
  gap:1.25rem;
}

.meja-card{
  background:var(--white);border-radius:var(--radius);
  box-shadow:var(--shadow);overflow:hidden;
  transition:transform .2s,box-shadow .2s;
  display:flex;flex-direction:column;
}
.meja-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-lg)}

.card-top{
  padding:1rem 1.25rem .75rem;
  border-bottom:1px solid #f0ede4;
  display:flex;align-items:flex-start;justify-content:space-between;gap:.5rem;
}
.meja-nama{font-size:1.05rem;font-weight:800;color:var(--ink)}
.meja-kode{font-size:.72rem;color:var(--ink-lite);letter-spacing:.5px}

.badge{
  display:inline-block;padding:3px 10px;
  border-radius:20px;font-size:.7rem;font-weight:700;
  text-transform:uppercase;letter-spacing:.3px;white-space:nowrap;
}
.badge-tersedia  {background:#d1fae5;color:#065f46}
.badge-dipakai   {background:#fee2e2;color:#991b1b}
.badge-maintenance{background:#fef3c7;color:#92400e}

.card-mid{padding:.75rem 1.25rem;flex:1}
.info-row{
  display:flex;justify-content:space-between;align-items:center;
  padding:.3rem 0;border-bottom:1px dashed #f0ede4;font-size:.84rem;
}
.info-row:last-child{border:none}
.info-key{color:var(--ink-mid)}
.info-val{font-weight:600;color:var(--ink)}

.tipe-pill{
  display:inline-block;padding:2px 9px;border-radius:20px;
  font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.3px;
}
.tipe-Standard{background:#dbeafe;color:#1e40af}
.tipe-VIP     {background:#f3e8ff;color:#6b21a8}
.tipe-Premium {background:#fef9c3;color:#854d0e}

.harga-besar{
  font-size:1.15rem;font-weight:800;color:var(--felt-mid)
}
.harga-satuan{font-size:.7rem;font-weight:400;color:var(--ink-lite)}

.fasilitas-wrap{padding:.6rem 1.25rem .4rem;border-top:1px solid #f0ede4}
.fasilitas-label{font-size:.7rem;color:var(--ink-lite);text-transform:uppercase;letter-spacing:.5px;margin-bottom:.35rem}
.fasilitas-list{display:flex;flex-wrap:wrap;gap:.3rem}
.fas-tag{
  background:#f0faf5;border:1px solid #c6e9d5;
  color:var(--felt-mid);padding:2px 8px;
  border-radius:5px;font-size:.71rem;font-weight:500;
}

.card-bot{
  padding:.75rem 1.25rem;background:#fafaf8;
  display:flex;align-items:center;justify-content:space-between;
  border-top:1px solid #f0ede4;
}
.lokasi-txt{font-size:.76rem;color:var(--ink-lite)}

/* ── EMPTY STATE ─────────────────────────────────────── */
.empty{
  text-align:center;padding:3rem 1rem;
  background:var(--white);border-radius:var(--radius);
  box-shadow:var(--shadow);
}
.empty .big{font-size:3rem;margin-bottom:.5rem}
.empty p{color:var(--ink-lite);font-size:.9rem}

/* ── MODAL DETAIL ────────────────────────────────────── */
.overlay{
  position:fixed;inset:0;background:rgba(0,0,0,.55);
  display:flex;align-items:center;justify-content:center;
  z-index:200;padding:1rem;
}
.modal{
  background:var(--white);border-radius:14px;
  max-width:520px;width:100%;box-shadow:var(--shadow-lg);
  overflow:hidden;
}
.modal-head{
  background:linear-gradient(135deg,#0d2818,var(--felt-mid));
  padding:1.2rem 1.5rem;
  display:flex;align-items:center;justify-content:space-between;
}
.modal-head h2{color:#fff;font-size:1.1rem;font-weight:700}
.modal-close{
  background:rgba(255,255,255,.15);border:none;
  color:#fff;width:30px;height:30px;border-radius:50%;
  font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;
  text-decoration:none;transition:background .15s;
}
.modal-close:hover{background:rgba(255,255,255,.3)}
.modal-body{padding:1.5rem}
.modal-row{
  display:flex;gap:.5rem;padding:.55rem 0;
  border-bottom:1px dashed #eee;font-size:.88rem;
}
.modal-row:last-child{border:none}
.modal-key{width:130px;flex-shrink:0;color:var(--ink-mid);font-weight:500}
.modal-val{flex:1;font-weight:600;color:var(--ink)}
.modal-foot{
  padding:1rem 1.5rem;background:#fafaf8;
  border-top:1px solid #eee;
  display:flex;justify-content:flex-end;
}

/* ── SECTION TITLE ───────────────────────────────────── */
.section-title{
  font-size:.7rem;font-weight:700;letter-spacing:2px;
  text-transform:uppercase;color:var(--felt-mid);
  margin-bottom:1.2rem;display:flex;align-items:center;gap:.5rem;
}
.section-title::after{
  content:'';flex:1;height:1px;background:#ddd;
}

/* ── TABLE VIEW (mode tabel) ─────────────────────────── */
.tbl{width:100%;border-collapse:collapse;font-size:.85rem}
.tbl thead tr{background:linear-gradient(135deg,#0d2818,var(--felt-mid))}
.tbl thead th{
  color:#fff;padding:.7rem 1rem;text-align:left;
  font-size:.74rem;text-transform:uppercase;letter-spacing:.4px;font-weight:600;
  white-space:nowrap;
}
.tbl tbody tr:nth-child(even){background:#fafaf8}
.tbl tbody tr:hover{background:#f0faf5}
.tbl td{padding:.65rem 1rem;border-bottom:1px solid #f0ede4;vertical-align:middle}
.tbl-wrap{overflow-x:auto}

/* ── VIEW TOGGLE ─────────────────────────────────────── */
.view-toggle{display:flex;gap:.4rem}
.vtoggle{
  width:34px;height:34px;border:1.5px solid #ddd;border-radius:7px;
  background:#fff;cursor:pointer;display:flex;align-items:center;
  justify-content:center;font-size:1rem;transition:all .15s;
}
.vtoggle.active{background:var(--felt);border-color:var(--felt);color:#fff}

/* ── RESPONSIVE ─────────────────────────────────────── */
@media(max-width:900px){.stats-row{grid-template-columns:repeat(2,1fr)}}
@media(max-width:580px){
  .wrap{padding:1rem}
  .stats-row{grid-template-columns:1fr 1fr}
  .header-inner{flex-direction:column;align-items:flex-start}
  .meja-grid{grid-template-columns:1fr}
}
</style>
</head>
<body>

<!-- HEADER -->
<header class="header">
  <div class="header-inner">
    <div class="brand">
      <div class="brand-ball">8</div>
      <div class="brand-text">
        <h1>Billiard Club</h1>
        <span>Table Management System</span>
      </div>
    </div>
    <div class="header-info">
      📅 <?= date('l, d F Y') ?><br>
      Total Meja: <strong><?= count($meja_billiard) ?></strong>
    </div>
  </div>
</header>

<!-- MODAL DETAIL MEJA -->
<?php if ($detailMeja): ?>
<div class="overlay">
  <div class="modal">
    <div class="modal-head">
      <h2>🎱 Detail <?= $detailMeja['nama'] ?> <span style="opacity:.7;font-weight:400">(<?= $detailMeja['kode'] ?>)</span></h2>
      <a href="?" class="modal-close">✕</a>
    </div>
    <div class="modal-body">
      <div class="modal-row">
        <span class="modal-key">Nama Meja</span>
        <span class="modal-val"><?= $detailMeja['nama'] ?></span>
      </div>
      <div class="modal-row">
        <span class="modal-key">Kode</span>
        <span class="modal-val"><?= $detailMeja['kode'] ?></span>
      </div>
      <div class="modal-row">
        <span class="modal-key">Tipe</span>
        <span class="modal-val">
          <span class="tipe-pill tipe-<?= $detailMeja['tipe'] ?>"><?= $detailMeja['tipe'] ?></span>
        </span>
      </div>
      <div class="modal-row">
        <span class="modal-key">Ukuran</span>
        <span class="modal-val"><?= $detailMeja['ukuran'] ?></span>
      </div>
      <div class="modal-row">
        <span class="modal-key">Kondisi</span>
        <span class="modal-val"><?= $detailMeja['kondisi'] ?></span>
      </div>
      <div class="modal-row">
        <span class="modal-key">Harga/Jam</span>
        <span class="modal-val" style="color:var(--felt-mid);font-size:1.05rem"><?= formatRupiah($detailMeja['harga_jam']) ?></span>
      </div>
      <div class="modal-row">
        <span class="modal-key">Status</span>
        <span class="modal-val"><span class="badge badge-<?= $detailMeja['status'] ?>"><?= labelStatus($detailMeja['status']) ?></span></span>
      </div>
      <div class="modal-row">
        <span class="modal-key">Lokasi</span>
        <span class="modal-val"><?= $detailMeja['lokasi'] ?></span>
      </div>
      <div class="modal-row">
        <span class="modal-key">Fasilitas</span>
        <span class="modal-val">
          <div style="display:flex;flex-wrap:wrap;gap:.3rem">
            <?php foreach ($detailMeja['fasilitas'] as $f): ?>
            <span class="fas-tag"><?= $f ?></span>
            <?php endforeach; ?>
          </div>
        </span>
      </div>
    </div>
    <div class="modal-foot">
      <a href="?" class="btn btn-ghost">Tutup</a>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- PAGE -->
<div class="wrap">

  <!-- STATISTIK -->
  <div class="stats-row">
    <div class="stat">
      <div class="stat-dot dot-total">🎱</div>
      <div>
        <div class="stat-num"><?= $stats['total'] ?></div>
        <div class="stat-lbl">Total Meja</div>
      </div>
    </div>
    <div class="stat">
      <div class="stat-dot dot-tersedia">✅</div>
      <div>
        <div class="stat-num"><?= $stats['tersedia'] ?></div>
        <div class="stat-lbl">Tersedia</div>
      </div>
    </div>
    <div class="stat">
      <div class="stat-dot dot-dipakai">🔴</div>
      <div>
        <div class="stat-num"><?= $stats['dipakai'] ?></div>
        <div class="stat-lbl">Sedang Dipakai</div>
      </div>
    </div>
    <div class="stat">
      <div class="stat-dot dot-maint">🔧</div>
      <div>
        <div class="stat-num"><?= $stats['maintenance'] ?></div>
        <div class="stat-lbl">Maintenance</div>
      </div>
    </div>
  </div>

  <!-- KONTROL FILTER -->
  <form method="GET" class="controls">
    <span class="ctrl-label">Filter:</span>

    <select name="tipe">
      <option value="semua" <?= $filterTipe==='semua'?'selected':'' ?>>Semua Tipe</option>
      <option value="Standard" <?= $filterTipe==='Standard'?'selected':'' ?>>Standard</option>
      <option value="VIP"      <?= $filterTipe==='VIP'?'selected':'' ?>>VIP</option>
      <option value="Premium"  <?= $filterTipe==='Premium'?'selected':'' ?>>Premium</option>
    </select>

    <select name="status">
      <option value="semua"       <?= $filterStatus==='semua'?'selected':'' ?>>Semua Status</option>
      <option value="tersedia"    <?= $filterStatus==='tersedia'?'selected':'' ?>>Tersedia</option>
      <option value="dipakai"     <?= $filterStatus==='dipakai'?'selected':'' ?>>Dipakai</option>
      <option value="maintenance" <?= $filterStatus==='maintenance'?'selected':'' ?>>Maintenance</option>
    </select>

    <select name="sort">
      <option value="" <?= $sortHarga===''?'selected':'' ?>>Urutan Default</option>
      <option value="murah" <?= $sortHarga==='murah'?'selected':'' ?>>Harga Termurah</option>
      <option value="mahal" <?= $sortHarga==='mahal'?'selected':'' ?>>Harga Termahal</option>
    </select>

    <!-- View mode -->
    <input type="hidden" name="view" id="viewInput" value="<?= htmlspecialchars($_GET['view'] ?? 'card') ?>">

    <button type="submit" class="btn btn-green">🔍 Terapkan</button>
    <a href="?" class="btn btn-ghost">↺ Reset</a>

    <div class="view-toggle" style="margin-left:auto">
      <button type="button" class="vtoggle <?= ($_GET['view']??'card')==='card'?'active':'' ?>"
        title="Kartu" onclick="setView('card')">▦</button>
      <button type="button" class="vtoggle <?= ($_GET['view']??'card')==='table'?'active':'' ?>"
        title="Tabel" onclick="setView('table')">☰</button>
    </div>
  </form>

  <!-- JUMLAH HASIL -->
  <div class="result-info">
    Menampilkan <strong><?= count($tampil) ?></strong> dari <?= count($meja_billiard) ?> meja
    <?= $filterTipe!=='semua' ? "· Tipe: <strong>$filterTipe</strong>" : '' ?>
    <?= $filterStatus!=='semua' ? "· Status: <strong>".ucfirst($filterStatus)."</strong>" : '' ?>
  </div>

  <?php $viewMode = $_GET['view'] ?? 'card'; ?>

  <!-- ======================================================
       VIEW: KARTU
       ====================================================== -->
  <?php if ($viewMode === 'card'): ?>

  <?php if (empty($tampil)): ?>
  <div class="empty">
    <div class="big">🎱</div>
    <p>Tidak ada meja yang sesuai filter.</p>
  </div>
  <?php else: ?>

  <div class="section-title">Daftar Meja Billiard</div>
  <div class="meja-grid">
    <?php foreach ($tampil as $m): ?>
    <div class="meja-card">

      <!-- TOP: nama + status -->
      <div class="card-top">
        <div>
          <div class="meja-nama"><?= $m['nama'] ?></div>
          <div class="meja-kode"><?= $m['kode'] ?> &nbsp;·&nbsp;
            <span class="tipe-pill tipe-<?= $m['tipe'] ?>"><?= $m['tipe'] ?></span>
          </div>
        </div>
        <span class="badge badge-<?= $m['status'] ?>"><?= labelStatus($m['status']) ?></span>
      </div>

      <!-- MID: info baris -->
      <div class="card-mid">
        <div class="info-row">
          <span class="info-key">Ukuran</span>
          <span class="info-val"><?= $m['ukuran'] ?></span>
        </div>
        <div class="info-row">
          <span class="info-key">Kondisi</span>
          <span class="info-val"><?= $m['kondisi'] ?></span>
        </div>
        <div class="info-row">
          <span class="info-key">Harga / Jam</span>
          <span class="info-val harga-besar">
            <?= formatRupiah($m['harga_jam']) ?>
            <span class="harga-satuan">/jam</span>
          </span>
        </div>
      </div>

      <!-- FASILITAS -->
      <div class="fasilitas-wrap">
        <div class="fasilitas-label">Fasilitas</div>
        <div class="fasilitas-list">
          <?php foreach ($m['fasilitas'] as $f): ?>
          <span class="fas-tag"><?= $f ?></span>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- BOTTOM: lokasi + tombol detail -->
      <div class="card-bot">
        <span class="lokasi-txt">📍 <?= $m['lokasi'] ?></span>
        <a href="?detail=<?= $m['id'] ?>&tipe=<?= urlencode($filterTipe) ?>&status=<?= urlencode($filterStatus) ?>&sort=<?= urlencode($sortHarga) ?>&view=card"
           class="btn btn-detail">Detail</a>
      </div>

    </div>
    <?php endforeach; ?>
  </div>

  <?php endif; ?>

  <!-- ======================================================
       VIEW: TABEL
       ====================================================== -->
  <?php else: ?>

  <div class="section-title">Daftar Meja Billiard — Tampilan Tabel</div>

  <?php if (empty($tampil)): ?>
  <div class="empty">
    <div class="big">🎱</div>
    <p>Tidak ada meja yang sesuai filter.</p>
  </div>
  <?php else: ?>

  <div style="background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden">
    <div class="tbl-wrap">
      <table class="tbl">
        <thead>
          <tr>
            <th>Kode</th>
            <th>Nama Meja</th>
            <th>Tipe</th>
            <th>Ukuran</th>
            <th>Kondisi</th>
            <th>Harga/Jam</th>
            <th>Lokasi</th>
            <th>Fasilitas</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tampil as $m): ?>
          <tr>
            <td style="font-weight:700;color:var(--ink-mid)"><?= $m['kode'] ?></td>
            <td style="font-weight:700"><?= $m['nama'] ?></td>
            <td><span class="tipe-pill tipe-<?= $m['tipe'] ?>"><?= $m['tipe'] ?></span></td>
            <td><?= $m['ukuran'] ?></td>
            <td><?= $m['kondisi'] ?></td>
            <td style="font-weight:700;color:var(--felt-mid)"><?= formatRupiah($m['harga_jam']) ?></td>
            <td style="font-size:.8rem;color:var(--ink-mid)"><?= $m['lokasi'] ?></td>
            <td>
              <div style="display:flex;flex-wrap:wrap;gap:.25rem">
                <?php foreach ($m['fasilitas'] as $f): ?>
                <span class="fas-tag"><?= $f ?></span>
                <?php endforeach; ?>
              </div>
            </td>
            <td><span class="badge badge-<?= $m['status'] ?>"><?= labelStatus($m['status']) ?></span></td>
            <td>
              <a href="?detail=<?= $m['id'] ?>&view=table&tipe=<?= urlencode($filterTipe) ?>&status=<?= urlencode($filterStatus) ?>&sort=<?= urlencode($sortHarga) ?>"
                 class="btn btn-detail">Detail</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php endif; ?>
  <?php endif; ?>

</div><!-- /wrap -->

<script>
function setView(v) {
    document.getElementById('viewInput').value = v;
    document.querySelector('form.controls').submit();
}
</script>

</body>
</html>
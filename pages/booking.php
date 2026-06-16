<?php
// pages/booking.php

// W13: Proteksi ganda, pastikan yang akses beneran customer yang sah
if (!isset($_SESSION["role"]) || $_SESSION["role"] != 'customer') {
    echo "<script>alert('Akses ilegal!');</script>";
    echo "<script>window.location='index.php?p=login';</script>";
    exit();
}

require_once('./class/class.Outlet.php');
require_once('./class/class.BilliardTable.php');
require_once('./class/class.Booking.php');

$objOutlet = new Outlet();
$objTable = new BilliardTable();
$objBooking = new Booking();

$arrOutlet = $objOutlet->SelectAllOutlet();
$arrTable = [];
$selected_outlet = '';

// Logika dinamis: Jika cabang outlet dipilih, load meja spesifik cabang tersebut (Pola W12)
if (isset($_POST['outlet_id']) && $_POST['outlet_id'] != '') {
    $selected_outlet = $_POST['outlet_id'];
    $arrTable = $objTable->SelectTablesByOutlet($selected_outlet);
}

// Proses ketika tombol 'Book Now' ditekan (Transaksi INSERT - Pola W12)
if (isset($_POST['btnSubmit'])) {
    $objBooking->userid = $_SESSION['userid']; // Diambil otomatis dari session login (W13)
    $objBooking->table_id = $_POST['table_id'];
    $objBooking->booking_date = $_POST['booking_date'];
    $objBooking->start_time = $_POST['start_time'];
    $objBooking->duration_hours = $_POST['duration_hours'];

    $objBooking->AddBooking();

    echo "<script>alert('$objBooking->message');</script>";
    if ($objBooking->hasil) {
        // Redirect ke riwayat booking
        echo '<script>window.location = "dashboardcustomer.php?p=mybookings";</script>';
    }
}
?>

<!-- ── GOOGLE FONTS ─────────────────────────────────────────── -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,700&family=EB+Garamond:wght@400;500;600&family=Crimson+Text:wght@400;600&display=swap" rel="stylesheet">

<style>
    /* ── Reset & Base ──────────────────────────────────────────── */
    body {
        background-color: #0D0D0D;
        font-family: 'Crimson Text', Georgia, serif;
    }

    /* ── Outer wrapper ─────────────────────────────────────────── */
    .afterhour-container {
        margin-top: 0;
        margin-bottom: 0;
        min-height: 100vh;
        display: flex;        /* flexbox agar dua kolom sama tinggi */
        align-items: stretch;
    }

    /* ═══════════════════════════════════════════════════════════
       LEFT COLUMN — Hero Brand Section
    ═══════════════════════════════════════════════════════════ */
    .brand-section {
        position: relative;
        overflow: hidden;
        padding: 0;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;

        /* ── FOTO LATAR ──────────────────────────────────────────
           Ganti path di bawah dengan path foto biliar lo.
           Contoh kalau pakai assets/img/:
           background-image: url('../assets/img/billiard-room.jpg');
        ─────────────────────────────────────────────────────── */
        background-image: url('assets/Afterhour (1).png');
        background-size: cover;
        background-position: center 25%;
        min-height: 100vh;
    }

    /* Gradient scrim di atas foto */
    .brand-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            160deg,
            rgba(13, 13, 13, 0.65) 0%,
            rgba(74, 14, 23, 0.60) 45%,
            rgba(13, 13, 13, 0.88) 100%
        );
        z-index: 1;
    }

    /* Garis emas vertikal pemisah kanan */
    .brand-section::after {
        content: '';
        position: absolute;
        top: 60px;
        bottom: 60px;
        right: 0;
        width: 1px;
        background: linear-gradient(
            to bottom,
            transparent,
            rgba(212, 175, 55, 0.45) 20%,
            rgba(212, 175, 55, 0.45) 80%,
            transparent
        );
        z-index: 2;
    }

    /* Konten brand menumpuk di atas scrim */
    .brand-inner {
        position: relative;
        z-index: 3;
        padding: 60px 52px;
    }

    /* Eyebrow line */
    .brand-eyebrow {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 22px;
    }
    .brand-eyebrow-line {
        height: 1px;
        width: 32px;
        background: #D4AF37;
        opacity: 0.6;
    }
    .brand-eyebrow-text {
        font-family: 'EB Garamond', serif;
        font-size: 11px;
        letter-spacing: 0.28em;
        text-transform: uppercase;
        color: rgba(212, 175, 55, 0.6);
        margin: 0;
    }

    /* Logotype */
    .neon-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-style: italic;
        font-size: 68px;
        letter-spacing: -0.02em;
        line-height: 1;
        color: #D4AF37;
        text-shadow: 0 4px 40px rgba(212, 175, 55, 0.30);
        margin: 0 0 14px;
    }

    /* Subtitle */
    .neon-subtitle {
        font-family: 'EB Garamond', serif;
        font-size: 16px;
        letter-spacing: 0.08em;
        color: rgba(232, 220, 200, 0.65);
        text-transform: none;
        margin-bottom: 40px;
        text-shadow: none;
    }

    /* Divider emas tipis */
    .brand-divider {
        height: 1px;
        background: linear-gradient(
            to right,
            rgba(212, 175, 55, 0.30),
            transparent
        );
        margin-bottom: 28px;
    }


    /* ═══════════════════════════════════════════════════════════
       RIGHT COLUMN — Form Section
    ═══════════════════════════════════════════════════════════ */
    .form-section {
        background-color: #0D0D0D;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 56px 60px;
        min-height: 100vh;
    }

    /* Card wrapper */
    .booking-card-premium {
        background: linear-gradient(160deg, #1a0a0e 0%, #110810 50%, #170b0f 100%);
        border: 1px solid rgba(212, 175, 55, 0.22);
        box-shadow:
            0 0 0 1px rgba(74, 14, 23, 0.4) inset,
            0 24px 60px rgba(0, 0, 0, 0.65);
        border-radius: 4px;
        overflow: hidden;
        color: #EDE8DC;
        width: 100%;
    }

    /* Garis emas atas kartu */
    .card-gold-rule {
        height: 1px;
        background: linear-gradient(to right, transparent, #D4AF37, transparent);
    }

    .card-body-inner {
        padding: 36px 40px 40px;
    }

    /* Card header */
    .card-eyebrow {
        font-family: 'EB Garamond', serif;
        font-size: 11px;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: rgba(212, 175, 55, 0.55);
        margin: 0 0 10px;
        display: block;
    }
    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 26px;
        font-weight: 600;
        color: #EDE8DC;
        margin: 0 0 6px;
        letter-spacing: -0.01em;
    }
    .card-subtitle {
        font-family: 'Crimson Text', serif;
        font-size: 15px;
        color: #8A7E6C;
        margin: 0 0 28px;
        line-height: 1.5;
    }

    /* Divider dalam card */
    .form-divider {
        height: 1px;
        background: linear-gradient(to right, rgba(212, 175, 55, 0.22), transparent);
        margin: 0 0 28px;
    }

    /* ── Form fields ────────────────────────────────────────── */
    .form-group-custom {
        margin-bottom: 22px;
    }

    .form-group-custom label {
        font-family: 'EB Garamond', serif;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.13em;
        color: rgba(212, 175, 55, 0.60);
        margin-bottom: 8px;
        display: block;
    }

    /* Input-group addon (ikon kiri) */
    .input-addon-dark {
        background-color: #1C141A !important;
        border: 1px solid rgba(212, 175, 55, 0.20) !important;
        border-right: none !important;
        color: rgba(212, 175, 55, 0.55) !important;
        border-radius: 4px 0 0 4px !important;
    }

    /* Input & select */
    .form-control-premium {
        background-color: #1C141A;
        border: 1px solid rgba(212, 175, 55, 0.20);
        border-left: none;
        color: #EDE8DC;
        height: 46px;
        border-radius: 0 4px 4px 0;
        font-family: 'Crimson Text', serif;
        font-size: 15px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control-premium:focus {
        border-color: rgba(212, 175, 55, 0.60);
        box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.10);
        background-color: #1C141A;
        color: #EDE8DC;
        outline: none;
    }

    .form-control-premium option {
        background-color: #1C141A;
        color: #EDE8DC;
    }

    /* Placeholder / empty select color */
    .form-control-premium.placeholder-active {
        color: #8A7E6C;
    }

    /* ── Buttons ───────────────────────────────────────────── */
    .btn-action-row {
        margin-top: 32px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .btn-neon-book {
        background: #1E6B3E;
        color: #EDE8DC;
        font-family: 'EB Garamond', serif;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        border: 1px solid rgba(34, 197, 94, 0.35);
        height: 46px;
        border-radius: 4px;
        padding: 0 32px;
        box-shadow: 0 4px 20px rgba(30, 107, 62, 0.35);
        transition: background 0.2s, box-shadow 0.2s, transform 0.15s;
        cursor: pointer;
    }

    .btn-neon-book:hover,
    .btn-neon-book:focus {
        background: #196035;
        color: #EDE8DC;
        box-shadow: 0 6px 28px rgba(30, 107, 62, 0.50);
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-kembali {
        background: transparent;
        border: 1px solid rgba(138, 126, 108, 0.28);
        color: #8A7E6C;
        font-family: 'EB Garamond', serif;
        font-weight: 600;
        font-size: 13px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        height: 46px;
        border-radius: 4px;
        padding: 0 24px;
        transition: border-color 0.2s, color 0.2s;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-kembali:hover,
    .btn-kembali:focus {
        border-color: rgba(212, 175, 55, 0.30);
        color: #EDE8DC;
        text-decoration: none;
    }

    /* Footer note */
    .form-footer-note {
        font-family: 'Crimson Text', serif;
        font-size: 12px;
        color: rgba(138, 126, 108, 0.45);
        text-align: center;
        margin: 20px 0 0;
        letter-spacing: 0.04em;
    }

    /* Garis emas bawah kartu */
    .card-gold-rule-bottom {
        height: 1px;
        background: linear-gradient(to right, transparent, #D4AF37, transparent);
        margin-top: 8px;
    }
</style>


<!-- ══════════════════════════════════════════════════════════════
     HTML LAYOUT — Ganti <div class="row afterhour-container"> lama
     dengan blok di bawah ini. Semua name attribute, PHP loop,
     dan action button tidak diubah sama sekali.
══════════════════════════════════════════════════════════════ -->

<div class="row afterhour-container">

    <!-- ── LEFT: Brand Hero ── col-md-5 ───────────────────── -->
    <div class="col-md-5 brand-section">
        <div class="brand-inner">

            <!-- Eyebrow -->
            <div class="brand-eyebrow">
                <span class="brand-eyebrow-line"></span>
                <span class="brand-eyebrow-text">Est. 2019 &middot; Jakarta</span>
            </div>

            <!-- Logotype -->
            <h1 class="neon-title">AFTERHOUR</h1>
            <div class="neon-subtitle">Billiard &amp; Lounge &mdash; Premium Experience</div>

            <!-- Divider -->
            <div class="brand-divider"></div>

        </div>
    </div>
    <!-- /col-md-5 -->

    <!-- ── RIGHT: Form Card ── col-md-7 ───────────────────── -->
    <div class="col-md-7 form-section">
        <div class="booking-card-premium">

            <!-- Garis emas atas -->
            <div class="card-gold-rule"></div>

            <div class="card-body-inner">

                <!-- Card header -->
                <span class="card-eyebrow">Reservasi Meja</span>
                <h3 class="card-title">Buat Pemesanan</h3>
                <p class="card-subtitle">Isi preferensi kunjungan bermain Anda di bawah ini.</p>

                <div class="form-divider"></div>

                <!-- ── FORM — semua name attribute TIDAK diubah ── -->
                <form action="" method="post">

                    <!-- outlet_id -->
                    <div class="form-group form-group-custom">
                        <label>Cabang Tempat (Outlet Location)</label>
                        <div class="input-group">
                            <span class="input-group-addon input-addon-dark">
                                <span class="glyphicon glyphicon-map-marker"></span>
                            </span>
                            <select class="form-control form-control-premium" name="outlet_id"
                                onChange="this.form.submit()" required>
                                <option value="">-- Pilih Lokasi Cabang Afterhour --</option>
                                <?php foreach ($arrOutlet as $outlet) { ?>
                                    <option value="<?php echo $outlet->outlet_id; ?>"
                                        <?php if ($selected_outlet == $outlet->outlet_id) echo 'selected'; ?>>
                                        <?php echo $outlet->outlet_name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <!-- table_id -->
                    <div class="form-group form-group-custom">
                        <label>Nomor Meja &amp; Kelas Area</label>
                        <div class="input-group">
                            <span class="input-group-addon input-addon-dark">
                                <span class="glyphicon glyphicon-tag"></span>
                            </span>
                            <select class="form-control form-control-premium" name="table_id" required>
                                <option value="">-- Pilih Meja / Tipe Ruangan --</option>
                                <?php
                                if (!empty($arrTable)) {
                                    foreach ($arrTable as $table) { ?>
                                        <option value="<?php echo $table->table_id; ?>">
                                            Meja <?php echo $table->table_number; ?> -
                                            <?php echo $table->class_type; ?>
                                            (Rp <?php echo number_format($table->price_per_hour, 0, ',', '.'); ?> / Jam)
                                        </option>
                                    <?php }
                                } else { ?>
                                    <option value="" disabled>Silakan pilih lokasi cabang terlebih dahulu</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <!-- booking_date + start_time -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-custom">
                                <label>Tanggal Bermain</label>
                                <div class="input-group">
                                    <span class="input-group-addon input-addon-dark">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    <input type="date" class="form-control form-control-premium"
                                        name="booking_date" required
                                        min="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-custom">
                                <label>Jam Mulai Main</label>
                                <div class="input-group">
                                    <span class="input-group-addon input-addon-dark">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    <input type="time" class="form-control form-control-premium"
                                        name="start_time" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- duration_hours -->
                    <div class="form-group form-group-custom">
                        <label>Durasi Pemakaian Meja</label>
                        <div class="input-group">
                            <span class="input-group-addon input-addon-dark">
                                <span class="glyphicon glyphicon-hourglass"></span>
                            </span>
                            <select class="form-control form-control-premium" name="duration_hours" required>
                                <option value="1">1 Jam Bermain</option>
                                <option value="2">2 Jam Bermain</option>
                                <option value="3">3 Jam Bermain</option>
                                <option value="4">4 Jam Bermain</option>
                            </select>
                        </div>
                    </div>

                    <!-- ── Action buttons ── -->
                    <div class="btn-action-row">
                        <button type="submit" name="btnSubmit" class="btn btn-neon-book">
                            <span class="glyphicon glyphicon-ok-sign"></span>&nbsp; Confirm &amp; Book
                        </button>
                        <a href="dashboardcustomer.php" class="btn-kembali">
                            Kembali
                        </a>
                    </div>

                </form>
                <!-- /form -->

                <p class="form-footer-note">
                    Kalkulasi harga diproses oleh server &middot; Afterhour &copy; 2024
                </p>

            </div>
            <!-- /card-body-inner -->

            <!-- Garis emas bawah -->
            <div class="card-gold-rule-bottom"></div>

        </div>
        <!-- /booking-card-premium -->
    </div>
    <!-- /col-md-7 -->

</div>
<!-- /row afterhour-container -->
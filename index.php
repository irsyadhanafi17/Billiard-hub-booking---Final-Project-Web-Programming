<?php
if (!isset($_SESSION)) { session_start(); }
$page = isset($_GET['p']) ? $_GET['p'] : 'home';

if ($page === 'login') {
    include('pages/login.php');
    exit();
}
if ($page === 'register') {
    include('pages/register.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Afterhour — Billiard & Lounge Premium</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&family=Boldonse&display=swap" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            background: #070103;
            font-family: 'DM Sans', sans-serif;
            color: #EDE8DC;
            overflow-x: hidden;
        }
        img { max-width: 100%; display: block; }

        .ah-navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 999;
            padding: 18px 48px;
            display: flex; align-items: center; justify-content: space-between;
            background: linear-gradient(to bottom, rgba(7,1,3,0.95) 0%, rgba(7,1,3,0.6) 60%, transparent 100%);
            backdrop-filter: blur(6px);
            transition: background 0.4s;
        }
        .ah-navbar.scrolled { background: rgba(7,1,3,0.97); border-bottom: 1px solid #2f0618; }
        .nav-logo {
            font-family: 'Boldonse', sans-serif;
            font-size: 22px;
            color: #8bd100;
            letter-spacing: 0.08em;
            text-decoration: none;
        }
        .nav-logo span { color: #fff; }
        .nav-links { display: flex; align-items: center; gap: 32px; }
        .nav-links a {
            color: #8A7E6C; font-size: 13px; font-weight: 500;
            text-decoration: none; letter-spacing: 0.04em;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: #fff; }
        .nav-cta-group { display: flex; align-items: center; gap: 12px; }
        .btn-nav-login {
            display: inline-flex; align-items: center; gap: 6px;
            color: #EDE8DC; font-size: 12px; font-weight: 600;
            letter-spacing: 0.05em; text-transform: uppercase;
            text-decoration: none; padding: 8px 16px;
            border: 1px solid #3f0828; border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-nav-login:hover { border-color: #8bd100; color: #8bd100; text-decoration: none; }
        .btn-nav-book {
            display: inline-flex; align-items: center; gap: 6px;
            background: #8bd100; color: #000; font-size: 12px; font-weight: 700;
            letter-spacing: 0.05em; text-transform: uppercase;
            text-decoration: none; padding: 9px 20px; border-radius: 30px;
            transition: all 0.3s;
        }
        .btn-nav-book:hover { background: #fff; transform: translateY(-1px); text-decoration: none; color: #000; }

        .hero {
            position: relative;
            height: 100vh; min-height: 600px;
            display: flex; align-items: flex-end;
            overflow: hidden;
        }
        .hero-slides { position: absolute; inset: 0; }
        .hero-slide {
            position: absolute; inset: 0;
            background-size: cover; background-position: center;
            opacity: 0; transition: opacity 1.4s ease;
        }
        .hero-slide.active { opacity: 1; }
        .hero-slide::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(7,1,3,0.95) 0%, rgba(7,1,3,0.5) 40%, rgba(7,1,3,0.15) 100%);
        }
        .hero-content {
            position: relative; z-index: 2;
            padding: 0 72px 80px;
            max-width: 800px;
        }
        .hero-eyebrow {
            font-size: 11px; font-weight: 600; letter-spacing: 4px;
            text-transform: uppercase; color: #8bd100; margin-bottom: 16px;
            display: flex; align-items: center; gap: 10px;
        }
        .hero-eyebrow::before {
            content: ''; display: block; width: 28px; height: 1px; background: #8bd100;
        }
        .hero-title {
            font-family: 'Boldonse', sans-serif;
            font-size: clamp(42px, 6vw, 74px);
            line-height: 1.0;
            color: #fff;
            margin-bottom: 20px;
            letter-spacing: -0.01em;
        }
        .hero-title .accent { color: #8bd100; }
        .hero-sub {
            font-size: 16px; color: #8A7E6C; line-height: 1.7;
            max-width: 520px; margin-bottom: 36px;
        }
        .hero-cta { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
        .btn-hero-book {
            display: inline-flex; align-items: center; gap: 10px;
            background: #8bd100; color: #000; font-weight: 700; font-size: 14px;
            letter-spacing: 0.06em; text-transform: uppercase; text-decoration: none;
            padding: 16px 36px; border-radius: 40px;
            transition: all 0.3s;
            box-shadow: 0 8px 32px rgba(139,209,0,0.35);
        }
        .btn-hero-book:hover { background: #fff; transform: translateY(-3px); text-decoration: none; color: #000; }
        .btn-hero-member {
            display: inline-flex; align-items: center; gap: 8px;
            color: #EDE8DC; font-size: 14px; font-weight: 600;
            text-decoration: none; letter-spacing: 0.04em;
            padding: 16px 28px; border: 1px solid #3f0828; border-radius: 40px;
            transition: all 0.3s;
        }
        .btn-hero-member:hover { border-color: #8A7E6C; color: #fff; text-decoration: none; }
        
        .hero-dots {
            position: absolute; bottom: 32px; right: 72px; z-index: 3;
            display: flex; gap: 8px;
        }
        .hdot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #3f0828; cursor: pointer; transition: all 0.3s;
        }
        .hdot.active { background: #8bd100; width: 20px; border-radius: 3px; }

        .section { padding: 88px 0; }
        .section-label {
            font-size: 11px; font-weight: 600; letter-spacing: 4px;
            text-transform: uppercase; color: #8bd100;
            display: flex; align-items: center; gap: 10px; margin-bottom: 14px;
        }
        .section-label::before { content:''; display:block; width:24px; height:1px; background:#8bd100; }
        .section-title {
            font-family: 'Boldonse', sans-serif;
            font-size: clamp(28px, 4vw, 46px);
            color: #fff; line-height: 1.1; margin-bottom: 16px;
        }
        .section-desc { font-size: 15px; color: #8A7E6C; line-height: 1.7; max-width: 540px; }

        .photo-grid-section { background: #070103; padding: 80px 0; }
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: 240px 240px;
            gap: 8px;
        }
        .pg-item {
            overflow: hidden; border-radius: 8px; position: relative; cursor: pointer;
            transition: transform 0.4s;
        }
        .pg-item:hover { transform: scale(1.02); z-index: 2; }
        .pg-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s; }
        .pg-item:hover img { transform: scale(1.08); }
        .pg-item.span2 { grid-column: span 2; }
        .pg-item.span-row2 { grid-row: span 2; }
        .pg-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(7,1,3,0.7) 0%, transparent 50%);
            opacity: 0; transition: opacity 0.3s;
            display: flex; align-items: flex-end; padding: 16px;
        }
        .pg-item:hover .pg-overlay { opacity: 1; }
        .pg-overlay span { color: #8bd100; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }

        .class-cards { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; margin-top: 40px; }
        .class-card {
            background: #1f0410; border: 1px solid #2f0618; border-radius: 16px;
            overflow: hidden; transition: all 0.3s;
        }
        .class-card:hover { border-color: #8bd100; transform: translateY(-4px); }
        .class-card-img { height: 200px; overflow: hidden; }
        .class-card-img img { width:100%; height:100%; object-fit:cover; transition:transform 0.5s; }
        .class-card:hover .class-card-img img { transform: scale(1.08); }
        .class-card-body { padding: 22px; }
        .class-card-tag {
            font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
            margin-bottom: 10px; display: inline-block; padding: 4px 10px; border-radius: 4px;
        }
        .tag-regular { background: #1a3a2a; color: #5acc8a; }
        .tag-vip { background: #1a1a3a; color: #8a8aff; }
        .tag-vvip { background: #3a1a00; color: #ffaa00; }
        .class-card-name { font-family:'Boldonse',sans-serif; font-size:22px; color:#fff; margin-bottom:8px; }
        .class-card-desc { font-size:13px; color:#8A7E6C; line-height:1.6; margin-bottom:16px; }
        .class-card-price { font-family:'Boldonse',sans-serif; font-size:24px; color:#8bd100; }
        .class-card-price span { font-family:'DM Sans',sans-serif; font-size:13px; color:#555; font-weight:400; }

        .outlet-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-top:40px; }
        .outlet-card {
            border-radius:14px; overflow:hidden; position:relative;
            height: 260px; cursor:pointer; transition:transform 0.3s;
        }
        .outlet-card:hover { transform:translateY(-4px); }
        .outlet-card img { width:100%; height:100%; object-fit:cover; transition:transform 0.5s; }
        .outlet-card:hover img { transform:scale(1.06); }
        .outlet-card-overlay {
            position:absolute; inset:0;
            background:linear-gradient(to top, rgba(7,1,3,0.92) 0%, rgba(7,1,3,0.3) 60%, transparent 100%);
            display:flex; flex-direction:column; justify-content:flex-end; padding:22px;
        }
        .outlet-card-name { font-family:'Boldonse',sans-serif; font-size:18px; color:#fff; margin-bottom:4px; }
        .outlet-card-loc { font-size:12px; color:#8A7E6C; display:flex; align-items:center; gap:6px; }
        .outlet-card-loc .glyphicon { color:#e81b7b; font-size:11px; }

        .promo-section { background:#0d0208; border-top:1px solid #2f0618; border-bottom:1px solid #2f0618; padding:72px 0; }
        .promo-inner { display:grid; grid-template-columns:1fr 1fr; gap:40px; align-items:center; }
        .promo-cards { display:flex; flex-direction:column; gap:14px; }
        .promo-mini {
            background:#1f0410; border:1px solid #2f0618; border-radius:12px;
            padding:18px; display:flex; align-items:center; gap:16px;
            transition:border-color 0.3s;
        }
        .promo-mini:hover { border-color:#8bd100; }
        .promo-mini .pm-pct {
            min-width:70px; height:70px; background:#0d1a00; border:2px solid #8bd100;
            border-radius:10px; display:flex; flex-direction:column;
            align-items:center; justify-content:center; flex-shrink:0;
        }
        .pm-pct .pn { font-family:'Boldonse',sans-serif; font-size:22px; color:#8bd100; line-height:1; }
        .pm-pct .pl { font-size:8px; color:#8bd100; letter-spacing:1px; text-transform:uppercase; }
        .promo-mini h5 { color:#fff; font-weight:700; font-size:15px; margin:0 0 4px; }
        .promo-mini p { color:#8A7E6C; font-size:12px; margin:0; line-height:1.5; }

        .stats-bar {
            display:grid; grid-template-columns:repeat(4,1fr);
            gap:0; border:1px solid #2f0618; border-radius:14px; overflow:hidden;
            margin-top:60px;
        }
        .stat-item {
            padding:28px 24px; text-align:center;
            border-right:1px solid #2f0618;
            background:#1f0410;
        }
        .stat-item:last-child { border-right:none; }
        .stat-num { font-family:'Boldonse',sans-serif; font-size:36px; color:#8bd100; line-height:1; margin-bottom:6px; }
        .stat-lbl { font-size:12px; color:#8A7E6C; letter-spacing:1px; text-transform:uppercase; }

        .cta-section {
            position:relative; overflow:hidden;
            padding:100px 0; text-align:center;
        }
        .cta-bg {
            position:absolute; inset:0;
            background-image:url('assets/stocks/Afterhour (2).png');
            background-size:cover; background-position:center;
            filter:brightness(0.2);
        }
        .cta-content { position:relative; z-index:2; }
        .cta-title { font-family:'Boldonse',sans-serif; font-size:clamp(28px,5vw,56px); color:#fff; margin-bottom:14px; }
        .cta-sub { font-size:16px; color:#8A7E6C; margin-bottom:36px; }

        .ah-footer {
            background:#070103; border-top:1px solid #2f0618;
            padding:48px 0 28px;
        }
        .footer-grid { display:grid; grid-template-columns:2fr 1fr 1fr; gap:40px; margin-bottom:40px; }
        .footer-brand { font-family:'Boldonse',sans-serif; font-size:24px; color:#8bd100; margin-bottom:12px; }
        .footer-desc { font-size:13px; color:#8A7E6C; line-height:1.7; max-width:280px; }
        .footer-col h5 { color:#fff; font-weight:700; font-size:13px; letter-spacing:1px; text-transform:uppercase; margin-bottom:16px; }
        .footer-col a { display:block; color:#8A7E6C; font-size:13px; text-decoration:none; margin-bottom:8px; transition:color 0.2s; }
        .footer-col a:hover { color:#fff; }
        .footer-bottom { border-top:1px solid #1a0a12; padding-top:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; }
        .footer-copy { font-size:12px; color:#3a3a3a; }

        .container-xl { max-width:1200px; margin:0 auto; padding:0 48px; }
        @media(max-width:991px) {
            .ah-navbar { padding:16px 24px; }
            .hero-content { padding:0 24px 60px; }
            .hero-dots { right:24px; }
            .container-xl { padding:0 24px; }
            .photo-grid { grid-template-columns:repeat(2,1fr); grid-template-rows:200px 200px 200px; }
            .pg-item.span-row2 { grid-row:span 1; }
            .class-cards, .outlet-grid { grid-template-columns:1fr; }
            .promo-inner { grid-template-columns:1fr; }
            .stats-bar { grid-template-columns:repeat(2,1fr); }
            .footer-grid { grid-template-columns:1fr; }
            .nav-links { display:none; }
        }
    </style>
</head>
<body>


<nav class="ah-navbar" id="mainNav">
    <a href="index.php" class="nav-logo">AFTER<span>HOUR</span></a>
    <div class="nav-links">
        <a href="#gallery">Galeri</a>
        <a href="#kelas">Kelas Meja</a>
        <a href="#outlet">Outlet</a>
        <a href="#promo">Promo</a>
    </div>
    <div class="nav-cta-group">
        <?php if (isset($_SESSION['role'])): ?>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="dashboardadmin.php" class="btn-nav-login">Admin Panel</a>
            <?php elseif ($_SESSION['role'] === 'manager'): ?>
                <a href="dashboardmanager.php" class="btn-nav-login">Manager Panel</a>
            <?php else: ?>
                <a href="dashboardcustomer.php" class="btn-nav-login">
                    <span class="glyphicon glyphicon-user"></span> <?= htmlspecialchars($_SESSION['name']) ?>
                </a>
            <?php endif; ?>
            <a href="logout.php" class="btn-nav-book">Logout</a>
        <?php else: ?>
            <a href="index.php?p=login" class="btn-nav-login">
                <span class="glyphicon glyphicon-user"></span> MASUK
            </a>
            <a href="index.php?p=login" class="btn-nav-book">
                BOOKING MEJA
            </a>
        <?php endif; ?>
    </div>
</nav>

<section class="hero">
    <div class="hero-slides" id="heroSlides">
        <div class="hero-slide active" style="background-image:url('assets/stocks/Afterhour (2).png')"></div>
        <div class="hero-slide" style="background-image:url('assets/stocks/Afterhour (4).png')"></div>
        <div class="hero-slide" style="background-image:url('assets/stocks/Afterhour (12).png')"></div>
        <div class="hero-slide" style="background-image:url('assets/stocks/Afterhour (8).png')"></div>
    </div>

    <div class="hero-content">
        <div class="hero-eyebrow">Premium Billiard & Lounge</div>
        <h1 class="hero-title">
            Main Seru,<br>Nongkrong <span class="accent">Asik.</span>
        </h1>
        <p class="hero-sub">
            Afterhour hadir di 6 lokasi strategis Jakarta dengan meja billiard premium kelas Regular, VIP Smoking, hingga VVIP — semua tersedia untuk Anda.
        </p>
        <div class="hero-cta">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
                <a href="dashboardcustomer.php?p=booking" class="btn-hero-book">
                    🎱 &nbsp; BOOKING MEJA SEKARANG
                </a>
                <a href="dashboardcustomer.php?p=mybookings" class="btn-hero-member">Lihat Booking Saya</a>
            <?php else: ?>
                <a href="index.php?p=login" class="btn-hero-book">
                    🎱 &nbsp; BOOKING MEJA SEKARANG
                </a>
                <a href="index.php?p=register" class="btn-hero-member">
                    <span class="glyphicon glyphicon-ok-sign"></span> Daftar Member Gratis
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="hero-dots" id="heroDots">
        <div class="hdot active" onclick="goSlide(0)"></div>
        <div class="hdot" onclick="goSlide(1)"></div>
        <div class="hdot" onclick="goSlide(2)"></div>
        <div class="hdot" onclick="goSlide(3)"></div>
    </div>
</section>

<div class="container-xl">
    <div class="stats-bar">
        <div class="stat-item"><div class="stat-num">6</div><div class="stat-lbl">Outlet di Jakarta</div></div>
        <div class="stat-item"><div class="stat-num">50+</div><div class="stat-lbl">Meja Premium</div></div>
        <div class="stat-item"><div class="stat-num">3</div><div class="stat-lbl">Kelas Meja</div></div>
        <div class="stat-item"><div class="stat-num">24<span style="font-size:20px">Jam</span></div><div class="stat-lbl">Buka Setiap Hari</div></div>
    </div>
</div>

<section class="photo-grid-section" id="gallery">
    <div class="container-xl">
        <div class="section-label">Galeri</div>
        <div class="section-title">Suasana <br>Afterhour</div>
        <p class="section-desc" style="margin-bottom:32px">Rasakan atmosfer premium yang memadukan keseruan billiard dengan kenyamanan lounge eksklusif.</p>

        <div class="photo-grid">
            <div class="pg-item span-row2">
                <img src="assets/stocks/Afterhour (2).png" alt="Afterhour Billiard">
                <div class="pg-overlay"><span>Meja Premium</span></div>
            </div>
            <div class="pg-item">
                <img src="assets/stocks/Afterhour (1).png" alt="Afterhour Lounge">
                <div class="pg-overlay"><span>Lounge Area</span></div>
            </div>
            <div class="pg-item">
                <img src="assets/stocks/Afterhour (4).png" alt="Afterhour VIP">
                <div class="pg-overlay"><span>VIP Zone</span></div>
            </div>
            <div class="pg-item">
                <img src="assets/stocks/Afterhour (5).png" alt="Billiard Balls">
                <div class="pg-overlay"><span>Siap Tempur</span></div>
            </div>
            
            <div class="pg-item">
                <img src="assets/stocks/Afterhour (3).png" alt="Playing">
                <div class="pg-overlay"><span>Aksi Seru</span></div>
            </div>
            <div class="pg-item">
                <img src="assets/stocks/Afterhour (9).png" alt="Coaching">
                <div class="pg-overlay"><span>Belajar Bareng</span></div>
            </div>
            <div class="pg-item">
                <img src="assets/stocks/Afterhour (10).png" alt="Atmosphere">
                <div class="pg-overlay"><span>Vibes Premium</span></div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px;margin-top:8px">
            <?php for ($i=6; $i<=10; $i++): ?>
            <div class="pg-item" style="height:140px;border-radius:8px">
                <img src="assets/stocks/Afterhour (<?= $i ?>).png" alt="Afterhour <?= $i ?>">
                <div class="pg-overlay"><span>Afterhour</span></div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<section class="section" id="kelas" style="background:#0d0208;border-top:1px solid #2f0618">
    <div class="container-xl">
        <div class="section-label">Pilihan Meja</div>
        <div class="section-title">Kelas Sesuai<br>Selera Anda</div>
        <p class="section-desc">Dari casual hingga ultra-premium, Afterhour menyediakan tiga kelas meja untuk memenuhi kebutuhan Anda.</p>

        <div class="class-cards">
            
            <div class="class-card">
                <div class="class-card-img">
                    <img src="assets/stocks/Afterhour (7).png" alt="Regular Floor">
                </div>
                <div class="class-card-body">
                    <span class="class-card-tag tag-regular">Regular Floor</span>
                    <div class="class-card-name">Regular</div>
                    <p class="class-card-desc">Meja billiard standar dengan kualitas terjamin. Cocok untuk sesi santai bersama teman.</p>
                    <div class="class-card-price">Rp 35.000 <span>/ jam</span></div>
                </div>
            </div>
            
            <div class="class-card">
                <div class="class-card-img">
                    <img src="assets/stocks/Afterhour (8).png" alt="VIP Smoking">
                </div>
                <div class="class-card-body">
                    <span class="class-card-tag tag-vip">VIP Smoking</span>
                    <div class="class-card-name">VIP Smoking</div>
                    <p class="class-card-desc">Area VIP eksklusif dengan fasilitas smoking lounge dan meja premium berstandar tinggi.</p>
                    <div class="class-card-price">Rp 60.000 <span>/ jam</span></div>
                </div>
            </div>
    
            <div class="class-card" style="border-color:#3a1a00">
                <div class="class-card-img">
                    <img src="assets/stocks/Afterhour (11).png" alt="VVIP">
                </div>
                <div class="class-card-body">
                    <span class="class-card-tag tag-vvip">VVIP</span>
                    <div class="class-card-name">VVIP</div>
                    <p class="class-card-desc">Pengalaman bermain paling premium dengan privasi penuh, meja terbaik, dan layanan prioritas.</p>
                    <div class="class-card-price" style="color:#ffaa00">Rp 100.000 <span>/ jam</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="outlet">
    <div class="container-xl">
        <div class="section-label">Lokasi Kami</div>
        <div class="section-title">6 Outlet<br>di Jakarta</div>
        <p class="section-desc" style="margin-bottom:0">Temukan Afterhour terdekat dari lokasi Anda dan nikmati pengalaman bermain terbaik.</p>

        <div class="outlet-grid" style="margin-top:36px">
            <?php
            $outlets = [
                ['Afterhour Cikini',  'Cikini, Jakarta Pusat',       'Afterhour (3).png'],
                ['Afterhour Menteng', 'Menteng, Jakarta Pusat',       'Afterhour (6).png'],
                ['Afterhour Sunter',  'Sunter Agung, Jakarta Utara',  'Afterhour (7).png'],
                ['Afterhour PIK',     'PIK, Jakarta Utara',           'Afterhour (8).png'],
                ['Afterhour Poins',   'Lebak Bulus, Jakarta Selatan', 'Afterhour (9).png'],
                ['M Billiards Blok M','Melawai, Jakarta Barat',       'Afterhour (10).png'],
            ];
            foreach ($outlets as $ol):
            ?>
            <div class="outlet-card">
                <img src="assets/stocks/<?= $ol[2] ?>" alt="<?= $ol[0] ?>">
                <div class="outlet-card-overlay">
                    <div class="outlet-card-name"><?= $ol[0] ?></div>
                    <div class="outlet-card-loc">
                        <span class="glyphicon glyphicon-map-marker"></span> <?= $ol[1] ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
require_once('class/class.Discount.php');
$objDiscount    = new Discount();
$activeDiscounts= $objDiscount->SelectActiveDiscounts();
if (!empty($activeDiscounts)):
?>
<section class="promo-section" id="promo">
    <div class="container-xl">
        <div class="promo-inner">
            <div>
                <div class="section-label">Promo Aktif</div>
                <div class="section-title">Hemat Lebih<br>Main Lebih Lama</div>
                <p class="section-desc" style="margin-bottom:24px">Manfaatkan promo eksklusif Afterhour dan dapatkan diskon spesial untuk sesi bermain Anda.</p>
                <?php if (!isset($_SESSION['role'])): ?>
                <a href="index.php?p=register" class="btn-hero-book" style="font-size:13px;padding:12px 28px">
                    Daftar &amp; Nikmati Promo
                </a>
                <?php endif; ?>
            </div>
            <div class="promo-cards">
                <?php foreach ($activeDiscounts as $d): ?>
                <div class="promo-mini">
                    <div class="pm-pct">
                        <div class="pn"><?= (int)$d->discount_pct ?>%</div>
                        <div class="pl">OFF</div>
                    </div>
                    <div>
                        <h5><?= htmlspecialchars($d->title) ?></h5>
                        <p><?= htmlspecialchars($d->description) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="cta-section">
    <div class="cta-bg"></div>
    <div class="cta-content container-xl">
        <h2 class="cta-title">Siap Bermain Malam Ini?</h2>
        <p class="cta-sub">Reservasi meja favorit Anda sekarang dan rasakan pengalaman billiard premium bersama Afterhour.</p>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
            <a href="dashboardcustomer.php?p=booking" class="btn-hero-book">🎱 &nbsp; BOOKING SEKARANG</a>
        <?php else: ?>
            <a href="index.php?p=login" class="btn-hero-book">🎱 &nbsp; BOOKING SEKARANG</a>
            &nbsp;&nbsp;
            <a href="index.php?p=register" class="btn-hero-member" style="display:inline-flex">Daftar Gratis</a>
        <?php endif; ?>
    </div>
</section>

<footer class="ah-footer">
    <div class="container-xl">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">AFTERHOUR</div>
                <p class="footer-desc">Billiard & Lounge premium terbaik di Jakarta. Hadir di 6 lokasi strategis untuk memenuhi hasrat bermain Anda.</p>
            </div>
            <div class="footer-col">
                <h5>Navigasi</h5>
                <a href="#gallery">Galeri</a>
                <a href="#kelas">Kelas Meja</a>
                <a href="#outlet">Outlet</a>
                <a href="#promo">Promo</a>
            </div>
            <div class="footer-col">
                <h5>Akun</h5>
                <a href="index.php?p=login">Masuk</a>
                <a href="index.php?p=register">Daftar Member</a>
                <?php if (isset($_SESSION['role'])): ?>
                    <?php if ($_SESSION['role']==='admin'): ?>
                        <a href="dashboardadmin.php">Admin Panel</a>
                    <?php elseif ($_SESSION['role']==='manager'): ?>
                        <a href="dashboardmanager.php">Manager Panel</a>
                    <?php else: ?>
                        <a href="dashboardcustomer.php?p=booking">Booking Meja</a>
                        <a href="dashboardcustomer.php?p=mybookings">Riwayat Booking</a>
                    <?php endif; ?>
                    <a href="logout.php">Logout</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-bottom">
            <span class="footer-copy">© <?= date('Y') ?> Afterhour Billiard & Lounge. All rights reserved.</span>
            <span class="footer-copy">Made with ❤️ for billiard lovers</span>
        </div>
    </div>
</footer>

<script>
window.addEventListener('scroll', function() {
    var nav = document.getElementById('mainNav');
    if (window.scrollY > 60) { nav.classList.add('scrolled'); }
    else { nav.classList.remove('scrolled'); }
});

var slides     = document.querySelectorAll('.hero-slide');
var dots       = document.querySelectorAll('.hdot');
var current    = 0;
var slideTimer;

function goSlide(n) {
    slides[current].classList.remove('active');
    dots[current].classList.remove('active');
    current = (n + slides.length) % slides.length;
    slides[current].classList.add('active');
    dots[current].classList.add('active');
    clearInterval(slideTimer);
    slideTimer = setInterval(function(){ goSlide(current + 1); }, 5000);
}

slideTimer = setInterval(function(){ goSlide(current + 1); }, 5000);
</script>
</body>
</html>

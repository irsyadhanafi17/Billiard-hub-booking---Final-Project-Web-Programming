<?php
if (!isset($_SESSION)) {
    session_start(); 
}

require_once("inc.koneksi.php");

if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] == 'customer') {
        echo '<script>window.location = "dashboardcustomer.php";</script>';
    } else if ($_SESSION["role"] == 'admin') {
        echo '<script>window.location = "dashboardadmin.php";</script>';
    }
    exit();
}

$page = isset($_GET['p']) ? $_GET['p'] : 'home';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Afterhour - Premium Billiard & Lounge</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <link class="sheet" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2 family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2 family=Boldonse&display=swap" rel="stylesheet">

    <style>
        @font-face {
            font-family: "Boldonse", system-ui;
            font-weight: 400;
            font-style: normal;
            src: url("assets/fonts/Boldonse-Regular.woff2") format("woff2"),
                 url("assets/fonts/Boldonse-Regular.woff") format("woff"),
                 url("assets/fonts/Boldonse-Regular.ttf") format("truetype");
        }

        body {
            background-color: #070103 !important;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: 'DM Sans', 'Inter', sans-serif !important;
            font-weight: 400;
            font-size: 15px; 
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .font-horizon {
            font-family: 'Boldonse', 'Impact', 'Arial Narrow', sans-serif !important;
            font-weight: 900 !important; 
            text-transform: uppercase !important;
            letter-spacing: 0.04em !important; 
            line-height: 1.15 !important;    
            color: #ffffff;
        }

        .section-title-editorial {
            font-family: 'DM Sans', sans-serif !important;
            font-weight: 700 !important; 
            letter-spacing: -0.01em !important;
            text-transform: none !important;
            font-size: 48px !important;
        }

        .section-desc-editorial, .hero-subtitle-text, .advantage-desc, .membership-block-bg p {
            font-family: 'DM Sans', sans-serif !important;
            font-weight: 400 !important;
            color: #EDE8DC !important;
            letter-spacing: 0px !important;
            line-height: 1.7 !important;
        }

        .navbar-landing-public {
            position: fixed !important;
            width: 100% !important;
            top: 0;
            left: 0;
            border: none !important;
            margin: 0 !important;
            min-height: 90px !important;
            z-index: 99999 !important;
            background-color: transparent !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.25) !important; 
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }

        .navbar-landing-public.nav-scrolled {
            min-height: 70px !important;
            background-color: #000000 !important;
            border-bottom: 2px solid rgba(0, 0, 0, 0.9) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.9);
        }

        .navbar-landing-public .container,
        .navbar-landing-public .container-fluid {
            position: relative !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 90px !important;
            margin: 0 !important;
            padding: 0 !important;
            transition: all 0.4s ease !important;
        }
        .navbar-landing-public.nav-scrolled .container,
        .navbar-landing-public.nav-scrolled .container-fluid {
            height: 70px !important;
        }

        .navbar-landing-public .navbar-header {
            position: absolute !important;
            top: 50% !important;
            left: 40px !important; 
            transform: translateY(-50%) !important;
            float: none !important;
            margin: 0 !important;
        }

        .navbar-landing-public .navbar-brand {
            padding: 0 !important;
            margin: 0 !important;
            display: block !important;
            height: auto !important;
        }

        .navbar-landing-public .navbar-brand .logo-img {
            height: 45px;
            width: auto;
            object-fit: contain;
            transition: all 0.3s ease;
        }

        .navbar-landing-public .navbar-nav.navbar-right {
            position: absolute !important;
            top: 50% !important;
            right: 40px !important; 
            transform: translateY(-50%) !important;
            float: none !important;
            margin: 0 !important;
            display: block !important;
        }

        .navbar-landing-public .navbar-nav > li {
            float: none !important;
            display: inline-block !important;
        }

        .navbar-landing-public .navbar-nav > li > a {
            color: #ffffff !important;
            font-family: 'DM Sans', sans-serif !important; 
            font-weight: 500 !important;
            font-size: 14px !important;   
            letter-spacing: 0.03em !important;
            text-transform: uppercase;
            padding: 8px 20px !important;
            border: 2px solid transparent !important;
            border-radius: 30px !important; 
            background-color: transparent !important;
            transition: all 0.3s ease;
        }

        .navbar-landing-public.nav-scrolled .navbar-nav > li > a {
            color: #d1fd79 !important; 
        }

        .navbar-landing-public .navbar-nav > li > a:hover {
            color: #ffffff !important;
            background-color: #e81b7b !important;
            border-color: #e81b7b !important;
            box-shadow: 0 0 0px #000000;
        }

        @media (max-width: 991px) {
            .navbar-landing-public .navbar-header { left: 20px !important; }
            .navbar-landing-public .navbar-nav.navbar-right { right: 20px !important; }
        }

        .main-landing-wrapper {
            background-color: #050505;
            padding-top: 0px;
        }

        .hero-premium-section {
            position: relative;
            background: linear-gradient(rgba(0,0,0,0.2), #000000), url('assets/hero/Afterhour Hero Image.png') no-repeat center center;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            padding: 0 80px;
        }
        .hero-premium-section .hero-title-graphic {
            display: block;
            margin-left: 0 !important;   
            margin-right: auto;
            margin-bottom: 35px;         
            max-width: 650px; 
            width: 100%;
            height: auto;
            object-fit: contain;
        }
        .hero-premium-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg, rgba(0, 0, 0, 0.5) 0%, rgba(47, 6, 24, 0.4) 50%, #000000 100%);
            z-index: 1;
        }
        .hero-content-inner {
            position: relative;
            z-index: 2;
            max-width: 750px;
            margin-top: 40px;
        }
        .hero-content-inner-leftaligned {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: flex-start;    
            justify-content: center;
            text-align: left;           
            width: 100%;
            max-width: 750px;
            margin-left: 15px;             
        }
        .hero-action-buttons-leftaligned {
            display: flex;
            gap: 15px;                  
            justify-content: flex-start; 
            width: 100%;
            flex-wrap: wrap;
            margin-left: 50px;             
        }
        .hero-premium-section .btn-gold-action, 
        .hero-premium-section .btn-outline-action {
            flex: 0 0 auto;
            min-width: 200px;
            justify-content: center;
            height: 52px;
        }

        .hero-title-graphic {
            display: block;
            margin-bottom: 30px;
            max-width: 650px; 
            width: 100%;
            height: auto;
            object-fit: contain;
        }
        @media (max-width: 768px) {
        .hero-premium-section { padding: 40px 20px !important; }
        .hero-content-inner-leftaligned { align-items: flex-start; }
        .hero-action-buttons-leftaligned { gap: 12px; }
        .hero-premium-section .btn-gold-action, 
        .hero-premium-section .btn-outline-action { min-width: 100%; } 
        }

        .btn-gold-action {
            background: #8bd100 !important; 
            color: #000000 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            height: 52px;
            padding: 0 35px;
            display: inline-flex;
            align-items: center;
            border-radius: 30px !important; 
            border: none;
            box-shadow: 0 4px 25px rgba(209, 253, 121, 0.25);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-gold-action:hover {
            background: #ffffff !important;
            box-shadow: 0 0 15px #d1fd796f;
            text-decoration: none;
            transform: translateY(-2px);
        }
        .btn-outline-action {
            background: transparent;
            color: #ffffff !important;
            font-family: 'DM Sans', sans-serif !important;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border: 2px solid #2f0618 !important;
            height: 52px;
            padding: 0 30px;
            display: inline-flex;
            align-items: center;
            border-radius: 30px !important; 
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-outline-action:hover {
            border-color: #a3ee5989 !important;
            color: #a3ee59 !important;
            text-decoration: none;
        }

        .section-padding {
            padding: 120px 80px;
        }

        .stats-counter-wrapper {
            padding: 10px 40px 0px;
            background-color: #0c0105;
        }

        .stats-premium-box {
            background-color: #2f0618 !important;
            border: 1px solid #2f0618 !important;
            border-radius: 32px !important;
            padding: 40px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .stats-item-block {
            flex: 1;
            text-align: left;
            padding-left: 40px;
        }

        .stats-item-block:not(:first-child) {
            border-left: 2px solid rgba(232, 27, 123, 0.3) !important;
            padding-left: 35px;
        }

        .stats-huge-number {
            font-family: 'Boldonse', sans-serif !important;
            font-weight: 900 !important;
            font-size: 56px !important;
            color: #ffffff;
            line-height: 1.0 !important;
            margin-bottom: 10px;
            letter-spacing: 0.01em !important;
        }

        .stats-label-text {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 700 !important;
            font-size: 13px;
            color: #e81b7b !important;
            text-transform: uppercase;
            letter-spacing: 2px !important;
        }

        @media (max-width: 991px) {
            .stats-counter-wrapper { padding: 40px 20px 0px; }
            .stats-premium-box { flex-direction: column; padding: 40px 30px; gap: 40px; }
            .stats-item-block { text-align: center; padding-left: 0 !important; width: 100%; }
            .stats-item-block:not(:first-child) { border-left: none !important; border-top: 1px solid rgba(232, 27, 123, 0.2) !important; padding-top: 30px; }
            .stats-huge-number { font-size: 46px !important; }
        }
        .locations-sidebar-bg {
            background-image: url('assets/hero/Afterhour Main Outline.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: bottom right;
            position: sticky;
        }

        .outlets-container-bg {
            background-image: url('assets/Afterhour Main Outline.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center right;
            position: sticky;
        }
        
        .outlet-card-premium {
            background: #1f0410 !important;
            backdrop-filter: blur(15px);
            border: 0.1px solid #2f0618 !important;
            border-radius: 24px !important; 
            overflow: hidden;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s, box-shadow 0.4s ease;
        }
        
        .outlet-card-premium:hover {
            transform: translateY(-10px);
            border-color: #2f0618 !important;
            box-shadow: 0 1px 35px rgba(153, 10, 74, 0.47) !important;
        }
        
        .outlet-image-holder {
            background-color: #111;
            background-size: cover;
            background-position: center;
            border-bottom: 1px solid #2f0618;
        }
        
        .outlet-body-inner {
            padding: 25px;
            background: transparent;
        }
        
        .outlet-meta {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 300 !important; 
            font-size: 11px;
            color: #84003b !important;
            letter-spacing: 3px !important; 
            margin-bottom: 8px;
            display: block;
        }
        
        .outlet-name-text {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 700 !important; 
            font-size: 24px !important;
            color: #ffffff !important;
            margin: 0 0 10px;
            text-transform: none !important;
            character-spacing: -2px !important;
        }
        
        .outlet-price-info {
            font-family: 'DM Sans', sans-serif !important;
            font-weight: 400 !important; 
            font-size: 14px;
            color: #8A7E6C;
        }

        @media (min-width: 1200px) {
            .row-flex-responsive { display: flex !important; align-items: center !important; flex-wrap: wrap !important; }
            
            .horizontal-scroll-container {
                display: flex !important; 
                flex-wrap: nowrap !important;
                overflow-x: auto !important; 
                overflow-y: hidden !important;
                gap: 28px; 
                padding-top: 15px !important; 
                padding-bottom: 30px !important; 
                scroll-behavior: smooth; 
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none !important; 
                -ms-overflow-style: none !important;
            }
            .horizontal-scroll-container::-webkit-scrollbar { display: none !important; }
            .outlet-card-premium { flex: 0 0 290px !important; }
            .outlet-image-holder { height: 280px; }
        }

        @media (max-width: 991px) {
            .navbar-landing-public { padding: 0 20px !important; }
            .navbar-landing-public.nav-scrolled { padding: 0 20px !important; }
            .navbar-landing-public .navbar-brand .logo-img { height: 35px; }
            .navbar-landing-public .navbar-nav > li > a { font-size: 11px !important; padding: 6px 12px !important; }
            .hero-premium-section { padding: 40px 20px !important; }
            .hero-title-graphic { max-width: 320px; margin-bottom: 25px; }
            .outlet-image-holder { height: 240px !important; }
            .section-padding { padding: 60px 20px !important; }
            .membership-block-bg { padding: 60px 20px !important; }
            .horizontal-scroll-container { padding-top: 10px !important; padding-bottom: 15px !important; }
            .courtside-footer-public { padding: 60px 20px 30px !important; }
            .footer-col-right { margin-top: 40px; }
        }

        .membership-block-bg {
            background: linear-gradient(180deg, #000000 0%, #160a0e 50%, #000000 100%);
            padding: 120px 80px;
        }
        
        .membership-block-bg .section-title-editorial {
            font-family: 'DM Sans', sans-serif !important;
            font-weight: 700 !important;
            color: #ffffff;
        }

        .premium-price-box {
            background: rgba(0, 0, 0, 0.5) !important;
            backdrop-filter: blur(20px);
            border: 2px solid rgba(240, 22, 121, 0.35) !important;
            box-shadow: 0 0 40px rgba(240, 22, 121, 0.25);
            border-radius: 24px !important; 
            padding: 50px;
            text-align: center;
            max-width: 550px; 
            margin: 0 auto;  
        }

        .premium-price-box .card-title {
            font-family: 'DM Sans', sans-serif !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            letter-spacing: 0px !important;
        }

        .benefit-list { text-align: left; margin: 25px 0; padding-left: 0; list-style: none; }
        
        .benefit-list li {
            font-family: 'DM Sans', sans-serif !important;
            font-weight: 400 !important;
            font-size: 15px;
            padding: 14px 0;
            border-bottom: 1px solid rgba(240, 22, 121, 0.12) !important;
            color: #EDE8DC;
        }
        .benefit-list li span {
            color: #61caee !important;
            margin-right: 12px;
        }

        .advantage-row { margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid rgba(240, 22, 121, 0.1); }
        
        .advantage-title { 
            font-family: 'DM Sans', sans-serif !important;
            font-weight: 700 !important;
            color: #61caee !important; 
            font-size: 22px !important; 
            text-transform: none !important;
            letter-spacing: 0px !important;
        }
        .courtside-footer-public {
            background-color: #000000;
            border-top: 1px solid #160a0e;
            padding: 100px 80px 40px;
        }

        .footer-brand-img {
            height: 45px;
            width: auto;
            object-fit: contain;
            margin-bottom: 20px;
        }

        .footer-headline-nav {
            font-family: 'DM Sans', sans-serif !important;
            font-weight: 700;
            font-size: 14px;
            color: #ffffff;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .footer-link-item {
            font-family: 'DM Sans', sans-serif !important;
            font-weight: 400;
            font-size: 14px;
            color: #8A7E6C;
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            text-decoration: none !important;
            line-height: 1.6;
            transition: color 0.3s;
        }

        .footer-link-item:hover {
            color: #e81b7b;
        }

        .footer-link-item span {
            margin-top: 3px;
            margin-right: 12px;
            font-size: 12px;
            color: #e81b7b;
        }

        .footer-bottom-copyright {
            border-top: 1px solid #160a0e;
            margin-top: 60px;
            padding-top: 30px;
            font-family: 'DM Sans', sans-serif !important;
            font-weight: 400;
            font-size: 13px;
            color: #4a4238;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-inverse navbar-landing-public" id="mainNavbar">
        <div class="container-fluid">
            
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">
                    <img src="assets/logo/Afterhour.png" alt="Afterhour Logo" class="logo-img">
                </a>
            </div>
            
            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php?p=login"><span class="glyphicon glyphicon-user"></span> ENTER HUB</a></li>
            </ul>
            
        </div>
    </nav>
    <div class="main-landing-wrapper">
        <?php
        switch ($page) {
            case 'login':
                include('pages/login.php'); 
                break;
                
            case 'register':
                include('pages/register.php'); 
                break;
                
            case 'home':
            default:
                ?>
                <div class="hero-premium-section">
                    <div class="hero-content-inner-leftaligned">
                        
                        <img src="assets/logo/Reserved For Afterhour.png" alt="Reserved For Afterhour" class="hero-title-graphic">
                        
                        <div class="hero-action-buttons-leftaligned">
                            <a href="index.php?p=login" class="btn-gold-action">BOOK TABLE NOW</a>
                            <a href="#membership" class="btn-outline-action">PRIVILEGE CLUB</a>
                        </div>

                    </div>
                </div>

                <div class="stats-counter-wrapper container-fluid">
                    <div class="stats-premium-box">
                        
                        <div class="stats-item-block">
                            <div class="stats-huge-number">30.000+</div>
                            <div class="stats-label-text">PEMAIN ACTIVE</div>
                        </div>

                        <div class="stats-item-block">
                            <div class="stats-huge-number">6+</div>
                            <div class="stats-label-text">LUXURY OUTLETS</div>
                        </div>

                        <div class="stats-item-block">
                            <div class="stats-huge-number">120+</div>
                            <div class="stats-label-text">PREMIUM TABLES</div>
                        </div>

                    </div>
                </div>

                <div class="section-padding container-fluid">
                    <div class="row row-flex-responsive">
                        
                        <div class="col-md-4 locations-sidebar-bg">
                            <span class="hero-eyebrow-text" style="font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight:300; color: #f01679; letter-spacing: 3px;">LOCATIONS</span>
                                <h2 class="section-title-editorial">THE LOCALS HUB.</h2>
                                <p class="section-desc-editorial">
                                    Nikmati pengalaman bermain billiard premium dengan meja standar internasional, stik berkualitas tinggi, serta ambience lounge yang BAM eksklusif di 6 lokasi strategis Jakarta.
                                </p>
                            <a href="index.php?p=login" class="btn-outline-action" style="margin-left:0; border-color:#f01679; color:#f01679; margin-top:20px;">VIEW ON MAP</a>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="horizontal-scroll-container">
                                
                                <div class="outlet-card-premium">
                                    <div class="outlet-image-holder" style="background-image: url('assets/stocks/Afterhour (1).png');"></div>
                                    <div class="outlet-body-inner">
                                        <span class="outlet-meta"><span class="glyphicon glyphicon-map-marker"></span> JAKARTA PUSAT</span>
                                        <h4 class="outlet-name-text">AH Cikini</h4>
                                        <div class="outlet-price-info">Pusat Kuliner Cikini Hub &middot; <strong style="color:#d1fd79;">PREMIUM</strong></div>
                                    </div>
                                </div>

                                <div class="outlet-card-premium">
                                    <div class="outlet-image-holder" style="background-image: url('assets/stocks/Afterhour (1).png');"></div>
                                    <div class="outlet-body-inner">
                                        <span class="outlet-meta"><span class="glyphicon glyphicon-map-marker"></span> JAKARTA PUSAT</span>
                                        <h4 class="outlet-name-text">AH Menteng</h4>
                                        <div class="outlet-price-info">Kawasan Elit Menteng &middot; <strong style="color:#61caee;">VIP LOUNGE</strong></div>
                                    </div>
                                </div>

                                <div class="outlet-card-premium">
                                    <div class="outlet-image-holder" style="background-image: url('assets/stocks/Afterhour (1).png');"></div>
                                    <div class="outlet-body-inner">
                                        <span class="outlet-meta"><span class="glyphicon glyphicon-map-marker"></span> JAKARTA UTARA</span>
                                        <h4 class="outlet-name-text">AH Sunter</h4>
                                        <div class="outlet-price-info">Sunter Luxury Area &middot; <strong style="color:#d1fd79;">PREMIUM</strong></div>
                                    </div>
                                </div>

                                <div class="outlet-card-premium">
                                    <div class="outlet-image-holder" style="background-image: url('assets/stocks/Afterhour (1).png');"></div>
                                    <div class="outlet-body-inner">
                                        <span class="outlet-meta"><span class="glyphicon glyphicon-map-marker"></span> JAKARTA UTARA</span>
                                        <h4 class="outlet-name-text">AH PIK</h4>
                                        <div class="outlet-price-info">Pantai Indah Kapuk Metro &middot; <strong style="color:#f01679;">VVIP LUX</strong></div>
                                    </div>
                                </div>

                                <div class="outlet-card-premium">
                                    <div class="outlet-image-holder" style="background-image: url('assets/stocks/Afterhour (1).png');"></div>
                                    <div class="outlet-body-inner">
                                        <span class="outlet-meta"><span class="glyphicon glyphicon-map-marker"></span> JAKARTA SELATAN</span>
                                        <h4 class="outlet-name-text">AH Kemang</h4>
                                        <div class="outlet-price-info">Kemang Lounge Area &middot; <strong style="color:#61caee;">VIP LOUNGE</strong></div>
                                    </div>
                                </div>

                                <div class="outlet-card-premium">
                                    <div class="outlet-image-holder" style="background-image: url('assets/stocks/Afterhour (1).png');"></div>
                                    <div class="outlet-body-inner">
                                        <span class="outlet-meta"><span class="glyphicon glyphicon-map-marker"></span> JAKARTA BARAT</span>
                                        <h4 class="outlet-name-text">AH Puri</h4>
                                        <div class="outlet-price-info">Puri Kembangan Area &middot; <strong style="color:#d1fd79;">PREMIUM</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-padding container-fluid" style="background-color:#050505; border-top: 1px solid #000000;">
                    <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
                        <div class="col-md-5 text-center">
                            <div style="position: relative; padding: 20px; display: inline-block;">
                                <img src="assets/hero/Why Afterhour.png" style="width: 85%; border: 2px solid #000000; border-radius: 12px;" alt="Billiard Lounge">
                            </div>
                        </div>
                        
                        <div class="col-md-7" style="padding-left: 30px;">
                            <span class="hero-eyebrow-text" style="font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight:300; color: #61caee;">THE PERKS</span>
                            <h2 class="section-title-editorial" style="margin-bottom: 30px;">KENAPA HARUS DI AFTERHOUR</h2>
                            
                            <div class="advantage-row">
                                <h5 class="advantage-title">Area Open Table</h5>
                                <p class="advantage-desc">Deretan meja billiard yang terbuka sudah dilengkapi area khusus smoking maupun non-smoking.</p>
                            </div>
                            <div class="advantage-row">
                                <h5 class="advantage-title">Ruangan VIP &amp; VVIP</h5>
                                <p class="advantage-desc">Terdapat ruangan khusus dengan pilihan smoking area atau non-smoking dengan space ruangan yang jauh lebih luas and privat.</p>
                            </div>
                            <div class="advantage-row">
                                <h5 class="advantage-title">PB Bar Integration</h5>
                                <p class="advantage-desc">Terintegrasi dengan coffee shop eksklusif and tempat nongkrong yang mantap di setiap cabang outlet resmi kami.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="membership" class="membership-block-bg container-fluid">
                    <div class="row row-flex-responsive">
                        <div class="col-md-6" style="padding-right: 50px;">
                            <span class="hero-eyebrow-text" style="font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight:300; color: #d1fd79;">PRIVILEGED CLUB</span>
                            <h2 class="section-title-editorial" style="font-size: 60px !important; line-height: 0.9;">PLAY MORE.<br>PAY LESS.</h2>
                            <p>Nikmati keuntungan luar biasa bermain biliar tanpa batas serta berbagai macam potongan harga eksklusif F&amp;B dengan mendaftarkan diri Anda ke dalam keanggotaan resmi Afterhour Privilege Member.</p>
                        </div>

                        <div class="col-md-6">
                            <div class="premium-price-box">
                                <span class="card-eyebrow" style="font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight:300; font-size:12px; letter-spacing:3px; color: #61caee;">[ VIP ACCESS ]</span>
                                <h3 class="card-title" style="font-size: 32px; margin-top:5px;">MEMBERSHIP PERKS</h3>
                                
                                <ul class="benefit-list">
                                    <li><span class="glyphicon glyphicon-triangle-right"></span> Regular Table: Rp 35.000 / Jam</li>
                                    <li><span class="glyphicon glyphicon-triangle-right"></span> VIP Area: Diskon 10%</li>
                                    <li><span class="glyphicon glyphicon-triangle-right"></span> VVIP Lounge: Diskon 10%</li>
                                    <li><span class="glyphicon glyphicon-triangle-right"></span> Food &amp; Beverages: Diskon 10%</li>
                                </ul>
                                
                                <div style="margin: 30px 0;">
                                    <span style="font-size: 46px; font-weight:700; color:#d1fd79;">Rp 200.000</span>
                                </div>

                                <a href="index.php?p=login" class="btn-gold-action" style="width:100%; justify-content:center;">CLAIM MEMBERSHIP SLOT</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                break;
        }
        ?>
    </div>

    <footer class="courtside-footer-public container-fluid">
        <div class="row">
            <div class="col-md-6">
                <img src="assets/Afterhour.png" alt="Afterhour Logo" class="footer-brand-img">
                <p class="footer-brand-subtitle" style="max-width: 400px; line-height: 1.6;">
                    Esensi olahraga billiard premium berbalut kenyamanan lounge eksklusif. Rasakan pengalaman bermain kelas dunia di setiap sudut ruang kami.
                </p>
            </div>
            <div class="col-md-3 col-sm-6 footer-col-right">
                <h5 class="footer-headline-nav">Visit Us</h5>
                <div class="footer-link-item">
                    <span class="glyphicon glyphicon-map-marker"></span>
                    Menara 165, Lantai 18, Jl. TB Simatupang No. 1, Cilandak Timur, Pasar Minggu, Jakarta Selatan, 12560
                </div>
                <h5 class="footer-headline-nav" style="margin-top: 30px;">Follow Us</h5>
                <a href="https://instagram.com/afterhour_id" target="_blank" class="footer-link-item">
                    <span class="glyphicon glyphicon-camera"></span> @afterhour_id
                </a>
                <a href="https://tiktok.com/@afterhour_id" target="_blank" class="footer-link-item">
                    <span class="glyphicon glyphicon-play"></span> @afterhour_id
                </a>
            </div>
            <div class="col-md-3 col-sm-6 footer-col-right">
                <h5 class="footer-headline-nav">Contact Us</h5>
                <a href="mailto:hello@courtside.id" class="footer-link-item">
                    <span class="glyphicon glyphicon-envelope"></span> hello@courtside.id
                </a>
                <a href="tel:+6282123168944" class="footer-link-item">
                    <span class="glyphicon glyphicon-earphone"></span> +62 821-2316-8944
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-left footer-bottom-copyright">
                &copy; Afterhour Billiard &amp; Lounge. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script>
        window.addEventListener('scroll', function() {
            var navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.add('nav-scrolled'); 
            } else {
                navbar.classList.remove('nav-scrolled'); 
            }
        });
    </script>
</body>
</html>
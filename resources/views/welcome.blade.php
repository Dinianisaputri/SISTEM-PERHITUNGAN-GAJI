<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistem HRD | PT Starfood International</title>

    <meta name="description" content="Sistem Informasi HRD PT Starfood International untuk pengelolaan karyawan, absensi, penggajian, jadwal kerja, dan regulasi perusahaan.">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght\@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>

        :root {

            --primary: #123b32;

            --primary-dark: #0b2c25;

            --accent: #34d399;

            --text: #172b26;

            --muted: #64748b;

            --border: #e2e8f0;

            --surface: #ffffff;

            --soft: #f8fafc;

        }

        \* {

            box-sizing: border-box;

        }

        html {

            scroll-behavior: smooth;

            scroll-padding-top: 85px;

        }

        body {

            margin: 0;

            font-family: 'Inter', sans-serif;

            color: var(--text);

            background: var(--surface);

            line-height: 1.6;

            -webkit-font-smoothing: antialiased;

        }

        a {

            color: inherit;

            text-decoration: none;

        }

        button, a {

            -webkit-tap-highlight-color: transparent;

        }

        .container {

            width: min(1120px, calc(100% - 40px));

            margin: 0 auto;

        }

        .navbar {

            position: sticky;

            top: 0;

            z-index: 100;

            background: rgba(255, 255, 255, 0.96);

            border-bottom: 1px solid var(--border);

            backdrop-filter: blur(14px);

        }

        .nav-inner {

            min-height: 76px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 24px;

        }

        .brand {

            display: flex;

            align-items: center;

            gap: 12px;

            min-width: 0;

        }

        .brand-logo {

            width: 44px;

            height: 44px;

            flex-shrink: 0;

            display: grid;

            place-items: center;

            border-radius: 12px;

            color: white;

            background: var(--primary);

            font-weight: 800;

            font-size: 14px;

        }

        .brand-name {

            display: block;

            font-size: 13px;

            font-weight: 800;

            letter-spacing: .02em;

            color: #142d26;

        }

        .brand-subtitle {

            display: block;

            margin-top: 2px;

            font-size: 11px;

            color: var(--muted);

        }

        .nav-links {

            display: flex;

            align-items: center;

            gap: 30px;

        }

        .nav-links a {

            font-size: 13px;

            font-weight: 600;

            color: #52635d;

            transition: color .2s;

        }

        .nav-links a:hover {

            color: #16805d;

        }

        .btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 9px;

            min-height: 44px;

            padding: 11px 20px;

            border: 1px solid transparent;

            border-radius: 9px;

            font-family: inherit;

            font-size: 13px;

            font-weight: 700;

            cursor: pointer;

            transition: background .2s, transform .2s, border-color .2s;

        }

        .btn:hover {

            transform: translateY(-1px);

        }

        .btn-primary {

            color: white;

            background: #176b50;

        }

        .btn-primary:hover {

            background: #10543e;

        }

        .btn-light {

            color: var(--primary);

            background: white;

        }

        .btn-light:hover {

            background: #ecfdf5;

        }

        .btn-outline {

            color: white;

            border-color: rgba(255,255,255,.3);

            background: transparent;

        }

        .btn-outline:hover {

            background: rgba(255,255,255,.09);

        }

        .hero {

            position: relative;

            overflow: hidden;

            color: white;

            background:

                radial-gradient(circle at 85% 15%, rgba(52,211,153,.17), transparent 28%),

                linear-gradient(120deg, #0b2c25, #123b32 60%, #174b3d);

        }

        .hero::before {

            content: "";

            position: absolute;

            inset: 0;

            pointer-events: none;

            opacity: .22;

            background-image:

                linear-gradient(rgba(255,255,255,.07) 1px, transparent 1px),

                linear-gradient(90deg, rgba(255,255,255,.07) 1px, transparent 1px);

            background-size: 42px 42px;

        }

        .hero-grid {

            position: relative;

            display: grid;

            grid-template-columns: 1.05fr .95fr;

            align-items: center;

            gap: 64px;

            padding-top: 90px;

            padding-bottom: 100px;

        }

        .eyebrow {

            display: inline-flex;

            align-items: center;

            gap: 9px;

            padding: 8px 13px;

            border: 1px solid rgba(52,211,153,.28);

            border-radius: 30px;

            background: rgba(52,211,153,.09);

            color: #a7f3d0;

            font-size: 10px;

            font-weight: 800;

            letter-spacing: .12em;

        }

        .eyebrow-dot {

            width: 7px;

            height: 7px;

            border-radius: 50%;

            background: var(--accent);

        }

        .hero h1 {

            max-width: 620px;

            margin: 26px 0 0;

            font-size: clamp(36px, 4.5vw, 58px);

            line-height: 1.13;

            letter-spacing: -.045em;

            font-weight: 800;

        }

        .hero h1 span {

            color: #6ee7b7;

        }

        .hero-description {

            max-width: 570px;

            margin: 22px 0 0;

            color: #d1e7de;

            font-size: 15px;

            line-height: 1.9;

        }

        .hero-actions {

            display: flex;

            flex-wrap: wrap;

            gap: 12px;

            margin-top: 30px;

        }

        .trust-list {

            display: flex;

            flex-wrap: wrap;

            gap: 18px;

            margin-top: 27px;

            color: #c3dcd1;

            font-size: 12px;

        }

        .trust-item::before {

            content: "✓";

            margin-right: 7px;

            color: #6ee7b7;

            font-weight: 800;

        }

        .preview {

            position: relative;

            padding: 0;

            border: 1px solid rgba(255,255,255,.22);

            border-radius: 18px;

            background: white;

            color: var(--text);

            box-shadow: 0 28px 80px rgba(0,0,0,.25);

        }

        .preview-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 12px;

            padding: 18px 20px;

            border-bottom: 1px solid var(--border);

        }

        .preview-brand {

            display: flex;

            align-items: center;

            gap: 10px;

        }

        .preview-brand-icon {

            width: 36px;

            height: 36px;

            display: grid;

            place-items: center;

            border-radius: 9px;

            color: white;

            background: var(--primary);

            font-size: 11px;

            font-weight: 800;

        }

        .preview-title {

            margin: 0;

            font-size: 13px;

            font-weight: 800;

        }

        .preview-subtitle {

            margin: 2px 0 0;

            font-size: 11px;

            color: var(--muted);

        }

        .status-pill {

            padding: 6px 10px;

            border-radius: 20px;

            color: #166534;

            background: #dcfce7;

            font-size: 10px;

            font-weight: 800;

        }

        .preview-body {

            padding: 23px;

            background: #f8fafc;

            border-radius: 0 0 18px 18px;

        }

        .preview-body h3 {

            margin: 0;

            font-size: 19px;

        }

        .preview-body > p {

            margin: 5px 0 20px;

            color: var(--muted);

            font-size: 12px;

        }

        .preview-cards {

            display: grid;

            grid-template-columns: repeat(2, minmax(0, 1fr));

            gap: 12px;

        }

        .preview-card {

            min-height: 125px;

            padding: 15px;

            border: 1px solid var(--border);

            border-radius: 12px;

            background: white;

        }

        .preview-icon {

            width: 34px;

            height: 34px;

            display: grid;

            place-items: center;

            border-radius: 9px;

            background: #ecfdf5;

            color: #176b50;

            font-size: 17px;

        }

        .preview-card small {

            display: block;

            margin-top: 13px;

            color: var(--muted);

            font-size: 11px;

        }

        .preview-card strong {

            display: block;

            margin-top: 3px;

            font-size: 12px;

        }

        .preview-note {

            display: flex;

            align-items: center;

            gap: 12px;

            margin-top: 14px;

            padding: 15px;

            border-radius: 11px;

            color: white;

            background: #176b50;

        }

        .preview-note-icon {

            width: 35px;

            height: 35px;

            flex-shrink: 0;

            display: grid;

            place-items: center;

            border-radius: 9px;

            background: rgba(255,255,255,.13);

        }

        .preview-note strong {

            display: block;

            font-size: 12px;

        }

        .preview-note small {

            display: block;

            margin-top: 3px;

            color: #d1fae5;

            font-size: 10px;

        }

        .section {

            padding: 88px 0;

        }

        .section-heading {

            max-width: 690px;

            margin: 0 auto;

            text-align: center;

        }

        .section-label {

            color: #176b50;

            font-size: 11px;

            font-weight: 800;

            letter-spacing: .18em;

            text-transform: uppercase;

        }

        .section-heading h2,

        .about h2 {

            margin: 13px 0 0;

            color: #142d26;

            font-size: clamp(27px, 3.2vw, 38px);

            line-height: 1.25;

            letter-spacing: -.035em;

        }

        .section-heading p {

            margin: 15px 0 0;

            color: var(--muted);

            font-size: 14px;

            line-height: 1.9;

        }

        .feature-grid {

            display: grid;

            grid-template-columns: repeat(3, minmax(0, 1fr));

            gap: 20px;

            margin-top: 48px;

        }

        .feature-card {

            padding: 27px;

            border: 1px solid var(--border);

            border-radius: 15px;

            background: white;

            transition: transform .2s, box-shadow .2s, border-color .2s;

        }

        .feature-card:hover {

            transform: translateY(-4px);

            border-color: #a7f3d0;

            box-shadow: 0 16px 35px rgba(15, 60, 43, .07);

        }

        .feature-icon {

            width: 47px;

            height: 47px;

            display: grid;

            place-items: center;

            border-radius: 12px;

            color: #176b50;

            background: #ecfdf5;

            font-size: 21px;

        }

        .feature-card h3 {

            margin: 20px 0 0;

            font-size: 16px;

            font-weight: 800;

        }

        .feature-card p {

            margin: 10px 0 0;

            color: var(--muted);

            font-size: 13px;

            line-height: 1.9;

        }

        .about-section {

            padding: 85px 0;

            border-top: 1px solid var(--border);

            border-bottom: 1px solid var(--border);

            background: var(--soft);

        }

        .about {

            display: grid;

            grid-template-columns: .9fr 1.1fr;

            align-items: start;

            gap: 65px;

        }

        .about-copy p {

            margin: 0 0 15px;

            color: #52635d;

            font-size: 14px;

            line-height: 2;

        }

        .cta-section {

            padding: 75px 0;

        }

        .cta {

            padding: 48px 28px;

            border-radius: 22px;

            text-align: center;

            color: white;

            background:

                radial-gradient(circle at 50% 0%, rgba(52,211,153,.18), transparent 55%),

                var(--primary);

        }

        .cta h2 {

            margin: 0;

            font-size: clamp(25px, 3vw, 33px);

            letter-spacing: -.03em;

        }

        .cta p {

            max-width: 580px;

            margin: 13px auto 0;

            color: #d1e7de;

            font-size: 14px;

            line-height: 1.8;

        }

        .cta .btn {

            margin-top: 23px;

        }

        .footer {

            border-top: 1px solid var(--border);

            background: white;

        }

        .footer-inner {

            min-height: 92px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            padding-top: 20px;

            padding-bottom: 20px;

        }

        .footer-copy {

            color: var(--muted);

            font-size: 11px;

        }

        @media (max-width: 900px) {

            .hero-grid {

                grid-template-columns: 1fr;

                gap: 48px;

                padding-top: 70px;

                padding-bottom: 75px;

            }

            .hero-copy {

                max-width: 720px;

            }

            .preview {

                width: 100%;

                max-width: 600px;

                margin: 0 auto;

            }

            .feature-grid {

                grid-template-columns: repeat(2, minmax(0, 1fr));

            }

            .about {

                grid-template-columns: 1fr;

                gap: 25px;

            }

        }

        @media (max-width: 640px) {

            .container {

                width: min(100% - 30px, 1120px);

            }

            .nav-inner {

                min-height: 68px;

                gap: 12px;

            }

            .brand-logo {

                width: 39px;

                height: 39px;

            }

            .brand-name {

                font-size: 11px;

            }

            .brand-subtitle {

                font-size: 10px;

            }

            .nav-links {

                display: none;

            }

            .nav-inner > .btn {

                min-height: 39px;

                padding: 9px 13px;

                font-size: 12px;

            }

            .hero-grid {

                padding-top: 57px;

                padding-bottom: 65px;

                gap: 39px;

            }

            .eyebrow {

                font-size: 9px;

                letter-spacing: .07em;

            }

            .hero h1 {

                margin-top: 22px;

                font-size: 39px;

            }

            .hero-description {

                font-size: 14px;

            }

            .hero-actions .btn {

                width: 100%;

            }

            .trust-list {

                gap: 11px;

                font-size: 11px;

            }

            .preview-header {

                padding: 14px;

            }

            .preview-body {

                padding: 14px;

            }

            .preview-card {

                min-height: 118px;

                padding: 12px;

            }

            .section,

            .about-section {

                padding: 65px 0;

            }

            .feature-grid {

                grid-template-columns: 1fr;

                gap: 14px;

                margin-top: 32px;

            }

            .feature-card {

                padding: 22px;

            }

            .about {

                gap: 20px;

            }

            .cta-section {

                padding: 55px 0;

            }

            .cta {

                padding: 38px 20px;

            }

            .footer-inner {

                align-items: flex-start;

                flex-direction: column;

                justify-content: center;

                gap: 8px;

            }

        }



        /* Penyegaran tampilan: landing page publik yang lebih tenang dan profesional */
        :root {
            --primary: #174b3d;
            --primary-dark: #10382e;
            --accent: #2e8b68;
            --muted: #718078;
            --surface: #ffffff;
            --line: #e5ebe7;
        }

        body { background: #fff; color: #24352e; }
        .navbar {
            min-height: 76px;
            background: rgba(255,255,255,.97);
            border-bottom: 1px solid #edf0ee;
            box-shadow: none;
        }
        .nav-inner { min-height: 76px; }
        .brand-logo { border-radius: 9px; background: #174b3d; }
        .nav-links { gap: 26px; }
        .nav-links a { color: #53645b; font-weight: 500; }
        .btn { border-radius: 7px; min-height: 42px; padding: 10px 17px; }
        .btn-primary { background: #176b50; }
        .hero {
            background: #f7faf8;
            color: #24352e;
            min-height: 0;
            padding: 76px 0 78px;
        }
        .hero::before { display: none; }
        .hero-grid {
            grid-template-columns: minmax(0, 1.05fr) minmax(0, .95fr);
            gap: clamp(36px, 6vw, 82px);
            align-items: center;
        }
        .hero-copy { max-width: 590px; }
        .eyebrow {
            color: #176b50;
            background: #eaf4ef;
            border: 1px solid #dcebe2;
            letter-spacing: .08em;
            font-size: 10px;
            padding: 9px 13px;
        }
        .eyebrow-dot { background: #2e8b68; }
        .hero h1 {
            color: #173b30;
            font-size: clamp(38px, 4vw, 54px);
            line-height: 1.12;
            letter-spacing: -.045em;
            margin: 24px 0 18px;
            max-width: 590px;
        }
        .hero h1 span { color: #2e8b68; }
        .hero-description {
            color: #63736a;
            font-size: 15px;
            line-height: 1.85;
            max-width: 540px;
        }
        .hero-actions { margin-top: 28px; gap: 12px; }
        .btn-light { color: #fff; background: #176b50; }
        .btn-light:hover { background: #12563f; }
        .btn-outline { color: #315347; border-color: #cbd9d1; background: #fff; }
        .btn-outline:hover { background: #edf5f0; }
        .trust-list { margin-top: 25px; gap: 18px; color: #63736a; }
        .trust-item::before { color: #2e8b68; }
        .preview {
            width: 100%;
            max-width: 500px;
            justify-self: end;
            border: 1px solid #e1e9e4;
            border-radius: 12px;
            box-shadow: 0 18px 45px rgba(27, 64, 47, .07);
            transform: none;
        }
        .preview-header { padding: 17px 20px; }
        .preview-content { padding: 22px; }
        .preview-card, .preview-stat, .feature-card {
            border-radius: 9px;
            box-shadow: none;
        }
        .preview-grid { gap: 12px; }
        .preview-stat { border: 1px solid #e5ebe7; }
        .preview-footer { border-radius: 8px; }
        .section { padding-top: 72px; padding-bottom: 72px; }
        .section-heading h2 { color: #173b30; letter-spacing: -.025em; }
        .section-heading p { color: #718078; }
        .feature-card { border: 1px solid #e5ebe7; background: #fff; }
        .feature-card:hover { border-color: #c5dbce; box-shadow: 0 10px 24px rgba(27,64,47,.05); }
        .feature-icon { border-radius: 8px; background: #edf6f0; color: #176b50; }
        .about, .cta { background: #f7faf8; }
        .cta-inner { border-radius: 12px; }
        .footer { border-top: 1px solid #e8eeea; }

        @media (max-width: 900px) {
            .hero { padding: 58px 0 64px; }
            .hero-grid { grid-template-columns: 1fr; gap: 38px; }
            .hero-copy { max-width: 680px; }
            .preview { justify-self: start; max-width: 620px; }
        }
        @media (max-width: 600px) {
            .navbar, .nav-inner { min-height: 66px; }
            .brand-subtitle { font-size: 10px; }
            .nav-links { gap: 14px; }
            .hero { padding: 44px 0 48px; }
            .hero h1 { font-size: clamp(34px, 9vw, 43px); }
            .hero-description { font-size: 14px; }
            .trust-list { flex-wrap: wrap; gap: 10px 15px; }
            .section { padding-top: 52px; padding-bottom: 52px; }
        }

    </style>

</head>

<body>

    <header class="navbar">

        <nav class="container nav-inner">

            <a href="{{ route('home') }}" class="brand">

                <span class="brand-logo">PG</span>

                <span>

                    <span class="brand-name">STARFOOD INTERNATIONAL</span>

                    <span class="brand-subtitle">Human Resources Information System</span>

                </span>

            </a>

            <div class="nav-links">

                <a href="#beranda">Beranda</a>

                <a href="#fitur">Fitur Sistem</a>

                <a href="#tentang">Tentang Sistem</a>

            </div>

            @auth

                <a href="{{ route('dashboard') }}" class="btn btn-primary">

                    Buka Dashboard →

                </a>

            @else

                <a href="{{ route('login') }}" class="btn btn-primary">

                    Login →

                </a>

            @endauth

        </nav>

    </header>

    <main>

        <section class="hero" id="beranda">

            <div class="container hero-grid">

                <div class="hero-copy">

                    <div class="eyebrow">

                        <span class="eyebrow-dot"></span>

                        HUMAN RESOURCES INFORMATION SYSTEM

                    </div>

                    <h1>

                        Pengelolaan SDM yang

                        <span>Lebih Terintegrasi.</span>

                    </h1>

                    <p class="hero-description">

                        Sistem informasi HRD PT Starfood International untuk membantu

                        pengelolaan data karyawan, kehadiran, penggajian, jadwal kerja,

                        dan regulasi perusahaan secara lebih terstruktur.

                    </p>

                    <div class="hero-actions">

                        @auth

                            <a href="{{ route('dashboard') }}" class="btn btn-light">

                                Buka Dashboard →

                            </a>

                        @else

                            <a href="{{ route('login') }}" class="btn btn-light">

                                Masuk ke Sistem →

                            </a>

                        @endauth

                        <a href="#fitur" class="btn btn-outline">

                            Jelajahi Fitur

                        </a>

                    </div>

                    <div class="trust-list">

                        <span class="trust-item">Data terpusat</span>

                        <span class="trust-item">Akses terkontrol</span>

                        <span class="trust-item">Administrasi terstruktur</span>

                    </div>

                </div>

                <div class="preview">

                    <div class="preview-header">

                        <div class="preview-brand">

                            <span class="preview-brand-icon">PG</span>

                            <span>

                                <span class="preview-title">Sistem HRD</span>

                                <span class="preview-subtitle">Ringkasan sistem</span>

                            </span>

                        </div>

                        <span class="status-pill">Dashboard</span>

                    </div>

                    <div class="preview-body">

                        <h3>Selamat datang</h3>

                        <p>Ringkasan pengelolaan HRD dalam satu sistem.</p>

                        <div class="preview-cards">

                            <div class="preview-card">

                                <span class="preview-icon">♙</span>

                                <small>Data Karyawan</small>

                                <strong>Manajemen SDM</strong>

                            </div>

                            <div class="preview-card">

                                <span class="preview-icon">◷</span>

                                <small>Absensi</small>

                                <strong>Data Kehadiran</strong>

                            </div>

                            <div class="preview-card">

                                <span class="preview-icon">▤</span>

                                <small>Penggajian</small>

                                <strong>Perhitungan Gaji</strong>

                            </div>

                            <div class="preview-card">

                                <span class="preview-icon">▧</span>

                                <small>Regulasi</small>

                                <strong>Dokumen Perusahaan</strong>

                            </div>

                        </div>

                        <div class="preview-note">

                            <span class="preview-note-icon">✓</span>

                            <span>

                                <strong>Terintegrasi dalam satu platform</strong>

                                <small>Mendukung pengelolaan administrasi HRD.</small>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <section class="section" id="fitur">

            <div class="container">

                <div class="section-heading">

                    <span class="section-label">Fitur Sistem</span>

                    <h2>Seluruh Kebutuhan HRD dalam Satu Platform</h2>

                    <p>

                        Mendukung pengelolaan informasi sumber daya manusia melalui

                        fitur yang saling melengkapi.

                    </p>

                </div>

                <div class="feature-grid">

                    <article class="feature-card">

                        <span class="feature-icon">♙</span>

                        <h3>Data Karyawan</h3>

                        <p>

                            Pengelolaan data karyawan dan pengelompokan berdasarkan

                            jenis karyawan serta informasi pekerjaan.

                        </p>

                    </article>

                    <article class="feature-card">

                        <span class="feature-icon">◷</span>

                        <h3>Fingerprint & Absensi</h3>

                        <p>

                            Pencatatan data fingerprint, pengelolaan PIN, jadwal kerja,

                            serta hari libur.

                        </p>

                    </article>

                    <article class="feature-card">

                        <span class="feature-icon">▤</span>

                        <h3>Penggajian</h3>

                        <p>

                            Mendukung proses perhitungan gaji, pengelolaan tarif,

                            dan penyimpanan arsip penggajian.

                        </p>

                    </article>

                    <article class="feature-card">

                        <span class="feature-icon">▦</span>

                        <h3>Jadwal Kerja</h3>

                        <p>

                            Pengelolaan master jam kerja dan jadwal kerja harian

                            sebagai referensi operasional.

                        </p>

                    </article>

                    <article class="feature-card">

                        <span class="feature-icon">▧</span>

                        <h3>Regulasi Perusahaan</h3>

                        <p>

                            Pencatatan dokumen regulasi, pemantauan status pemenuhan,

                            dan masa berlaku dokumen.

                        </p>

                    </article>

                    <article class="feature-card">

                        <span class="feature-icon">▣</span>

                        <h3>Arsip Penggajian</h3>

                        <p>

                            Penyimpanan ringkasan hasil penggajian untuk memudahkan

                            penelusuran data periode sebelumnya.

                        </p>

                    </article>

                </div>

            </div>

        </section>

        <section class="about-section" id="tentang">

            <div class="container about">

                <div>

                    <span class="section-label">Tentang Sistem</span>

                    <h2>Mendukung Administrasi HRD yang Lebih Terstruktur</h2>

                </div>

                <div class="about-copy">

                    <p>

                        Sistem HRD PT Starfood International dirancang sebagai

                        platform internal untuk mendukung pencatatan dan pengelolaan

                        administrasi sumber daya manusia.

                    </p>

                    <p>

                        Melalui satu sistem, pengguna yang memiliki akses dapat

                        mengelola data karyawan, memantau kehadiran, melakukan proses

                        penggajian, serta mengelola dokumen regulasi perusahaan.

                    </p>

                    <p>

                        Akses ke fitur internal dilakukan melalui akun yang telah

                        terdaftar sehingga informasi operasional tidak ditampilkan

                        pada halaman publik.

                    </p>

                    @auth

                        <a href="{{ route('dashboard') }}" class="btn btn-primary">

                            Buka Dashboard →

                        </a>

                    @else

                        <a href="{{ route('login') }}" class="btn btn-primary">

                            Login ke Sistem →

                        </a>

                    @endauth

                </div>

            </div>

        </section>

        <section class="cta-section">

            <div class="container">

                <div class="cta">

                    <h2>Akses Sistem HRD</h2>

                    <p>

                        Masuk menggunakan akun yang telah terdaftar untuk mengakses

                        fitur dan informasi internal perusahaan.

                    </p>

                    @auth

                        <a href="{{ route('dashboard') }}" class="btn btn-light">

                            Buka Dashboard →

                        </a>

                    @else

                        <a href="{{ route('login') }}" class="btn btn-light">

                            Login Sekarang →

                        </a>

                    @endauth

                </div>

            </div>

        </section>

    </main>

    <footer class="footer">

        <div class="container footer-inner">

            <div class="brand">

                <span class="brand-logo">PG</span>

                <span>

                    <span class="brand-name">STARFOOD INTERNATIONAL</span>

                    <span class="brand-subtitle">Human Resources Information System</span>

                </span>

            </div>

            <p class="footer-copy">

                &copy; {{ date('Y') }} PT Starfood International. Sistem HRD Internal.

            </p>

        </div>

    </footer>

</body>

</html>

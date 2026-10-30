<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistem HRD | PT Starfood International</title>

    <meta name="description" content="Sistem Informasi HRD PT Starfood International untuk pengelolaan karyawan, absensi, penggajian, jadwal kerja, dan regulasi perusahaan.">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

        * {

            box-sizing: border-box;

        }

        html {

            scroll-behavior: smooth;

            scroll-padding-top: 85px;

        }

        body {

            margin: 0;

            font-family: 'Plus Jakarta Sans', sans-serif;

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



        :root {
            --primary: #174b3d;
            --primary-dark: #10382e;
            --accent: #238263;
            --text: #20352d;
            --muted: #687a70;
            --border: #e3ebe5;
            --surface: #ffffff;
            --soft: #f6f9f5;
        }

        body {
            color: var(--text);
            background: #fff;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid #8ad2b1;
            outline-offset: 3px;
        }

        .navbar {
            background: rgba(255, 255, 255, .9);
            border-bottom-color: rgba(222, 233, 225, .8);
            backdrop-filter: blur(16px);
        }

        .brand-logo {
            border-radius: 14px;
            background: linear-gradient(145deg, #287d5c, #123f33);
            box-shadow: 0 6px 16px rgba(23, 75, 61, .18);
        }

        .nav-links a {
            position: relative;
            padding: 8px 0;
            color: #52665b;
        }

        .nav-links a::after {
            position: absolute;
            right: 0;
            bottom: 1px;
            left: 0;
            height: 2px;
            border-radius: 2px;
            background: #398c68;
            content: "";
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .2s ease;
        }

        .nav-links a:hover::after {
            transform: scaleX(1);
        }

        .btn {
            min-height: 46px;
            padding: 11px 20px;
            border-radius: 999px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #247c59, #176247);
            box-shadow: 0 7px 16px rgba(23, 98, 71, .15);
        }

        .hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            padding: 44px 0 60px;
            background:
                radial-gradient(ellipse at 82% 18%, rgba(196, 228, 193, .55), transparent 34%),
                radial-gradient(ellipse at 4% 92%, rgba(232, 241, 220, .8), transparent 35%),
                linear-gradient(125deg, #fbfcf8 8%, #f2f7f0 100%);
        }

        .hero::before {
            inset: 0;
            opacity: .35;
            background-image: radial-gradient(rgba(57, 118, 83, .16) .8px, transparent .8px);
            background-size: 22px 22px;
            mask-image: linear-gradient(90deg, transparent, #000 65%);
        }

        .hero-grid {
            gap: clamp(36px, 6vw, 78px);
            padding-top: 18px;
            padding-bottom: 18px;
        }

        .hero-copy {
            position: relative;
            z-index: 1;
            max-width: 600px;
        }

        .eyebrow {
            padding: 9px 14px;
            border: 1px solid #d5e8d9;
            border-radius: 999px;
            color: #26714f;
            background: rgba(255, 255, 255, .7);
            letter-spacing: .07em;
            box-shadow: 0 4px 14px rgba(45, 98, 65, .05);
        }

        .eyebrow-dot {
            box-shadow: 0 0 0 4px rgba(46, 139, 104, .12);
        }

        .hero h1 {
            max-width: 600px;
            margin: 22px 0 16px;
            color: #173b30;
            font-size: clamp(40px, 4.8vw, 62px);
            line-height: 1.08;
            letter-spacing: -.055em;
        }

        .hero h1 span {
            color: #2e8b68;
        }

        .hero-description {
            max-width: 530px;
            margin-top: 0;
            color: #607269;
            font-size: 15px;
            line-height: 1.85;
        }

        .hero-actions {
            gap: 13px;
            margin-top: 25px;
        }

        .btn-light {
            color: #fff;
            background: linear-gradient(135deg, #247c59, #176247);
            box-shadow: 0 8px 18px rgba(23, 98, 71, .16);
        }

        .btn-light:hover {
            background: #12563f;
        }

        .btn-outline {
            border-color: #ccdbd0;
            border-radius: 999px;
            color: #315347;
            background: rgba(255, 255, 255, .65);
        }

        .btn-outline:hover {
            background: #fff;
        }

        .trust-list {
            gap: 12px 18px;
            margin-top: 22px;
            color: #63746a;
        }

        .trust-item {
            padding: 7px 11px;
            border: 1px solid rgba(218, 231, 220, .8);
            border-radius: 999px;
            background: rgba(255, 255, 255, .52);
        }

        .trust-item::before {
            color: #2e8b68;
        }

        .preview {
            z-index: 0;
            width: 100%;
            max-width: 510px;
            justify-self: end;
            overflow: visible;
            border: 1px solid rgba(224, 234, 226, .95);
            border-radius: 22px;
            box-shadow: 0 24px 65px rgba(32, 71, 48, .13), 0 4px 12px rgba(32, 71, 48, .05);
            transform: rotate(1deg);
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .preview::after {
            position: absolute;
            z-index: -1;
            top: -20px;
            right: -24px;
            width: 110px;
            height: 110px;
            border: 1px solid rgba(85, 151, 105, .17);
            border-radius: 50%;
            background: rgba(255, 255, 255, .28);
            content: "";
        }

        .preview:hover {
            box-shadow: 0 30px 75px rgba(32, 71, 48, .17), 0 4px 12px rgba(32, 71, 48, .05);
            transform: rotate(0) translateY(-4px);
        }

        .preview-header {
            padding: 18px 21px;
            border-radius: 22px 22px 0 0;
            background: #fff;
        }

        .preview-brand-icon {
            border-radius: 11px;
            background: linear-gradient(145deg, #287d5c, #123f33);
        }

        .status-pill {
            padding: 7px 11px;
            border: 1px solid #d8efdf;
            color: #26714f;
            background: #eff9f0;
        }

        .preview-body {
            padding: 23px;
            border-radius: 0 0 22px 22px;
            background: linear-gradient(155deg, #f8faf7, #f0f6f1);
        }

        .preview-body h3 {
            color: #1a3e31;
        }

        .preview-cards {
            gap: 13px;
        }

        .preview-card {
            min-height: 126px;
            padding: 16px;
            border-color: #e6ede7;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(34, 73, 48, .035);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .preview-card:hover {
            box-shadow: 0 8px 20px rgba(34, 73, 48, .08);
            transform: translateY(-2px);
        }

        .preview-icon {
            border-radius: 11px;
            color: #247650;
            background: linear-gradient(145deg, #e5f5e9, #f1f8eb);
        }

        .preview-note {
            border-radius: 14px;
            background: linear-gradient(120deg, #237755, #14563f);
        }

        .section {
            padding: 82px 0;
        }

        .section-heading {
            max-width: 720px;
        }

        .section-label {
            display: inline-flex;
            align-items: center;
            padding: 7px 12px;
            border: 1px solid #dcebe0;
            border-radius: 999px;
            color: #26714f;
            background: #f0f7f0;
            font-size: 10px;
            letter-spacing: .12em;
        }

        .section-heading h2,
        .about h2 {
            color: #173b30;
            font-size: clamp(28px, 3.2vw, 40px);
            letter-spacing: -.045em;
        }

        .section-heading p {
            max-width: 590px;
            margin-right: auto;
            margin-left: auto;
            color: #718078;
        }

        .feature-grid {
            gap: 18px;
            margin-top: 40px;
        }

        .feature-card {
            position: relative;
            min-height: 230px;
            overflow: hidden;
            padding: 25px;
            border-color: #e5ece5;
            border-radius: 19px;
            background: linear-gradient(145deg, #fff, #fcfdfb);
        }

        .feature-card::after {
            position: absolute;
            right: -28px;
            bottom: -34px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(229, 242, 228, .42);
            content: "";
            transition: transform .25s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: #c7dfcc;
            box-shadow: 0 15px 32px rgba(27, 64, 47, .08);
        }

        .feature-card:hover::after {
            transform: scale(1.3);
        }

        .feature-icon {
            position: relative;
            z-index: 1;
            width: 49px;
            height: 49px;
            border: 1px solid #e1eee2;
            border-radius: 15px;
            color: #247650;
            background: linear-gradient(145deg, #e8f6e9, #f4f8e9);
        }

        .feature-card h3,
        .feature-card p {
            position: relative;
            z-index: 1;
        }

        .feature-card h3 {
            margin-top: 17px;
            color: #203d31;
        }

        .feature-card p {
            color: #718078;
        }

        .about-section {
            padding: 80px 0;
            border: 0;
            background:
                radial-gradient(ellipse at 100% 0%, rgba(217, 236, 214, .55), transparent 40%),
                #f5f8f3;
        }

        .about {
            gap: clamp(35px, 7vw, 90px);
        }

        .about h2 {
            max-width: 430px;
            margin-top: 16px;
        }

        .about-copy p {
            color: #607269;
            line-height: 1.9;
        }

        .about-copy .btn {
            margin-top: 8px;
        }

        .cta-section {
            padding: 76px 0;
        }

        .cta {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            padding: 53px 28px;
            border: 1px solid rgba(255, 255, 255, .13);
            border-radius: 26px;
            background:
                radial-gradient(ellipse at 12% 0%, rgba(110, 190, 133, .26), transparent 40%),
                radial-gradient(ellipse at 100% 100%, rgba(54, 144, 99, .28), transparent 42%),
                linear-gradient(125deg, #10382e, #1a5b43);
            box-shadow: 0 20px 50px rgba(23, 75, 61, .13);
        }

        .cta::before {
            position: absolute;
            z-index: -1;
            top: -80px;
            right: -30px;
            width: 230px;
            height: 230px;
            border: 1px solid rgba(255, 255, 255, .13);
            border-radius: 50%;
            box-shadow: 0 0 0 24px rgba(255, 255, 255, .035), 0 0 0 48px rgba(255, 255, 255, .025);
            content: "";
        }

        .cta h2 {
            position: relative;
            font-size: clamp(27px, 3vw, 36px);
        }

        .cta p {
            position: relative;
            color: #d7e9dd;
        }

        .cta .btn-light {
            position: relative;
            color: #174b3d;
            background: #fff;
        }

        .cta .btn-light:hover {
            background: #edf7ef;
        }

        .footer {
            border-top-color: #e8eee8;
            background: #fbfcfa;
        }

        @media (max-width: 900px) {
            .hero {
                padding: 44px 0 52px;
            }

            .hero-grid {
                grid-template-columns: 1fr;
                gap: 34px;
            }

            .hero-copy {
                max-width: 700px;
            }

            .preview {
                justify-self: start;
                max-width: 640px;
                transform: rotate(.5deg);
            }

            .feature-card {
                min-height: 215px;
            }
        }

        @media (max-width: 640px) {
            .container {
                width: min(100% - 32px, 1120px);
            }

            .navbar {
                min-height: 0;
            }

            .nav-inner {
                min-height: 0;
                flex-wrap: wrap;
                gap: 10px;
                padding: 10px 0 0;
            }

            .brand {
                gap: 9px;
            }

            .brand-logo {
                width: 39px;
                height: 39px;
                border-radius: 12px;
            }

            .brand-name {
                font-size: 10px;
            }

            .brand-subtitle {
                font-size: 9px;
            }

            .nav-inner > .btn {
                min-height: 38px;
                padding: 8px 13px;
                font-size: 11px;
            }

            .nav-links {
                order: 3;
                display: flex;
                width: 100%;
                justify-content: space-between;
                gap: 8px;
                padding: 9px 0 11px;
                border-top: 1px solid #edf1ed;
            }

            .nav-links a {
                font-size: 11px;
            }

            .hero {
                padding: 38px 0 44px;
            }

            .hero-grid {
                gap: 30px;
                padding-top: 8px;
                padding-bottom: 8px;
            }

            .eyebrow {
                padding: 8px 11px;
                font-size: 8px;
            }

            .hero h1 {
                margin-top: 19px;
                font-size: clamp(36px, 10vw, 46px);
            }

            .hero-description {
                font-size: 14px;
            }

            .hero-actions .btn {
                width: 100%;
            }

            .trust-list {
                gap: 8px;
                font-size: 10px;
            }

            .trust-item {
                padding: 6px 9px;
            }

            .preview {
                border-radius: 18px;
                transform: none;
            }

            .preview-header {
                padding: 14px;
                border-radius: 18px 18px 0 0;
            }

            .preview-body {
                padding: 14px;
                border-radius: 0 0 18px 18px;
            }

            .preview-card {
                min-height: 112px;
                padding: 12px;
            }

            .preview-card small {
                font-size: 10px;
            }

            .preview-card strong {
                font-size: 11px;
            }

            .section,
            .about-section {
                padding: 58px 0;
            }

            .feature-grid {
                grid-template-columns: 1fr;
                gap: 13px;
                margin-top: 30px;
            }

            .feature-card {
                min-height: 0;
                padding: 21px;
            }

            .about {
                gap: 19px;
            }

            .cta-section {
                padding: 48px 0;
            }

            .cta {
                padding: 39px 20px;
                border-radius: 22px;
            }

            .footer-inner {
                align-items: flex-start;
                flex-direction: column;
                justify-content: center;
                gap: 8px;
                padding-top: 18px;
                padding-bottom: 18px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
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

                        Pengelolaan SDM
                        <span>yang terintegrasi.</span>

                    </h1>

                    <p class="hero-description">

                        Sistem informasi HRD PT Starfood International mendukung
                        pengelolaan data karyawan, absensi, jadwal kerja, dan penggajian
                        secara terstruktur dalam satu platform.

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

                        <span class="trust-item">Data tersimpan terpusat</span>
                        <span class="trust-item">Akses sesuai peran</span>
                        <span class="trust-item">Alur kerja lebih praktis</span>

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

                    <h2> Kebutuhan HRD dalam Satu Platform</h2>

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

                    <h2>Mendukung administrasi HRD yang terintegrasi dan terstruktur.</h2>

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

                    <h2>Akses terpadu untuk kebutuhan pengelolaan HRD.</h2>

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

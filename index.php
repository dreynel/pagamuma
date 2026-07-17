<?php
require_once 'config/db.php';

// Fetch up to 3 approved reviews
$stmtReviews = $pdo->query("SELECT r.*, u.first_name, u.last_name FROM system_reviews r JOIN users u ON r.user_id = u.id WHERE r.status = 'approved' ORDER BY r.created_at DESC LIMIT 3");
$recentReviews = $stmtReviews->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAG-AMUMA: Public Resources</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-public {
            background-image: linear-gradient(135deg, rgba(255, 205, 210, 0.8), rgba(255, 243, 224, 0.8)), url('assets/images/bg.png');
            background-size: cover;
            background-position: center;
            padding: 120px 0;
            border-bottom: 5px solid var(--primary-color);
        }
        .resource-card {
            transition: transform 0.3s;
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .resource-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(229, 115, 115, 0.2);
        }
        /* ===== Floating Chat Widget ===== */
        #chatWidgetBtn {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: #fff;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 24px rgba(124,58,237,0.45);
            cursor: pointer;
            z-index: 1060;
            border: none;
            transition: transform .25s, box-shadow .25s;
        }
        #chatWidgetBtn:hover  { transform: scale(1.1); box-shadow: 0 8px 28px rgba(124,58,237,0.55); }
        #chatWidgetBtn .badge-dot {
            position: absolute;
            top: 4px; right: 4px;
            width: 13px; height: 13px;
            background: #4ade80;
            border-radius: 50%;
            border: 2px solid #fff;
            animation: wiggle 2s infinite;
        }
        @keyframes wiggle {
            0%,100%{ transform:scale(1); } 50%{ transform:scale(1.25); }
        }

        /* Tooltip bubble */
        #chatWidgetTooltip {
            position: fixed;
            bottom: 38px;
            right: 104px;
            background: #fff;
            padding: 9px 18px;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            font-weight: 500;
            font-size: 0.88rem;
            color: #7c3aed;
            z-index: 1055;
            white-space: nowrap;
            animation: floatX 2.5s ease-in-out infinite;
            transition: opacity .3s;
        }
        #chatWidgetTooltip::after {
            content: '';
            position: absolute;
            right: -8px; top: 50%;
            transform: translateY(-50%);
            border: 8px solid transparent;
            border-left-color: #fff;
        }
        @keyframes floatX {
            0%,100%{ transform:translateX(0); }
            50%{ transform:translateX(-8px); }
        }

        /* Chat popup panel */
        #chatPopup {
            position: fixed;
            bottom: 105px;
            right: 28px;
            width: 370px;
            max-height: 560px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.18);
            z-index: 1055;
            display: none;
            flex-direction: column;
            overflow: hidden;
            animation: slideUp .3s ease;
        }
        #chatPopup.open { display: flex; }
        @keyframes slideUp {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* Popup header */
        #cpHeader {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }
        .cp-avatar {
            width: 40px; height: 40px;
            background: rgba(255,255,255,.2);
            border-radius: 50%;
            display: flex; align-items:center; justify-content:center;
            font-size: 1.1rem; color:#fff;
            flex-shrink:0;
        }
        .cp-status-dot {
            width:8px; height:8px;
            background:#4ade80; border-radius:50%;
            display:inline-block;
            box-shadow: 0 0 0 2px rgba(74,222,128,.35);
        }

        /* Popup chat window */
        #cpWindow {
            flex:1;
            overflow-y: auto;
            padding: 16px 14px;
            background: #f8f9fc;
            scroll-behavior: smooth;
        }
        #cpWindow::-webkit-scrollbar { width:4px; }
        #cpWindow::-webkit-scrollbar-thumb { background:#ddd; border-radius:4px; }

        /* Bubbles */
        .cp-row { display:flex; margin-bottom:12px; align-items:flex-end; gap:8px; }
        .cp-row.cp-user { flex-direction:row-reverse; }
        .cp-av {
            width:30px; height:30px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:.7rem; flex-shrink:0;
        }
        .cp-av.bot  { background:linear-gradient(135deg,#7c3aed,#a855f7); color:#fff; }
        .cp-av.user { background:linear-gradient(135deg,#ec4899,#f43f5e); color:#fff; }
        .cp-bubble {
            max-width:78%;
            padding: 9px 13px;
            border-radius: 16px;
            font-size: .85rem;
            line-height: 1.55;
            word-break: break-word;
            box-shadow: 0 2px 6px rgba(0,0,0,.06);
        }
        .cp-bubble.bot  { background:#fff; color:#1e1e2e; border-bottom-left-radius:3px; }
        .cp-bubble.user { background:linear-gradient(135deg,#7c3aed,#a855f7); color:#fff; border-bottom-right-radius:3px; }
        .cp-bubble ul   { padding-left:1.1rem; margin:.3rem 0 0; }
        .cp-bubble li   { margin-bottom:.2rem; }
        .cp-bubble strong { color:#5b21b6; }
        .cp-time { font-size:.62rem; opacity:.5; display:block; margin-top:.3rem; }
        .cp-user .cp-time { text-align:right; }

        /* Typing indicator */
        .cp-typing span {
            width:7px; height:7px;
            background:#a855f7; border-radius:50%;
            display:inline-block;
            margin-right:3px;
            animation: cpBounce 1.2s infinite ease-in-out;
        }
        .cp-typing span:nth-child(2){ animation-delay:.2s; }
        .cp-typing span:nth-child(3){ animation-delay:.4s; }
        @keyframes cpBounce {
            0%,80%,100%{ transform:translateY(0); opacity:.5; }
            40%{ transform:translateY(-5px); opacity:1; }
        }

        /* Quick chips */
        .cp-chips { display:flex; flex-wrap:wrap; gap:6px; margin-top:10px; }
        .cp-chip {
            background:#f0eaff; color:#7c3aed;
            border:1px solid #ddd6fe; border-radius:20px;
            padding:.28rem .75rem; font-size:.75rem; font-weight:500;
            cursor:pointer; white-space:nowrap; transition:all .2s;
        }
        .cp-chip:hover { background:#7c3aed; color:#fff; border-color:#7c3aed; }

        /* Popup footer */
        #cpFooter {
            padding: 10px 12px;
            background:#fff;
            border-top: 1px solid #e9ecef;
            display:flex;
            align-items:center;
            gap:8px;
            flex-shrink:0;
        }
        #cpInput {
            flex:1;
            border:1.5px solid #e5e7eb;
            border-radius:999px;
            padding:.55rem 1rem;
            font-size:.85rem;
            outline:none;
            font-family:inherit;
            transition:border-color .2s;
        }
        #cpInput:focus { border-color:#7c3aed; }
        #cpSend {
            width:38px; height:38px;
            border-radius:50%; border:none;
            background:linear-gradient(135deg,#7c3aed,#a855f7);
            color:#fff; display:flex; align-items:center; justify-content:center;
            cursor:pointer; flex-shrink:0;
            box-shadow:0 3px 10px rgba(124,58,237,.35);
            transition:transform .15s;
        }
        #cpSend:hover  { transform:scale(1.08); }
        #cpSend:active { transform:scale(.93); }
        #cpSend:disabled { opacity:.5; cursor:not-allowed; transform:none; }

        /* Login nudge */
        #cpLoginNudge {
            padding: 8px 14px;
            background: #fdf4ff;
            border-top: 1px solid #e9d5ff;
            font-size: .75rem;
            color: #6d28d9;
            text-align:center;
            flex-shrink:0;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <div class="logo-circle-mobile me-3 d-flex align-items-center justify-content-center" style="width:45px;height:45px;background-color:var(--primary-light);border-radius:50%;">
                    <i class="fa-solid fa-child-reaching text-primary fs-5"></i>
                </div>
                <span class="fw-bold text-primary fs-4">PAG-AMUMA</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center mt-3 mt-lg-0">
                    <li class="nav-item me-lg-4 mb-2 mb-lg-0">
                        <a class="nav-link fw-medium text-dark px-0 hover-primary" href="#" data-i18n="nav_home">Home</a>
                    </li>
                    <li class="nav-item me-lg-4 mb-2 mb-lg-0">
                        <a class="nav-link fw-medium text-dark px-0 hover-primary" href="#resources" data-i18n="nav_resources">Resources</a>
                    </li>
                    <li class="nav-item me-lg-4 mb-2 mb-lg-0">
                        <a class="nav-link fw-medium text-dark px-0 hover-primary" href="#about" data-i18n="nav_about">About Us</a>
                    </li>
                    <li class="nav-item me-lg-4 mb-3 mb-lg-0">
                        <select class="form-select border-0 bg-light text-secondary fw-medium shadow-sm py-2 px-3 rounded-pill" id="languageSelector">
                            <option value="en">English</option>
                            <option value="hil">Hiligaynon</option>
                            <option value="tl">Tagalog</option>
                        </select>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary px-4 py-2 rounded-pill fw-medium shadow-sm w-100" href="login.php" data-i18n="nav_login_btn">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-public text-center position-relative">
        <div class="container position-relative z-index-1">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="logo-circle mx-auto mb-4 border-white border-4" style="width:120px;height:120px;">
                        <i class="fa-solid fa-child-reaching fs-1 text-white"></i>
                    </div>
                    <h1 class="display-3 fw-bold text-dark mb-4" data-i18n="public_hero_title">Welcome to PAG-AMUMA</h1>
                    <p class="lead text-dark fw-medium mb-5 px-3 fs-4" data-i18n="hero_desc">A Web-based Health Support and Learning Platform for Early Pregnancy Women</p>
                    <a href="#resources" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold text-primary shadow-lg" data-i18n="explore_resources">Explore Resources</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Resources Section -->
    <section id="resources" class="py-5 mt-4">
        <div class="container py-4">
            <div class="text-center mb-5 pb-3">
                <h2 class="display-6 fw-bold text-dark mb-3" data-i18n="resources_title">Educational Materials</h2>
                <p class="text-muted fs-5" data-i18n="resources_subtitle">Access free, open resources to support your pregnancy journey.</p>
            </div>
            
            <div class="row g-4">
                <!-- Resource 1 -->
                <div class="col-md-4">
                    <div class="card resource-card h-100 p-4 text-center bg-white">
                        <div class="mb-4 mt-2">
                            <i class="fa-solid fa-book-medical fs-1 text-primary p-4 bg-primary-light rounded-circle"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-3" data-i18n="menu_health">Health Modules</h4>
                        <p class="text-muted flex-grow-1 px-2 line-height-lg" data-i18n="health_desc">Learn about nutrition, prenatal vitamins, and safe exercises during your early pregnancy.</p>
                        <a href="guest_modules.php?category=health" class="btn btn-outline-primary rounded-pill mt-3 fw-medium py-2 px-4 shadow-sm">Read More</a>
                    </div>
                </div>
                <!-- Resource 2 -->
                <div class="col-md-4">
                    <div class="card resource-card h-100 p-4 text-center bg-white">
                        <div class="mb-4 mt-2">
                            <i class="fa-solid fa-baby-carriage fs-1 text-primary p-4 bg-primary-light rounded-circle"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-3" data-i18n="menu_parenting">Parenting Basics</h4>
                        <p class="text-muted flex-grow-1 px-2" data-i18n="parenting_desc">Prepare for your baby's arrival with guides on baby care, sleep schedules, and essential gear.</p>
                        <a href="guest_modules.php?category=parenting" class="btn btn-outline-primary rounded-pill mt-3 fw-medium py-2 px-4 shadow-sm">Read More</a>
                    </div>
                </div>
                <!-- Resource 3 -->
                <div class="col-md-4">
                    <div class="card resource-card h-100 p-4 text-center bg-white">
                        <div class="mb-4 mt-2">
                            <i class="fa-solid fa-heart-pulse fs-1 text-primary p-4 bg-primary-light rounded-circle"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-3" data-i18n="menu_emotional">Emotional Support</h4>
                        <p class="text-muted flex-grow-1 px-2" data-i18n="emotional_desc">Navigate the emotional changes of pregnancy with mindfulness exercises and community support.</p>
                        <a href="guest_modules.php?category=emotional" class="btn btn-outline-primary rounded-pill mt-3 fw-medium py-2 px-4 shadow-sm">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Showcase Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
        <div class="container py-5">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="pe-lg-5">
                        <span class="badge bg-primary-light text-primary rounded-pill px-3 py-2 mb-3 fw-bold" data-i18n="feat_badge">Why Choose PAG-AMUMA</span>
                        <h2 class="display-5 fw-bold text-dark mb-4" data-i18n="feat_title">Your Complete Pregnancy Companion</h2>
                        <p class="lead text-muted mb-4" data-i18n="feat_desc">We've designed a specialized platform that combines medical guidance, emotional support, and smart tracking—all in your local language.</p>
                        
                        <div class="d-flex align-items-start mb-4 bg-white p-3 rounded-4 shadow-sm hover-shadow transition-all">
                            <i class="fa-solid fa-person-pregnant fs-3 text-primary bg-primary-light p-3 rounded-circle me-4"></i>
                            <div>
                                <h5 class="fw-bold text-dark" data-i18n="feat1_title">Interactive Pregnancy Tracker</h5>
                                <p class="text-muted mb-0 small" data-i18n="feat1_desc">Watch your baby grow week by week with beautiful visual insights and fruit size comparisons on your premium dashboard.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-4 bg-white p-3 rounded-4 shadow-sm hover-shadow transition-all">
                            <i class="fa-solid fa-heart-pulse fs-3 text-danger bg-danger-subtle p-3 rounded-circle me-4"></i>
                            <div>
                                <h5 class="fw-bold text-dark" data-i18n="feat2_title">Smart Vitals Logging</h5>
                                <p class="text-muted mb-0 small" data-i18n="feat2_desc">Keep a secure digital history of your weight, blood pressure, and daily symptoms automatically graphed for your next doctor's visit.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start bg-white p-3 rounded-4 shadow-sm hover-shadow transition-all">
                            <i class="fa-solid fa-language fs-3 text-success bg-success-subtle p-3 rounded-circle me-4"></i>
                            <div>
                                <h5 class="fw-bold text-dark" data-i18n="feat3_title">Localized AI Chatbot</h5>
                                <p class="text-muted mb-0 small" data-i18n="feat3_desc">Our generative AI assistant is specifically trained to understand and reply naturally in English, Hiligaynon, and Tagalog 24/7.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 position-relative">
                    <!-- Decorative background element -->
                    <div class="position-absolute top-50 start-50 translate-middle bg-primary opacity-25 rounded-circle" style="width: 400px; height: 400px; filter: blur(60px); z-index: 0;"></div>
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden position-relative z-index-1 mx-auto" style="max-width: 400px; background: linear-gradient(135deg, #fff0f5, #fff);">
                        <div class="p-5 text-center">
                            <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-lg mb-4 mx-auto" style="width:120px;height:120px;">
                                <i class="fa-solid fa-lemon fs-1"></i>
                            </div>
                            <h3 class="fw-bold mb-2 text-dark">Week 14</h3>
                            <p class="text-secondary mb-4 fw-medium">Your baby is the size of a Lemon!</p>
                            <div class="progress bg-light shadow-inner mb-3" style="height: 12px; border-radius: 10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-gradient-primary" style="width: 35%; background: linear-gradient(90deg, #7c3aed, #ec4899);"></div>
                            </div>
                            <small class="text-muted fw-bold">35% Complete</small>
                            
                            <hr class="my-4 text-muted opacity-25">
                            
                            <p class="text-dark small fst-italic mb-0">"The golden trimester begins! Notice your energy returning."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Resources Section -->
    <section id="videos" class="py-5 bg-white">
        <div class="container py-4">
            <div class="text-center mb-5 pb-3">
                <h2 class="display-6 fw-bold text-dark mb-3" data-i18n="videos_title">Watch & Learn</h2>
                <p class="text-muted fs-5" data-i18n="videos_subtitle">Video guides and expert advice for your pregnancy journey.</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <!-- Video 1 -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card resource-card h-100 border-0 shadow-sm overflow-hidden bg-light">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/Qn7ouVsH0No" title="Pwede ba Magbreastfeed Kahit May Sakit si Mommy?" allowfullscreen></iframe>
                        </div>
                        <div class="card-body p-4 text-start">
                            <span class="badge bg-danger mb-2">Breastfeeding</span>
                            <h5 class="fw-semibold text-dark mb-2">Breastfeeding w/ Illness</h5>
                            <p class="card-text text-muted mb-0 small">Doc Leila (OB-GYN) talks about breastfeeding safely while sick.</p>
                        </div>
                    </div>
                </div>
                <!-- Video 2 -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card resource-card h-100 border-0 shadow-sm overflow-hidden bg-light">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/GsMVhp3Qw58" title="Puwede at Bawal Kainin para sa Buntis" allowfullscreen></iframe>
                        </div>
                        <div class="card-body p-4 text-start">
                            <span class="badge bg-primary mb-2">Nutrition</span>
                            <h5 class="fw-semibold text-dark mb-2">Puwede at Bawal Kainin</h5>
                            <p class="card-text text-muted mb-0 small">Doc Willie Ong's advice on what foods are safe and what to avoid.</p>
                        </div>
                    </div>
                </div>
                <!-- Video 3 -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card resource-card h-100 border-0 shadow-sm overflow-hidden bg-light">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/PoflO66C6iY" title="16 Prutas at Pagkain na Bawal sa Buntis" allowfullscreen></iframe>
                        </div>
                        <div class="card-body p-4 text-start">
                            <span class="badge bg-success mb-2">Nutrition</span>
                            <h5 class="fw-semibold text-dark mb-2">16 Prutas na Bawal</h5>
                            <p class="card-text text-muted mb-0 small">Learn about specific fruits and foods that could be harmful to the baby.</p>
                        </div>
                    </div>
                </div>
                <!-- Video 4 -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card resource-card h-100 border-0 shadow-sm overflow-hidden bg-light">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/6iK9yUPYHAY" title="Sobrang Pagduduwal at Pagsusuka - Morning Sickness" allowfullscreen></iframe>
                        </div>
                        <div class="card-body p-4 text-start">
                            <span class="badge bg-info mb-2 text-dark">Morning Sickness</span>
                            <h5 class="fw-semibold text-dark mb-2">Pagduduwal at Pagsusuka</h5>
                            <p class="card-text text-muted mb-0 small">Ate Nurse shares tips on how to handle severe morning sickness.</p>
                        </div>
                    </div>
                </div>
                <!-- Video 5 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card resource-card h-100 border-0 shadow-sm overflow-hidden bg-light">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/SeKIZy-1tDA" title="5 Pregnancy Tips You Should Not Ignore" allowfullscreen></iframe>
                        </div>
                        <div class="card-body p-4 text-start">
                            <span class="badge bg-warning mb-2 text-dark">General Tips</span>
                            <h5 class="fw-semibold text-dark mb-2">5 Pregnancy Tips</h5>
                            <p class="card-text text-muted mb-0 small">Important pregnancy practices you shouldn't ignore for a healthy birth.</p>
                        </div>
                    </div>
                </div>
                <!-- Video 6 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card resource-card h-100 border-0 shadow-sm overflow-hidden bg-light">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/aOxu0JTiwDY" title="Senyales na Malapit ng Manganak" allowfullscreen></iframe>
                        </div>
                        <div class="card-body p-4 text-start">
                            <span class="badge bg-danger mb-2">Preparation</span>
                            <h5 class="fw-semibold text-dark mb-2">Senyales na Malapit ng Manganak</h5>
                            <p class="card-text text-muted mb-0 small">Ate Nurse points out the signs to look for when labor is approaching.</p>
                        </div>
                    </div>
                </div>
                <!-- Video 7 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card resource-card h-100 border-0 shadow-sm overflow-hidden bg-light">
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/yZAWU8rCEQs" title="Senyales na Hindi Safe ang Baby sa Tiyan" allowfullscreen></iframe>
                        </div>
                        <div class="card-body p-4 text-start">
                            <span class="badge bg-secondary mb-2">Pre-Caution</span>
                            <h5 class="fw-semibold text-dark mb-2">Warning Signs</h5>
                            <p class="card-text text-muted mb-0 small">Nurse Yeza outlines the symptoms that indicate your baby might not be safe.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="login.php" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-medium shadow-sm">View All Videos <i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </section>

    <!-- Statistics Counter -->
    <section class="py-5" style="background: linear-gradient(135deg, #7c3aed, #ec4899);">
        <div class="container py-4">
            <div class="row text-center text-white g-4">
                <div class="col-6 col-md-3">
                    <i class="fa-solid fa-users fs-1 mb-3 opacity-75"></i>
                    <h2 class="display-4 fw-bold mb-0">1,200+</h2>
                    <p class="lead mb-0 text-white-50" data-i18n="stat_1">Local Mothers</p>
                </div>
                <div class="col-6 col-md-3">
                    <i class="fa-regular fa-comments fs-1 mb-3 opacity-75"></i>
                    <h2 class="display-4 fw-bold mb-0">15k+</h2>
                    <p class="lead mb-0 text-white-50" data-i18n="stat_2">Questions Answered</p>
                </div>
                <div class="col-6 col-md-3">
                    <i class="fa-solid fa-book-open fs-1 mb-3 opacity-75"></i>
                    <h2 class="display-4 fw-bold mb-0">40+</h2>
                    <p class="lead mb-0 text-white-50" data-i18n="stat_3">Curated Chapters</p>
                </div>
                <div class="col-6 col-md-3">
                    <i class="fa-solid fa-earth-asia fs-1 mb-3 opacity-75"></i>
                    <h2 class="display-4 fw-bold mb-0">3</h2>
                    <p class="lead mb-0 text-white-50" data-i18n="stat_4">Languages Supported</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5 pb-2">
                <h2 class="display-6 fw-bold text-dark mb-3" data-i18n="test_title">Loved by Mothers</h2>
                <p class="text-muted fs-5" data-i18n="test_subtitle">Read what other expecting mothers in our community have to say.</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <?php if (empty($recentReviews)): ?>
                    <div class="col-12 text-center text-muted py-4">
                        <i class="fa-regular fa-comments fs-1 mb-3 opacity-50"></i>
                        <h5>No public reviews yet</h5>
                        <p>Join our community and be the first to share your experience!</p>
                    </div>
                <?php else: ?>
                    <?php foreach($recentReviews as $rev): ?>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white hover-shadow transition-all">
                            <ul class="list-inline text-warning mb-3">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <?php if($i <= $rev['rating']): ?>
                                        <li class="list-inline-item m-0"><i class="fa-solid fa-star"></i></li>
                                    <?php else: ?>
                                        <li class="list-inline-item m-0"><i class="fa-solid fa-star text-muted" style="opacity:0.3"></i></li>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </ul>
                            <p class="text-dark fst-italic mb-4">"<?= htmlspecialchars($rev['comment']) ?>"</p>
                            <div class="d-flex align-items-center mt-auto border-top pt-3">
                                <?php
                                    $bg_colors = ['e57373', 'a855f7', '4ade80', '60a5fa', 'fbbf24'];
                                    $bg = $bg_colors[array_rand($bg_colors)];
                                ?>
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($rev['first_name'].' '.$rev['last_name']) ?>&background=<?= $bg ?>&color=fff&rounded=true" alt="User" width="45" height="45" class="shadow-sm rounded-circle">
                                <div class="ms-3">
                                    <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($rev['first_name'] . ' ' . mb_substr($rev['last_name'], 0, 1) . '.') ?></h6>
                                    <small class="text-muted"><?= date('M d, Y', strtotime($rev['created_at'])) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-5" style="background-color: var(--primary-light);">
        <div class="container py-5 text-center">
            <h2 class="display-6 fw-bold text-dark mb-4" data-i18n="nav_about">About Us</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <p class="lead text-dark fw-medium lh-lg" data-i18n="about_desc">
                        PAG-AMUMA is an ISO 25010 evaluated web-based health support and learning platform dedicated to assisting women during their early pregnancy journey, specifically tailored for Hiligaynon and Tagalog speakers.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-4 border-top">
        <div class="container text-center">
            <p class="text-muted mb-0 fw-medium">&copy; 2026 PAG-AMUMA. "Naga-ulikid sa imo ikaayong lawas kag sa imo lapsag."</p>
        </div>
    </footer>

    <!-- ===== Floating Chat Widget ===== -->

    <!-- Tooltip -->
    <div id="chatWidgetTooltip" data-i18n="chatbot_tooltip">Need help? Ask PAG-AMUMA! 👋</div>

    <!-- Trigger button -->
    <button id="chatWidgetBtn" title="Open PAG-AMUMA Chatbot" aria-label="Open chatbot">
        <i class="fa-solid fa-message"></i>
        <span class="badge-dot"></span>
    </button>

    <!-- Chat Popup Panel -->
    <div id="chatPopup" role="dialog" aria-label="PAG-AMUMA Chat">

        <!-- Header -->
        <div id="cpHeader">
            <div class="cp-avatar">🤱</div>
            <div class="flex-grow-1">
                <div class="fw-bold text-white" style="font-size:.95rem;">PAG-AMUMA Assistant</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="cp-status-dot"></span>
                    <span class="text-white-50" style="font-size:.72rem;">Online &amp; Ready</span>
                </div>
            </div>
            <button id="cpClose" style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;border-radius:8px;padding:3px 10px;font-size:.78rem;cursor:pointer;" title="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Chat window -->
        <div id="cpWindow">
            <!-- Welcome message injected by JS -->
        </div>

        <!-- Login nudge -->
        <div id="cpLoginNudge">
            <i class="fa-solid fa-lock me-1"></i>
            <a href="login.php" class="fw-semibold" style="color:#7c3aed;">Sign in</a> or
            <a href="register.php" class="fw-semibold" style="color:#7c3aed;">Register</a> to save your chat history &amp; access personalized features.
        </div>

        <!-- Input footer -->
        <div id="cpFooter">
            <input id="cpInput" type="text" placeholder="Ask your pregnancy question…" maxlength="800" autocomplete="off">
            <button id="cpSend" title="Send"><i class="fa-solid fa-paper-plane" style="font-size:.8rem;"></i></button>
        </div>

    </div><!-- /#chatPopup -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/translations.js"></script>

    <script>
    (function () {
        const btn      = document.getElementById('chatWidgetBtn');
        const popup    = document.getElementById('chatPopup');
        const closeBtn = document.getElementById('cpClose');
        const tooltip  = document.getElementById('chatWidgetTooltip');
        const cpWindow = document.getElementById('cpWindow');
        const cpInput  = document.getElementById('cpInput');
        const cpSend   = document.getElementById('cpSend');

        let isOpen     = false;
        let isSending  = false;
        let chatHistory = []; // [{ role: 'user'|'model', text: '...' }]
        let welcomed   = false;

        function toggleChat() {
            isOpen = !isOpen;
            popup.classList.toggle('open', isOpen);
            tooltip.style.opacity = isOpen ? '0' : '1';
            tooltip.style.pointerEvents = isOpen ? 'none' : 'auto';
            // Switch icon
            btn.querySelector('i').className = isOpen
                ? 'fa-solid fa-xmark'
                : 'fa-solid fa-message';
            if (isOpen && !welcomed) {
                welcomed = true;
                injectWelcome();
                cpInput.focus();
            }
        }

        btn.addEventListener('click', toggleChat);
        closeBtn.addEventListener('click', toggleChat);

        // Send on Enter
        cpInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); handleSend(); }
        });
        cpSend.addEventListener('click', handleSend);

        function injectWelcome() {
            const bubble = createBotBubble(
                '<p class="mb-1 fw-semibold" style="color:#5b21b6;">Kamusta! I\'m PAG-AMUMA 🤱</p>' +
                '<p class="mb-2" style="font-size:.82rem;color:#374151;">Your AI pregnancy guide — ask me anything about prenatal care, nutrition, or how you\'re feeling. I speak English, Tagalog, and Hiligaynon!</p>' +
                '<div class="cp-chips">' +
                    '<span class="cp-chip" onclick="sendQuickCp(this)">🥦 Foods to eat</span>' +
                    '<span class="cp-chip" onclick="sendQuickCp(this)">😴 First trimester tips</span>' +
                    '<span class="cp-chip" onclick="sendQuickCp(this)">💊 Prenatal vitamins</span>' +
                    '<span class="cp-chip" onclick="sendQuickCp(this)">🤰 Signs of labor</span>' +
                    '<span class="cp-chip" onclick="sendQuickCp(this)">🧘 Stress in pregnancy</span>' +
                '</div>'
            );
            cpWindow.appendChild(bubble);
            scrollCp();
        }

        window.sendQuickCp = function (el) {
            const text = el.textContent.replace(/^[\u{1F000}-\u{1FFFF}\u{2600}-\u{27BF} ]+/u, '').trim();
            cpInput.value = text;
            handleSend();
        };

        function handleSend() {
            if (isSending) return;
            const msg = cpInput.value.trim();
            if (!msg) return;

            appendUserBubble(msg);
            cpInput.value = '';
            chatHistory.push({ role: 'user', text: msg });

            const typing = appendTyping();
            isSending = true;
            cpSend.disabled = true;

            fetch('/pagamuma/api/public_chatbot_action.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({
                    message: msg,
                    history: chatHistory.slice(0, -1) // send prior context, not the current message
                })
            })
            .then(r => r.json())
            .then(data => {
                removeTyping(typing);
                const reply = data.error ? '⚠️ ' + data.error : data.reply;
                appendBotBubble(reply);
                if (!data.error) chatHistory.push({ role: 'model', text: reply });
            })
            .catch(() => {
                removeTyping(typing);
                appendBotBubble('⚠️ Could not connect. Please check your connection and try again.');
            })
            .finally(() => {
                isSending = false;
                cpSend.disabled = false;
                cpInput.focus();
            });
        }

        /* ---- DOM helpers ---- */
        function appendUserBubble(text) {
            const row = document.createElement('div');
            row.className = 'cp-row cp-user';
            row.innerHTML =
                '<div class="cp-av user"><i class="fa-solid fa-user"></i></div>' +
                '<div class="cp-bubble user">' + escHtml(text) +
                    '<small class="cp-time">' + now() + '</small>' +
                '</div>';
            cpWindow.appendChild(row);
            scrollCp();
        }

        function appendBotBubble(text) {
            const row = createBotBubble(formatBot(text) + '<small class="cp-time">' + now() + '</small>');
            cpWindow.appendChild(row);
            scrollCp();
        }

        function createBotBubble(innerHtml) {
            const row = document.createElement('div');
            row.className = 'cp-row';
            row.innerHTML =
                '<div class="cp-av bot"><i class="fa-solid fa-robot"></i></div>' +
                '<div class="cp-bubble bot">' + innerHtml + '</div>';
            return row;
        }

        function appendTyping() {
            const row = document.createElement('div');
            row.className = 'cp-row'; row.id = 'cpTyping';
            row.innerHTML =
                '<div class="cp-av bot"><i class="fa-solid fa-robot"></i></div>' +
                '<div class="cp-bubble bot"><div class="cp-typing"><span></span><span></span><span></span></div></div>';
            cpWindow.appendChild(row);
            scrollCp();
            return row;
        }
        function removeTyping(el) { if (el && el.parentNode) el.remove(); }
        function scrollCp() { cpWindow.scrollTop = cpWindow.scrollHeight; }
        function now() { return new Date().toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'}); }
        function escHtml(s) {
            return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
        }
        function formatBot(text) {
            text = text.replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>');
            text = text.replace(/\*(.*?)\*/g,'<em>$1</em>');
            const lines = text.split('\n');
            let html = '', inList = false;
            for (let line of lines) {
                const b = line.match(/^[\-\*•]\s+(.+)/);
                if (b) {
                    if (!inList) { html += '<ul>'; inList = true; }
                    html += '<li>' + b[1] + '</li>';
                } else {
                    if (inList) { html += '</ul>'; inList = false; }
                    if (line.trim()) html += '<p style="margin:0 0 .35rem;">' + line + '</p>';
                }
            }
            if (inList) html += '</ul>';
            return html;
        }
    })();
    </script>
</body>
</html>

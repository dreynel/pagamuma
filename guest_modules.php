<?php
// c:\xampp\htdocs\pagamuma\guest_modules.php
require_once 'config/db.php';
$hiligaynonVideos = require 'config/hiligaynon_videos.php';

$category = $_GET['category'] ?? 'health';
$stmt = $pdo->prepare("SELECT * FROM educational_modules WHERE category = ?");
$stmt->execute([$category]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$titles = [
    'health' => ['en' => 'Health Modules', 'hil' => 'Mga Module sa Ikaayong Lawas', 'tl' => 'Mga Modyul sa Kalusugan'],
    'parenting' => ['en' => 'Parenting Basics', 'hil' => 'Mga Basiko sa Pagginikanan', 'tl' => 'Mga Pangunahing Kaalaman sa Pagiging Magulang'],
    'emotional' => ['en' => 'Emotional Support', 'hil' => 'Emosyonal nga Suporta', 'tl' => 'Emosyonal na Suporta']
];

$title_key = "menu_" . $category;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAG-AMUMA: Learning Modules</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        <a class="nav-link fw-medium text-dark px-0 hover-primary" href="index.php" data-i18n="nav_home">Home</a>
                    </li>
                    <li class="nav-item me-lg-4 mb-2 mb-lg-0">
                        <a class="nav-link fw-medium text-dark px-0 hover-primary" href="index.php#resources" data-i18n="nav_resources">Resources</a>
                    </li>
                    <li class="nav-item me-lg-4 mb-2 mb-lg-0">
                        <a class="nav-link fw-medium text-dark px-0 hover-primary" href="index.php#about" data-i18n="nav_about">About Us</a>
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

    <!-- Page Content -->
    <div class="container py-5 min-vh-100">
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <!-- Translation Title handled by i18n -->
                <h2 class="fw-bold text-dark mb-1" data-i18n="<?= htmlspecialchars($title_key) ?>"><?= htmlspecialchars($titles[$category]['en'] ?? 'Modules') ?></h2>
                <p class="text-muted" data-i18n="<?= htmlspecialchars($category . '_desc') ?>">Explore educational resources for your pregnancy journey.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="index.php#resources" class="btn btn-outline-secondary rounded-pill px-4 py-2">&larr; Back to Resources</a>
            </div>
        </div>

        <div class="row g-4">
            <?php if(empty($articles)): ?>
                <div class="col-12 text-center text-muted py-5">
                    <i class="fa-solid fa-folder-open fs-1 mb-3 opacity-50"></i>
                    <h5 data-i18n="no_health_modules">No modules available yet.</h5>
                </div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                <div class="col-md-4 mb-4">
                    <a href="guest_read.php?id=<?= $article['id'] ?>" class="text-decoration-none h-100 d-block">
                        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-center text-md-start" style="transition: transform 0.2s;">
                            <div class="bg-primary-light p-4 d-flex justify-content-center align-items-center">
                                <i class="fa-solid fa-book-open fs-1 text-primary border border-2 border-primary rounded-circle p-3 bg-white shadow-sm"></i>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <span class="badge bg-primary-light text-primary mb-3 align-self-start" data-i18n="health_library">Library</span>
                                <h5 class="fw-bold text-dark mb-2">
                                    <span class="lang-en"><?= htmlspecialchars($article['title_en']) ?></span>
                                    <span class="lang-hil d-none"><?= htmlspecialchars($article['title_hil']) ?></span>
                                    <span class="lang-tl d-none"><?= htmlspecialchars($article['title_tl']) ?></span>
                                </h5>
                                <div class="text-muted small flex-grow-1" style="max-height: 80px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                    <span class="lang-en"><?= strip_tags($article['content_en']) ?></span>
                                    <span class="lang-hil d-none"><?= strip_tags($article['content_hil']) ?></span>
                                    <span class="lang-tl d-none"><?= strip_tags($article['content_tl']) ?></span>
                                </div>
                                <div class="mt-4 pt-3 border-top text-primary fw-medium small d-flex justify-content-between align-items-center">
                                    <span data-i18n="read_chapter">Read Chapter</span>
                                    <i class="fa-solid fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Featured Hiligaynon Video Modules -->
        <div class="mt-5 pt-4 border-top">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 pb-2 border-bottom">
                <div>
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-danger-subtle text-danger rounded-pill small fw-semibold mb-2">
                        <i class="fa-solid fa-volume-high"></i> <span data-i18n="video_hil">Hiligaynon Video</span>
                    </div>
                    <h3 class="fw-bold text-dark mb-1" data-i18n="hil_videos_title">Hiligaynon Maternal Health Videos</h3>
                    <p class="text-muted mb-0" data-i18n="hil_videos_subtitle">Exclusive localized video guides in Hiligaynon for expecting mothers.</p>
                </div>
                <span class="badge bg-danger rounded-pill px-3 py-2 fw-medium shadow-sm"><i class="fa-solid fa-video me-1"></i> 8 Hiligaynon Modules</span>
            </div>

            <div class="row g-4">
                <?php foreach ($hiligaynonVideos as $v): ?>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card resource-card h-100 border-0 shadow-sm overflow-hidden bg-white">
                            <div class="ratio ratio-16x9 bg-dark">
                                <video controls preload="metadata" playsinline class="w-100 h-100" style="object-fit: contain;">
                                    <source src="video2/<?= htmlspecialchars($v['file']) ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <div class="card-body p-3 text-start d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-danger-subtle text-danger fw-semibold" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-microphone-lines me-1"></i> Hiligaynon
                                    </span>
                                    <span class="badge bg-light text-secondary border fw-medium" style="font-size: 0.72rem;">
                                        <?= htmlspecialchars($v['badge']) ?>
                                    </span>
                                </div>
                                <h6 class="fw-semibold text-dark mb-1"><?= htmlspecialchars($v['title']) ?></h6>
                                <p class="card-text text-muted mb-0 small flex-grow-1"><?= htmlspecialchars($v['desc']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white py-4 border-top mt-auto">
        <div class="container text-center">
            <p class="text-muted mb-0 fw-medium">&copy; 2026 PAG-AMUMA. "Naga-ulikid sa imo ikaayong lawas kag sa imo lapsag."</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/translations.js"></script>
</body>
</html>

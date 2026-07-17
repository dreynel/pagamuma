<?php
// c:\xampp\htdocs\pagamuma\guest_read.php
require_once 'config/db.php';

$article_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM educational_modules WHERE id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if (!$article) {
    die("Article not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAG-AMUMA: Read Article</title>
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

    <div class="container py-5 min-vh-100">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Back Button -->
                <a href="guest_modules.php?category=<?= htmlspecialchars($article['category']) ?>" class="btn btn-light rounded-pill mb-4 shadow-sm fw-medium border-0 px-4 py-2 d-inline-flex align-items-center"><i class="fa-solid fa-arrow-left me-2"></i> <span data-i18n="back_to">Back to</span> <?= ucfirst($article['category']) ?></a>
                
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 bg-white">
                    <div class="bg-primary-light p-4 text-center">
                        <i class="fa-solid fa-book-open fs-1 text-primary border border-2 border-primary rounded-circle p-4 bg-white shadow-sm my-3"></i>
                        <h2 class="fw-bold text-dark mt-3 mb-2 px-3">
                            <span class="lang-en"><?= htmlspecialchars($article['title_en']) ?></span>
                            <span class="lang-hil d-none"><?= htmlspecialchars($article['title_hil']) ?></span>
                            <span class="lang-tl d-none"><?= htmlspecialchars($article['title_tl']) ?></span>
                        </h2>
                        <span class="badge bg-primary text-white px-3 py-2 rounded-pill shadow-sm mt-2"><?= ucfirst($article['category']) ?></span>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <div class="reading-content lh-lg" style="font-size: 1.1rem; color: #4B5563;">
                            <div class="lang-en"><?= $article['content_en'] ?></div>
                            <div class="lang-hil d-none"><?= $article['content_hil'] ?></div>
                            <div class="lang-tl d-none"><?= $article['content_tl'] ?></div>
                        </div>
                    </div>
                </div>

                <!-- Call to action -->
                <div class="card border-0 shadow-lg rounded-4 p-5 text-center mt-4" style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-lg" style="width: 70px; height: 70px;">
                        <i class="fa-solid fa-user-plus fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-2">Want to track your journey?</h3>
                    <p class="text-white-50 mb-4">Register an account to save your reading progress, track your health, and access our smart AI Chatbot.</p>
                    <a href="register.php" class="btn btn-light btn-lg rounded-pill fw-bold text-primary shadow-sm px-5 py-3">Register Now</a>
                </div>
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

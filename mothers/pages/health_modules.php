<!-- c:\xampp\htdocs\pagamuma\mothers\pages\health_modules.php -->
<?php
require_once __DIR__ . '/../../config/db.php';
$stmt = $pdo->prepare("SELECT * FROM educational_modules WHERE category = 'health'");
$stmt->execute();
$articles = $stmt->fetchAll();
?>
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-dark mb-1" data-i18n="menu_health">Health Modules</h2>
        <p class="text-muted" data-i18n="health_subtitle">Explore essential health topics tailored for a safe and healthy pregnancy.</p>
    </div>
</div>

<div class="row g-4">
    <?php if(empty($articles)): ?>
        <div class="col-12 text-center text-muted py-5">
            <i class="fa-solid fa-folder-open fs-1 mb-3 opacity-50"></i>
            <h5 data-i18n="no_health_modules">No health modules available yet.</h5>
        </div>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
        <div class="col-md-4 mb-4">
            <a href="index.php?page=read_article&id=<?= $article['id'] ?>" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-center text-md-start" style="transition: transform 0.2s;">
                    <div class="bg-primary-light p-4 d-flex justify-content-center align-items-center">
                        <i class="fa-solid fa-book-open fs-1 text-primary border border-2 border-primary rounded-circle p-3 bg-white shadow-sm"></i>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <span class="badge bg-primary-light text-primary mb-3 align-self-start" data-i18n="health_library">Health Library</span>
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

<div class="row mt-5 mb-4 border-top pt-4">
    <div class="col-12 pb-2">
        <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-play-circle text-primary me-2"></i> <span data-i18n="videos_title">Watch & Learn</span></h4>
        <p class="text-muted small" data-i18n="videos_subtitle">Visual guides and expert advice for your pregnancy journey.</p>
    </div>
</div>

<div class="row g-4 mb-4 justify-content-center">
    <!-- Video 1 -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/Qn7ouVsH0No" title="Pwede ba Magbreastfeed Kahit May Sakit si Mommy?" allowfullscreen></iframe>
            </div>
            <div class="card-body p-3">
                <span class="badge bg-danger-subtle text-danger mb-2">Breastfeeding</span>
                <h6 class="fw-bold text-dark mb-1">Breastfeeding w/ Illness</h6>
                <p class="text-muted small mb-0">Doc Leila (OB-GYN) talks about breastfeeding safely while sick.</p>
            </div>
        </div>
    </div>
    <!-- Video 2 -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/GsMVhp3Qw58" title="Puwede at Bawal Kainin para sa Buntis" allowfullscreen></iframe>
            </div>
            <div class="card-body p-3">
                <span class="badge bg-primary-subtle text-primary mb-2">Nutrition</span>
                <h6 class="fw-bold text-dark mb-1">Puwede at Bawal Kainin</h6>
                <p class="text-muted small mb-0">Doc Willie Ong's advice on what foods are safe and what to avoid.</p>
            </div>
        </div>
    </div>
    <!-- Video 3 -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/PoflO66C6iY" title="16 Prutas at Pagkain na Bawal sa Buntis" allowfullscreen></iframe>
            </div>
            <div class="card-body p-3">
                <span class="badge bg-success-subtle text-success mb-2">Nutrition</span>
                <h6 class="fw-bold text-dark mb-1">16 Prutas na Bawal</h6>
                <p class="text-muted small mb-0">Learn about specific fruits and foods that could be harmful.</p>
            </div>
        </div>
    </div>
    <!-- Video 4 -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/6iK9yUPYHAY" title="Sobrang Pagduduwal at Pagsusuka - Morning Sickness" allowfullscreen></iframe>
            </div>
            <div class="card-body p-3">
                <span class="badge bg-info-subtle text-info mb-2 text-dark">Morning Sickness</span>
                <h6 class="fw-bold text-dark mb-1">Pagduduwal at Pagsusuka</h6>
                <p class="text-muted small mb-0">Ate Nurse shares tips on how to handle severe morning sickness.</p>
            </div>
        </div>
    </div>
    <!-- Video 5 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/SeKIZy-1tDA" title="5 Pregnancy Tips You Should Not Ignore" allowfullscreen></iframe>
            </div>
            <div class="card-body p-3">
                <span class="badge bg-warning-subtle text-warning mb-2 text-dark">General Tips</span>
                <h6 class="fw-bold text-dark mb-1">5 Pregnancy Tips</h6>
                <p class="text-muted small mb-0">Important pregnancy practices you shouldn't ignore for a healthy birth.</p>
            </div>
        </div>
    </div>
    <!-- Video 6 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/aOxu0JTiwDY" title="Senyales na Malapit ng Manganak" allowfullscreen></iframe>
            </div>
            <div class="card-body p-3">
                <span class="badge bg-danger-subtle text-danger mb-2">Preparation</span>
                <h6 class="fw-bold text-dark mb-1">Malapit ng Manganak</h6>
                <p class="text-muted small mb-0">Ate Nurse points out the signs to look for when labor is approaching.</p>
            </div>
        </div>
    </div>
    <!-- Video 7 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/yZAWU8rCEQs" title="Senyales na Hindi Safe ang Baby sa Tiyan" allowfullscreen></iframe>
            </div>
            <div class="card-body p-3">
                <span class="badge bg-secondary-subtle text-secondary mb-2">Pre-Caution</span>
                <h6 class="fw-bold text-dark mb-1">Warning Signs</h6>
                <p class="text-muted small mb-0">Nurse Yeza outlines the symptoms that indicate your baby might not be safe.</p>
            </div>
        </div>
    </div>
</div>

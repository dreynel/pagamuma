<!-- c:\xampp\htdocs\pagamuma\mothers\pages\emotional.php -->
<?php
require_once __DIR__ . '/../../config/db.php';
$stmt = $pdo->prepare("SELECT * FROM educational_modules WHERE category = 'emotional'");
$stmt->execute();
$articles = $stmt->fetchAll();
?>
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-dark mb-1" data-i18n="menu_emotional">Emotional Support</h2>
        <p class="text-muted">Mental health resources to navigate the emotional changes of pregnancy.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6 border-end-md">
        <h4 class="fw-bold text-dark mb-4">Mindfulness & Mental Health</h4>
        <?php if(empty($articles)): ?>
            <p class="text-muted py-3">No articles available.</p>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
            <a href="index.php?page=read_article&id=<?= $article['id'] ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body p-4 d-flex align-items-center">
                        <i class="fa-solid fa-om fs-2 text-warning bg-warning bg-opacity-10 p-3 rounded-circle me-4"></i>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">
                                <span class="lang-en"><?= htmlspecialchars($article['title_en']) ?></span>
                                <span class="lang-hil d-none"><?= htmlspecialchars($article['title_hil']) ?></span>
                                <span class="lang-tl d-none"><?= htmlspecialchars($article['title_tl']) ?></span>
                            </h5>
                            <p class="text-muted small mb-0" style="max-height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <span class="lang-en"><?= strip_tags($article['content_en']) ?></span>
                                <span class="lang-hil d-none"><?= strip_tags($article['content_hil']) ?></span>
                                <span class="lang-tl d-none"><?= strip_tags($article['content_tl']) ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="col-md-6 ps-md-4">
        <h4 class="fw-bold text-dark mb-4">Community Support</h4>
        <div class="card border-0 shadow-sm bg-primary text-white rounded-4 p-4 text-center">
            <i class="fa-solid fa-users fs-1 mb-3"></i>
            <h5 class="fw-bold mb-2">You are not alone.</h5>
            <p class="mb-4">Connect with other mothers in the PAG-AMUMA community to share experiences and receive support.</p>
            <a href="https://www.facebook.com/groups/767354806309428/" target="_blank" class="btn btn-light text-primary fw-bold rounded-pill mx-auto mb-2 text-decoration-none"><i class="fa-brands fa-facebook me-2"></i> Join FB Group</a>
        </div>
    </div>
</div>

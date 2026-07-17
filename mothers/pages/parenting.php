<!-- c:\xampp\htdocs\pagamuma\mothers\pages\parenting.php -->
<?php
require_once __DIR__ . '/../../config/db.php';
$stmt = $pdo->prepare("SELECT * FROM educational_modules WHERE category = 'parenting'");
$stmt->execute();
$articles = $stmt->fetchAll();
?>
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-dark mb-1" data-i18n="menu_parenting">Parenting Basics</h2>
        <p class="text-muted" data-i18n="parenting_subtitle">Prepare for your baby's arrival with essential newborn care skills.</p>
    </div>
</div>

<div class="row g-4">
    <?php if(empty($articles)): ?>
        <div class="col-12 text-center text-muted py-5">
            <i class="fa-solid fa-folder-open fs-1 mb-3 opacity-50"></i>
            <h5 data-i18n="no_parenting_modules">No parenting modules available yet.</h5>
        </div>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
        <div class="col-md-4 mb-4">
            <a href="index.php?page=read_article&id=<?= $article['id'] ?>" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-center text-md-start" style="transition: transform 0.2s;">
                    <div class="bg-light p-4 d-flex justify-content-center align-items-center">
                        <i class="fa-solid fa-baby fs-1 text-info border border-2 border-info rounded-circle p-3 bg-white shadow-sm"></i>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <span class="badge bg-info bg-opacity-10 text-info mb-3 align-self-start" data-i18n="parenting_library">Parenting Library</span>
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
                        <div class="mt-4 pt-3 border-top text-info fw-medium small d-flex justify-content-between align-items-center">
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

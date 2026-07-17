<?php
// c:\xampp\htdocs\pagamuma\mothers\pages\read_article.php
require_once __DIR__ . '/../../config/db.php';

$article_id = $_GET['id'] ?? null;
if(!$article_id) {
    echo "<div class='alert alert-danger'>Article ID missing!</div>";
    return;
}

$stmt = $pdo->prepare("SELECT * FROM educational_modules WHERE id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if(!$article) {
    echo "<div class='alert alert-danger'>Article not found!</div>";
    return;
}
?>

<style>
/* Custom Book-Like Typography */
.book-reader {
    background-color: #fdfbf7;
    font-family: 'Merriweather', 'Georgia', serif;
    color: #2c2c2c;
    line-height: 1.8;
    font-size: 1.1rem;
    max-width: 800px;
    margin: 0 auto;
    border-radius: 12px;
    box-shadow: inset 0 0 20px rgba(0,0,0,0.03), 0 10px 40px rgba(0,0,0,0.08);
}

.book-reader h1.chapter-title {
    font-family: 'Playfair Display', 'Merriweather', serif;
    font-size: 2.8rem;
    font-weight: 700;
    text-align: center;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.book-reader .chapter-subtitle {
    font-family: 'Outfit', sans-serif;
    color: #b0a599;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-size: 0.85rem;
    margin-bottom: 3rem;
}

.book-reader h3, .book-reader h4 {
    font-family: 'Playfair Display', serif;
    color: #4a3f35;
    margin-top: 2.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.book-reader h3 {
    font-size: 1.8rem;
    border-bottom: 1px solid #eae1d8;
    padding-bottom: 0.5rem;
}

.book-reader h4 {
    font-size: 1.4rem;
}

.book-reader p {
    text-align: justify;
    margin-bottom: 1.5rem;
}

/* Drop cap for the first paragraph of the content */
.book-reader > .content-body > p:first-of-type::first-letter {
    font-family: 'Playfair Display', serif;
    font-size: 3.8rem;
    float: left;
    margin-top: -0.1em;
    margin-bottom: -0.1em;
    margin-right: 0.15em;
    line-height: 1;
    color: var(--bs-primary);
}

.book-header-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.book-back-btn {
    color: #6c757d;
    text-decoration: none;
    font-family: 'Outfit', sans-serif;
    font-weight: 500;
    transition: color 0.2s;
}

.book-back-btn:hover {
    color: var(--bs-primary);
}
</style>

<!-- Google Fonts for the Book Reader -->
<link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;1,400&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">

<div class="container py-2">
    <div class="book-header-nav max-w-800 mx-auto" style="max-width: 800px;">
        <a href="index.php?page=<?= htmlspecialchars($article['category']) == 'health' ? 'health_modules' : htmlspecialchars($article['category']) ?>" class="book-back-btn">
            <i class="fa-solid fa-arrow-left me-2"></i> <span data-i18n="back_to">Back to</span> 
            <span data-i18n="menu_<?= htmlspecialchars($article['category']) ?>">
                <?= ucfirst(htmlspecialchars($article['category'])) ?>
            </span>
        </a>
        <div class="text-muted small font-outfit" style="font-family: 'Outfit', sans-serif;">
            <i class="fa-regular fa-bookmark me-1"></i> Chapter <?= $article['id'] ?>
        </div>
    </div>

    <div class="book-reader p-4 p-md-5">
        <h1 class="chapter-title">
            <span class="lang-en"><?= htmlspecialchars($article['title_en']) ?></span>
            <span class="lang-hil d-none"><?= htmlspecialchars($article['title_hil']) ?></span>
            <span class="lang-tl d-none"><?= htmlspecialchars($article['title_tl']) ?></span>
        </h1>
        <div class="chapter-subtitle">PAG-AMUMA • Library</div>
        
        <div class="content-body">
            <!-- Injecting the rich HTML content perfectly -->
            <div class="lang-en"><?= $article['content_en'] ?></div>
            <div class="lang-hil d-none"><?= $article['content_hil'] ?></div>
            <div class="lang-tl d-none"><?= $article['content_tl'] ?></div>
        </div>
        
        <div class="mt-5 text-center">
            <i class="fa-solid fa-seedling text-primary opacity-25 fs-1"></i>
            <p class="text-muted small mt-3" style="font-family: 'Outfit', sans-serif;">End of chapter.</p>
        </div>
    </div>
</div>

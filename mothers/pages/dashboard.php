<!-- c:\xampp\htdocs\pagamuma\mothers\pages\dashboard.php -->
<?php
require_once __DIR__ . '/../../config/db.php';
$user_id = $_SESSION['user_id'];

// Fetch profile
$stmt = $pdo->prepare("SELECT * FROM pregnancy_profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

// Fetch latest health log
$log_stmt = $pdo->prepare("SELECT * FROM health_logs WHERE user_id = ? ORDER BY log_date DESC, id DESC LIMIT 1");
$log_stmt->execute([$user_id]);
$latest_log = $log_stmt->fetch();

// Calculate pregnancy week
$current_week = 0;
$trimester = 1;
$baby_size = "Not set";
$has_edd = false;

if ($profile && !empty($profile['expected_due_date'])) {
    $has_edd = true;
    $edd = new DateTime($profile['expected_due_date']);
    $today = new DateTime();
    
    // Conception is typically 280 days before EDD
    $conception = clone $edd;
    $conception->modify('-280 days');
    
    $diff = $conception->diff($today);
    $days_pregnant = $diff->days;
    // If today is before conception date somehow
    if ($today < $conception) {
        $days_pregnant = 0;
    }
    
    $current_week = floor($days_pregnant / 7);
    
    if ($current_week < 13) {
        $trimester = 1;
    } elseif ($current_week < 28) {
        $trimester = 2;
    } else {
        $trimester = 3;
    }
    
    // Simple fruit scale
    $fruits = [
        4=>'Poppy Seed', 5=>'Apple Seed', 6=>'Sweet Pea', 7=>'Blueberry', 8=>'Raspberry',
        9=>'Green Olive', 10=>'Prune', 11=>'Lime', 12=>'Plum', 13=>'Peach', 14=>'Lemon',
        15=>'Apple', 16=>'Avocado', 17=>'Turnip', 18=>'Bell Pepper', 19=>'Heirloom Tomato',
        20=>'Banana', 21=>'Carrot', 22=>'Spaghetti Squash', 23=>'Large Mango', 24=>'Ear of Corn',
        25=>'Rutabaga', 26=>'Scallion', 27=>'Cauliflower', 28=>'Eggplant', 29=>'Acorn Squash',
        30=>'Cabbage', 31=>'Coconut', 32=>'Jicama', 33=>'Pineapple', 34=>'Cantaloupe',
        35=>'Honeydew', 36=>'Romaine Lettuce', 37=>'Swiss Chard', 38=>'Leek', 39=>'Mini Watermelon',
        40=>'Small Pumpkin'
    ];
    if(isset($fruits[$current_week])) {
        $baby_size = $fruits[$current_week];
    } else if ($current_week < 4) {
        $baby_size = "Too tiny to measure";
    } else {
        $baby_size = "Watermelon";
    }
}
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-dark mb-1">Welcome, <?= htmlspecialchars($_SESSION['first_name']) ?> 👋</h2>
        <p class="text-muted mb-0" data-i18n="dash_summary">Here is your daily pregnancy summary.</p>

    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="index.php?page=tracking" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm fw-medium"><i class="fa-solid fa-heart-pulse me-2"></i> <span data-i18n="dash_log_health">Log Health Today</span></a>
    </div>
</div>

<?php if(!$has_edd): ?>
<div class="alert alert-warning rounded-4 mb-4 shadow-sm border-0 bg-warning-subtle text-dark">
    <div class="d-flex align-items-center p-2">
        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm flex-shrink-0" style="width:50px;height:50px;">
            <i class="fa-solid fa-calendar-plus fs-4"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-1" data-i18n="dash_track_journey">Let's track your journey!</h5>
            <p class="mb-0 text-muted"><span data-i18n="dash_set_edd">Set your expected due date to unlock personalized weekly insights.</span> <a href="index.php?page=settings" class="fw-bold text-warning text-decoration-none"><span data-i18n="dash_go_settings">Go to Settings</span> &rarr;</a></p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Journey Progress Bar -->
<?php if($has_edd): 
    $progress_percent = min(100, round(($current_week / 40) * 100));
    $fun_facts = [
        1 => "Your baby's heart is already beating, and all major organs are starting to form! The foundation is being laid rapidly.",
        2 => "Your baby can now hear your voice! This is often called the 'Golden Trimester'—enjoy that returning energy boost.",
        3 => "Your baby is putting on fat, practicing breathing, and getting ready to meet the world. Rest up and listen to your body!"
    ];
    $trimester_fact = $fun_facts[$trimester] ?? "Every pregnancy is beautiful and unique!";
?>
<div class="card border-0 shadow-sm rounded-4 mb-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-person-pregnant text-primary me-2"></i> <span data-i18n="dash_journey">Pregnancy Journey</span></h6>
        <span class="badge bg-primary text-white rounded-pill px-3 py-2 shadow-sm"><?= $progress_percent ?>% <span data-i18n="dash_complete">Complete</span></span>
    </div>
    <div class="progress bg-light shadow-inner" style="height: 14px; border-radius: 10px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-gradient-primary" role="progressbar" style="width: <?= $progress_percent ?>%; background: linear-gradient(90deg, #7c3aed, #ec4899);" aria-valuenow="<?= $progress_percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="d-flex justify-content-between mt-2 px-1 position-relative">
        <small class="text-muted fw-bold"><span data-i18n="dash_week">Week</span> 1</small>
        <small class="text-primary fw-bold" style="position: absolute; left: <?= $progress_percent ?>%; transform: translateX(-50%);"><span data-i18n="dash_week">Week</span> <?= $current_week ?></small>
        <small class="text-muted fw-bold"><span data-i18n="dash_week">Week</span> 40+</small>
    </div>
</div>
<?php endif; ?>

<div class="row g-4 mb-4">
    <!-- Featured Weekly Insight -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-0 overflow-hidden">
            <div class="row g-0 h-100">
                <div class="col-md-5 text-white p-4 d-flex flex-column justify-content-center align-items-center position-relative" style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-lg mb-3" style="width:80px;height:80px;">
                        <i class="fa-solid fa-leaf fs-1"></i>
                    </div>
                    <h5 class="fw-bold mb-1 opacity-75"><span data-i18n="dash_week">Week</span> <?= $has_edd ? $current_week : '--' ?></h5>
                    <div class="text-center mt-2">
                        <p class="mb-0 fw-medium" style="font-size: 0.95rem;" data-i18n="dash_baby_size">Baby is the size of a</p>
                        <p class="display-6 fw-bold mb-0 lh-sm mt-1"><?= $baby_size ?></p>
                    </div>
                    <button class="btn btn-sm btn-light rounded-pill mt-4 fw-bold text-primary shadow-sm px-4 py-2 hover-lift"><i class="fa-brands fa-facebook me-2"></i> <span data-i18n="dash_share">Share</span></button>
                </div>
                <div class="col-md-7 p-4 bg-white d-flex flex-column">
                    <div>
                        <span class="badge bg-primary-light text-primary mb-3 px-3 py-2 rounded-pill fw-bold"><span data-i18n="dash_trimester">Trimester</span> <?= $trimester ?></span>
                        <h4 class="fw-bold text-dark mb-3" data-i18n="dash_insight">Development Insight</h4>
                        <p class="text-muted mb-4 lh-lg" style="font-size: 1.05rem;"><?= $has_edd ? $trimester_fact : '<span data-i18n="dash_insight_placeholder">Set your due date to unlock medical insights for your current week of pregnancy.</span>' ?></p>
                    </div>
                    <div class="mt-auto bg-light rounded-4 p-3 border border-light shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-stethoscope fs-3 text-secondary me-3"></i>
                            <div>
                                <h6 class="fw-bold text-dark mb-1" data-i18n="dash_unwell">Feeling unwell?</h6>
                                <p class="text-muted small mb-0" data-i18n="dash_ask_chatbot">Ask the Chatbot for guidance.</p>
                            </div>
                            <a href="index.php?page=chatbot" class="btn btn-outline-primary btn-sm rounded-pill ms-auto fw-bold px-3 py-2" data-i18n="dash_chat_now">Chat Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Column -->
    <div class="col-lg-4 d-flex flex-column gap-4">
        <!-- Next Checkup / EDD -->
        <div class="card border-0 shadow-sm rounded-4 p-4 flex-grow-1" style="background: linear-gradient(135deg, #fff0f5, #fff);">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm me-3 flex-shrink-0" style="width:45px;height:45px;">
                    <i class="fa-solid fa-calendar-day fs-5"></i>
                </div>
                <h6 class="fw-bold mb-0 text-dark" data-i18n="dash_due_date">Due Date</h6>
            </div>
            <?php if($has_edd): ?>
                <h3 class="fw-bold text-primary mb-1"><?= date('M d, Y', strtotime($profile['expected_due_date'])) ?></h3>
                <div class="d-flex align-items-center mt-2">
                    <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm"><i class="fa-regular fa-clock me-1"></i> <?= $diff->days ?> <span data-i18n="dash_days_left">days left!</span></span>
                </div>
            <?php else: ?>
                <h3 class="fw-bold text-muted mb-0" data-i18n="dash_not_set">Not set</h3>
            <?php endif; ?>
        </div>
        
        <!-- Latest Weight -->
        <div class="card border-0 shadow-sm rounded-4 p-4 flex-grow-1">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width:45px;height:45px;">
                    <i class="fa-solid fa-weight-scale fs-5"></i>
                </div>
                <h6 class="fw-bold mb-0 text-dark" data-i18n="dash_latest_weight">Latest Weight</h6>
            </div>
            <?php if($latest_log && $latest_log['weight_kg']): ?>
                <h2 class="fw-bold text-dark mb-1 d-flex align-items-end"><?= htmlspecialchars($latest_log['weight_kg']) ?> <span class="fs-5 text-muted ms-1 fw-medium mb-1">kg</span></h2>
                <p class="text-muted small mb-0"><i class="fa-regular fa-calendar-check me-1"></i> <span data-i18n="dash_logged">Logged</span> <?= date('M d', strtotime($latest_log['log_date'])) ?></p>
            <?php else: ?>
                <h3 class="fw-bold text-muted mb-0">--</h3>
                <p class="text-muted small mt-2 mb-0"><span data-i18n="dash_no_vitals">No vitals logged yet.</span> <br><a href="index.php?page=tracking" class="fw-bold text-decoration-none" data-i18n="dash_log_now">Log Now</a></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recommended Readings -->
<div class="row pb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark mb-0"><span data-i18n="dash_curated">Curated For You (Week</span> <?= $has_edd && $current_week > 0 ? $current_week : '1' ?>)</h5>
                <a href="index.php?page=health_modules" class="text-decoration-none text-primary fw-bold small"><span data-i18n="dash_library">Library</span> &rarr;</a>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 rounded-4 border border-light bg-light hover-shadow transition-all" style="cursor: pointer;" onclick="window.location.href='index.php?page=health_modules'">
                        <div class="bg-white p-3 rounded-circle me-3 shadow-sm flex-shrink-0">
                            <i class="fa-solid fa-apple-whole fs-3 text-success"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1"><span data-i18n="dash_nutrition">Nutrition During Trimester</span> <?= $trimester ?></h6>
                            <p class="text-muted small mb-0" data-i18n="dash_nutrition_desc">Learn what foods to eat and avoid right now for optimal growth.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 rounded-4 border border-light bg-light hover-shadow transition-all" style="cursor: pointer;" onclick="window.location.href='index.php?page=emotional'">
                        <div class="bg-white p-3 rounded-circle me-3 shadow-sm flex-shrink-0">
                            <i class="fa-solid fa-bed fs-3 text-info"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1" data-i18n="dash_fatigue">Managing Fatigue & Symptoms</h6>
                            <p class="text-muted small mb-0" data-i18n="dash_fatigue_desc">Tips and tricks to ease discomfort and get proper sleep.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

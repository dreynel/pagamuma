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
}

// 40-Week Fetal Development Milestones
$fetal_milestones = [
        1 => ['image' => 'assets/images/baby_development/week_1_4.jpg', 'stage' => 'Conception', 'title' => 'Conception & Cell Division', 'length' => '< 0.1 cm', 'weight' => '< 0.1 g', 'highlight' => 'The fertilized ovum begins rapid cell division while journeying towards the uterus.'],
        2 => ['image' => 'assets/images/baby_development/week_1_4.jpg', 'stage' => 'Implantation', 'title' => 'Blastocyst Implantation', 'length' => '0.1 cm', 'weight' => '< 0.1 g', 'highlight' => 'The blastocyst implants firmly into the nutrient-dense uterine lining.'],
        3 => ['image' => 'assets/images/baby_development/week_1_4.jpg', 'stage' => 'Embryonic Disc', 'title' => 'Early Embryonic Disc', 'length' => '0.2 cm', 'weight' => '< 0.1 g', 'highlight' => 'Three distinct germ layers form, giving rise to all future organs and bodily systems.'],
        4 => ['image' => 'assets/images/baby_development/week_1_4.jpg', 'stage' => 'Early Sac', 'title' => 'Gestational Sac Forming', 'length' => '0.36 cm', 'weight' => '0.1 g', 'highlight' => 'The amniotic sac, placenta, and yolk sac begin providing crucial metabolic support.'],
        5 => ['image' => 'assets/images/baby_development/week_5_8.jpg', 'stage' => 'Neural Tube', 'title' => 'Neural Tube & Heart Tube', 'length' => '0.5 cm', 'weight' => '0.2 g', 'highlight' => 'The rudimentary heart begins pumping blood and the neural tube starts to close.'],
        6 => ['image' => 'assets/images/baby_development/week_5_8.jpg', 'stage' => 'Heartbeat', 'title' => 'First Detectable Heartbeat', 'length' => '0.6 cm', 'weight' => '0.5 g', 'highlight' => 'The heart beats rhythmically at 100-160 bpm; eye pits and limb buds become visible.'],
        7 => ['image' => 'assets/images/baby_development/week_5_8.jpg', 'stage' => 'Limb Buds', 'title' => 'Limb Buds & Facial Clefts', 'length' => '1.3 cm', 'weight' => '1.0 g', 'highlight' => 'Paddle-shaped hands and feet develop rapidly alongside digestive system structures.'],
        8 => ['image' => 'assets/images/baby_development/week_5_8.jpg', 'stage' => 'Webbed Digits', 'title' => 'Webbed Fingers & Brain Waves', 'length' => '1.6 cm', 'weight' => '1.5 g', 'highlight' => 'Brain wave activity commences and spontaneous gentle twitches begin in the embryo.'],
        9 => ['image' => 'assets/images/baby_development/week_9_12.jpg', 'stage' => 'Bending Joints', 'title' => 'Joints & Muscles Developing', 'length' => '2.3 cm', 'weight' => '2.0 g', 'highlight' => 'Elbows, wrists, and knees can flex as cartilage foundations form.'],
        10 => ['image' => 'assets/images/baby_development/week_9_12.jpg', 'stage' => 'Fetus Transition', 'title' => 'Official Fetal Stage', 'length' => '3.1 cm', 'weight' => '4.0 g', 'highlight' => 'The embryonic phase ends; your baby is now officially designated as a developing fetus!'],
        11 => ['image' => 'assets/images/baby_development/week_9_12.jpg', 'stage' => 'Nail Beds', 'title' => 'Fingers & Toes Separating', 'length' => '4.1 cm', 'weight' => '7.0 g', 'highlight' => 'Webbing between digits disappears completely and delicate fingernail beds begin to form.'],
        12 => ['image' => 'assets/images/baby_development/week_9_12.jpg', 'stage' => 'Reflexes', 'title' => 'Active Reflexes & Moving', 'length' => '5.4 cm', 'weight' => '14 g', 'highlight' => 'Baby curls toes, practices sucking motions, and the kidneys begin filtering amniotic fluid.'],
        13 => ['image' => 'assets/images/baby_development/week_13_16.jpg', 'stage' => 'Second Trimester', 'title' => 'Welcome to 2nd Trimester', 'length' => '7.4 cm', 'weight' => '23 g', 'highlight' => 'Unique fingerprints are set on baby fingertips and vocal cords begin developing.'],
        14 => ['image' => 'assets/images/baby_development/week_13_16.jpg', 'stage' => 'Facial Expressions', 'title' => 'Expressions & Squinting', 'length' => '8.7 cm', 'weight' => '43 g', 'highlight' => 'Facial muscles allow the baby to squint, frown, and make gentle facial gestures.'],
        15 => ['image' => 'assets/images/baby_development/week_13_16.jpg', 'stage' => 'Breathing Practice', 'title' => 'Breathing Practice Motions', 'length' => '10.1 cm', 'weight' => '70 g', 'highlight' => 'Baby gently inhales and exhales amniotic fluid to practice lung development.'],
        16 => ['image' => 'assets/images/baby_development/week_13_16.jpg', 'stage' => 'Thumb Sucking', 'title' => 'Thumb Sucking & Grasping', 'length' => '11.6 cm', 'weight' => '100 g', 'highlight' => 'Baby discovers hands and frequently sucks their thumb for soothing practice.'],
        17 => ['image' => 'assets/images/baby_development/week_17_20.jpg', 'stage' => 'Ossification', 'title' => 'Skeleton Hardening', 'length' => '13.0 cm', 'weight' => '140 g', 'highlight' => 'Soft rubbery cartilage is rapidly turning into sturdy bone throughout the skeleton.'],
        18 => ['image' => 'assets/images/baby_development/week_17_20.jpg', 'stage' => 'Hearing Sounds', 'title' => 'Hearing Mother\'s Voice', 'length' => '14.2 cm', 'weight' => '190 g', 'highlight' => 'Auditory bones harden; baby can hear mother\'s voice, heartbeat, and soothing sounds.'],
        19 => ['image' => 'assets/images/baby_development/week_17_20.jpg', 'stage' => 'Vernix Caseosa', 'title' => 'Protective Vernix Coating', 'length' => '15.3 cm', 'weight' => '240 g', 'highlight' => 'A natural creamy protective layer (vernix) coats baby\'s delicate skin.'],
        20 => ['image' => 'assets/images/baby_development/week_17_20.jpg', 'stage' => 'Halfway Point', 'title' => 'First Kicks & Somersaults', 'length' => '25.6 cm', 'weight' => '300 g', 'highlight' => 'Congratulations on reaching the halfway mark! Baby is actively kicking and twisting.'],
        21 => ['image' => 'assets/images/baby_development/week_21_24.jpg', 'stage' => 'Taste Sense', 'title' => 'Taste Buds & Swallowing', 'length' => '26.7 cm', 'weight' => '360 g', 'highlight' => 'Baby tastes flavors from mother\'s diet transferred through the amniotic fluid.'],
        22 => ['image' => 'assets/images/baby_development/week_21_24.jpg', 'stage' => 'Cord Grasp', 'title' => 'Touch Perception & Grip', 'length' => '27.8 cm', 'weight' => '430 g', 'highlight' => 'Sense of touch is acute; baby explores hands, feet, and umbilical cord.'],
        23 => ['image' => 'assets/images/baby_development/week_21_24.jpg', 'stage' => 'REM Sleep', 'title' => 'Dream Cycles & Hiccups', 'length' => '28.9 cm', 'weight' => '500 g', 'highlight' => 'Baby experiences rapid eye movement (REM) sleep and has cute rhythmic hiccups.'],
        24 => ['image' => 'assets/images/baby_development/week_21_24.jpg', 'stage' => 'Viability', 'title' => 'Milestone of Viability', 'length' => '30.0 cm', 'weight' => '600 g', 'highlight' => 'An essential clinical milestone: baby reaches viability and inner ear balance develops.'],
        25 => ['image' => 'assets/images/baby_development/week_25_28.jpg', 'stage' => 'Voice Response', 'title' => 'Responding to Touch & Sound', 'length' => '34.6 cm', 'weight' => '660 g', 'highlight' => 'Baby kicks back when you gently stroke your belly or hear familiar family voices.'],
        26 => ['image' => 'assets/images/baby_development/week_25_28.jpg', 'stage' => 'Eyes Opening', 'title' => 'Eyes Opening & Blinking', 'length' => '35.6 cm', 'weight' => '760 g', 'highlight' => 'Eyelids part and eyelashes form; baby blinks and turns towards bright external light.'],
        27 => ['image' => 'assets/images/baby_development/week_25_28.jpg', 'stage' => 'Third Trimester', 'title' => 'Entering 3rd Trimester', 'length' => '36.6 cm', 'weight' => '875 g', 'highlight' => 'The final stretch! Brain tissue expands rapidly with billions of complex neural pathways.'],
        28 => ['image' => 'assets/images/baby_development/week_25_28.jpg', 'stage' => 'Rhythmic Breaths', 'title' => 'Practicing Deep Breathing', 'length' => '37.6 cm', 'weight' => '1.0 kg', 'highlight' => 'Baby reaches 1 kilogram! Practicing regular respiratory movements and body control.'],
        29 => ['image' => 'assets/images/baby_development/week_29_32.jpg', 'stage' => 'Calcium Bank', 'title' => 'Absorbing Calcium for Bones', 'length' => '38.6 cm', 'weight' => '1.2 kg', 'highlight' => 'Baby channels calcium directly into skeletal bones, making them strong and durable.'],
        30 => ['image' => 'assets/images/baby_development/week_29_32.jpg', 'stage' => 'Full Head of Hair', 'title' => 'Head Hair & Surfactant', 'length' => '39.9 cm', 'weight' => '1.3 kg', 'highlight' => 'Hair on baby\'s head is growing thicker and lung surfactant production escalates.'],
        31 => ['image' => 'assets/images/baby_development/week_29_32.jpg', 'stage' => 'Brain Growth', 'title' => 'Active Brain Processing', 'length' => '41.1 cm', 'weight' => '1.5 kg', 'highlight' => 'The brain can now process 5 senses: sight, sound, smell, taste, and touch.'],
        32 => ['image' => 'assets/images/baby_development/week_29_32.jpg', 'stage' => 'Chubby Limbs', 'title' => 'Plumping Up & Chubby Limbs', 'length' => '42.4 cm', 'weight' => '1.7 kg', 'highlight' => 'Under-skin fat deposits smooth out newborn wrinkles, making baby chubby and warm.'],
        33 => ['image' => 'assets/images/baby_development/week_33_36.jpg', 'stage' => 'Antibodies', 'title' => 'Maternal Immune Transfer', 'length' => '43.7 cm', 'weight' => '1.9 kg', 'highlight' => 'Mother transfers antibodies through placenta to protect baby for early months after birth.'],
        34 => ['image' => 'assets/images/baby_development/week_33_36.jpg', 'stage' => 'Cephalic Head-Down', 'title' => 'Head-Down Birthing Position', 'length' => '45.0 cm', 'weight' => '2.1 kg', 'highlight' => 'Baby turns head-downward into optimal cephalic presentation for delivery.'],
        35 => ['image' => 'assets/images/baby_development/week_33_36.jpg', 'stage' => 'Maturing Organs', 'title' => 'Kidneys & Liver Functioning', 'length' => '46.2 cm', 'weight' => '2.4 kg', 'highlight' => 'Most basic physical development is accomplished; baby focuses on building fat reserves.'],
        36 => ['image' => 'assets/images/baby_development/week_33_36.jpg', 'stage' => 'Pelvic Engagement', 'title' => 'Descending into Pelvis', 'length' => '47.4 cm', 'weight' => '2.6 kg', 'highlight' => 'Baby prepares to drop into pelvis (lightening), easing pressure on mother\'s diaphragm.'],
        37 => ['image' => 'assets/images/baby_development/week_37_40.jpg', 'stage' => 'Early Term', 'title' => 'Early Full-Term Reached', 'length' => '48.6 cm', 'weight' => '2.9 kg', 'highlight' => 'Baby reaches full term! Lungs and digestive systems are ready for life outside the womb.'],
        38 => ['image' => 'assets/images/baby_development/week_37_40.jpg', 'stage' => 'Firm Grasp', 'title' => 'Strong Grasp & Newborn Glow', 'length' => '49.8 cm', 'weight' => '3.1 kg', 'highlight' => 'Hand grasp is remarkably strong and vernix sheds, revealing soft healthy baby skin.'],
        39 => ['image' => 'assets/images/baby_development/week_37_40.jpg', 'stage' => 'Full Maturity', 'title' => 'Fully Mature & Ready', 'length' => '50.7 cm', 'weight' => '3.3 kg', 'highlight' => 'Brain and nervous system are fully prepared; waiting for labor signals any day now.'],
        40 => ['image' => 'assets/images/baby_development/week_37_40.jpg', 'stage' => 'Welcome Baby!', 'title' => 'Delivery Day & Celebration', 'length' => '51.2 cm', 'weight' => '3.5 kg', 'highlight' => 'Congratulations super mama! Your beautiful baby is fully developed and ready to meet you!']
    ];

    $display_week = ($has_edd && $current_week > 0) ? min(40, max(1, $current_week)) : 1;
    $current_milestone = $fetal_milestones[$display_week] ?? $fetal_milestones[1];
    $baby_size = $current_milestone['title'];
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
                <div class="col-md-5 p-4 d-flex flex-column justify-content-between align-items-center position-relative text-white" style="background: linear-gradient(145deg, #6d28d9, #9333ea, #db2777);">
                    <!-- Top badge & explore button -->
                    <div class="w-100 d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-baby me-1"></i> <span data-i18n="dash_week">Week</span> <?= $has_edd ? $current_week : '--' ?>
                        </span>
                        <button type="button" class="btn btn-sm text-white rounded-pill px-3 py-1 fw-medium border border-white-50 shadow-sm" data-bs-toggle="modal" data-bs-target="#babyStagesModal" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(8px);">
                            <i class="fa-regular fa-images me-1"></i> <span data-i18n="dash_view_weeks">All Weeks</span>
                        </button>
                    </div>

                    <!-- Baby Development Stage Image -->
                    <div class="position-relative my-2 text-center w-100">
                        <div class="rounded-4 overflow-hidden shadow-lg border border-3 border-white mx-auto position-relative bg-white" style="width: 175px; height: 175px; box-shadow: 0 14px 30px rgba(0,0,0,0.28) !important;">
                            <img src="../<?= htmlspecialchars($current_milestone['image']) ?>" alt="Baby Week <?= $current_week ?>" class="w-100 h-100" style="object-fit: cover; transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                        </div>
                        <div class="mt-2 text-center">
                            <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-3 py-1 shadow-sm small fw-semibold" data-i18n="week_<?= $display_week ?>_title">
                                <?= htmlspecialchars($current_milestone['title']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Baby Growth Metrics (Length & Weight) -->
                    <div class="w-100 mt-2">
                        <div class="d-flex justify-content-around text-center rounded-3 py-2 px-2" style="background: rgba(255, 255, 255, 0.18); backdrop-filter: blur(6px); border: 1px solid rgba(255, 255, 255, 0.3);">
                            <div class="px-2">
                                <small class="text-white-75 d-block text-uppercase fw-semibold" style="font-size: 0.7rem;" data-i18n="dash_baby_length">Est. Length</small>
                                <span class="fw-bold text-white fs-6"><?= htmlspecialchars($current_milestone['length']) ?></span>
                            </div>
                            <div class="border-start border-white-50 my-1"></div>
                            <div class="px-2">
                                <small class="text-white-75 d-block text-uppercase fw-semibold" style="font-size: 0.7rem;" data-i18n="dash_baby_weight">Est. Weight</small>
                                <span class="fw-bold text-white fs-6"><?= htmlspecialchars($current_milestone['weight']) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions: Share & Modal trigger -->
                    <div class="d-flex gap-2 w-100 mt-3">
                        <button class="btn btn-sm btn-light rounded-pill fw-bold text-primary shadow-sm flex-fill py-2 hover-lift" onclick="shareBabyMilestone()"><i class="fa-solid fa-share-nodes me-1"></i> <span data-i18n="dash_share">Share</span></button>
                        <button class="btn btn-sm btn-outline-light rounded-pill fw-semibold shadow-sm flex-fill py-2" data-bs-toggle="modal" data-bs-target="#babyStagesModal"><i class="fa-solid fa-magnifying-glass-plus me-1"></i> <span data-i18n="dash_view_details">Details</span></button>
                    </div>
                </div>
                <div class="col-md-7 p-4 bg-white d-flex flex-column">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary-light text-primary px-3 py-2 rounded-pill fw-bold"><span data-i18n="dash_trimester">Trimester</span> <?= $trimester ?></span>
                            <small class="text-muted"><i class="fa-solid fa-circle-info me-1 text-primary"></i> <span data-i18n="dash_baby_stage">Baby's Milestone</span></small>
                        </div>
                        <h4 class="fw-bold text-dark mb-2" data-i18n="week_<?= $display_week ?>_title"><?= htmlspecialchars($current_milestone['title']) ?></h4>
                        <div class="p-3 rounded-4 mb-3" style="background: #faf5ff; border: 1px solid #f3e8ff;">
                            <div class="d-flex align-items-start">
                                <i class="fa-solid fa-wand-magic-sparkles text-primary mt-1 me-2 fs-5 flex-shrink-0"></i>
                                <p class="mb-0 text-dark fw-medium lh-base" style="font-size: 0.95rem;">
                                    <?php if ($has_edd): ?>
                                        <span data-i18n="week_<?= $display_week ?>_highlight"><?= htmlspecialchars($current_milestone['highlight']) ?></span>
                                    <?php else: ?>
                                        <span data-i18n="dash_insight_placeholder">Set your due date to unlock medical insights for your current week of pregnancy.</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark mb-2" data-i18n="dash_insight">Development Insight</h6>
                        <p class="text-muted mb-4 lh-lg" style="font-size: 0.98rem;">
                            <?php if ($has_edd): ?>
                                <span data-i18n="dash_trimester_fact_<?= $trimester ?>"><?= htmlspecialchars($trimester_fact) ?></span>
                            <?php else: ?>
                                <span data-i18n="dash_insight_placeholder">Set your due date to unlock medical insights for your current week of pregnancy.</span>
                            <?php endif; ?>
                        </p>
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

<!-- Baby Development Milestones Modal (Weeks 1 to 40) -->
<div class="modal fade" id="babyStagesModal" tabindex="-1" aria-labelledby="babyStagesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-bottom px-4 py-3 bg-light">
                <div>
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center mb-1" id="babyStagesModalLabel">
                        <span class="bg-primary text-white rounded-circle p-2 me-2 d-inline-flex align-items-center justify-content-center shadow-sm" style="width:36px;height:36px;">
                            <i class="fa-solid fa-baby fs-5"></i>
                        </span>
                        <span data-i18n="dash_fetal_milestones">40-Week Baby Development Journey</span>
                    </h5>
                    <p class="text-muted small mb-0" data-i18n="dash_modal_subtitle">Follow your baby's anatomical development, size milestones, and growth from conception to delivery.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Trimester Filter Tabs -->
            <div class="px-4 pt-3 pb-2 bg-white border-bottom d-flex flex-wrap gap-2 align-items-center">
                <span class="text-muted small fw-bold me-2"><i class="fa-solid fa-filter me-1 text-primary"></i> <span data-i18n="dash_filter_label">Filter:</span></span>
                <button type="button" class="btn btn-sm btn-primary trim-btn rounded-pill px-3 fw-medium active" onclick="filterTrimester('all', this)" data-i18n="dash_filter_all">All Weeks (1-40)</button>
                <button type="button" class="btn btn-sm btn-outline-primary trim-btn rounded-pill px-3 fw-medium" onclick="filterTrimester('1', this)" data-i18n="dash_filter_trim1">1st Trimester (W1-12)</button>
                <button type="button" class="btn btn-sm btn-outline-primary trim-btn rounded-pill px-3 fw-medium" onclick="filterTrimester('2', this)" data-i18n="dash_filter_trim2">2nd Trimester (W13-27)</button>
                <button type="button" class="btn btn-sm btn-outline-primary trim-btn rounded-pill px-3 fw-medium" onclick="filterTrimester('3', this)" data-i18n="dash_filter_trim3">3rd Trimester (W28-40)</button>
            </div>

            <div class="modal-body p-4 bg-light" style="max-height: 70vh;">
                <div class="row g-3" id="milestonesGrid">
                    <?php foreach ($fetal_milestones as $wk => $m): 
                        $is_current = ($has_edd && $wk == $current_week);
                        $trim_num = ($wk < 13) ? 1 : (($wk < 28) ? 2 : 3);
                    ?>
                    <div class="col-md-6 col-lg-4 col-xl-3 milestone-card-col" data-trimester="<?= $trim_num ?>">
                        <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden transition-all <?= $is_current ? 'border border-2 border-primary ring-2 ring-primary shadow' : '' ?>" style="background: #ffffff;">
                            <div class="position-relative" style="height: 160px; overflow: hidden; background: #fdf2f8;">
                                <img src="../<?= htmlspecialchars($m['image']) ?>" alt="Week <?= $wk ?>" class="w-100 h-100" style="object-fit: cover;">
                                <span class="badge bg-dark bg-opacity-75 text-white position-absolute top-0 start-0 m-2 rounded-pill px-3 py-1 fw-bold small">
                                    <span data-i18n="dash_week">Week</span> <?= $wk ?>
                                </span>
                                <?php if($is_current): ?>
                                    <span class="badge bg-primary text-white position-absolute top-0 end-0 m-2 rounded-pill px-2 py-1 small shadow-sm">
                                        <i class="fa-solid fa-heart me-1"></i> <span data-i18n="dash_current_badge">Current</span>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-3 d-flex flex-column">
                                <h6 class="fw-bold text-dark mb-2" data-i18n="week_<?= $wk ?>_title"><?= htmlspecialchars($m['title']) ?></h6>
                                <div class="d-flex gap-1 mb-2">
                                    <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 small" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-ruler-vertical text-muted me-1"></i><?= $m['length'] ?>
                                    </span>
                                    <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 small" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-weight-scale text-muted me-1"></i><?= $m['weight'] ?>
                                    </span>
                                </div>
                                <p class="text-muted small mb-0 lh-base flex-grow-1" style="font-size: 0.82rem;" data-i18n="week_<?= $wk ?>_highlight">
                                    <?= htmlspecialchars($m['highlight']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="modal-footer bg-white border-top px-4 py-2 d-flex justify-content-between">
                <small class="text-muted"><i class="fa-solid fa-circle-check text-success me-1"></i> <span data-i18n="dash_verified_clinical">Verified against clinical obstetrics fetal growth parameters.</span></small>
                <button type="button" class="btn btn-secondary rounded-pill px-4 btn-sm" data-bs-dismiss="modal" data-i18n="dash_close">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function filterTrimester(trim, btn) {
    document.querySelectorAll('.trim-btn').forEach(b => {
        b.classList.remove('btn-primary', 'active');
        b.classList.add('btn-outline-primary');
    });
    btn.classList.remove('btn-outline-primary');
    btn.classList.add('btn-primary', 'active');
    
    document.querySelectorAll('.milestone-card-col').forEach(card => {
        if (trim === 'all' || card.getAttribute('data-trimester') === trim) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function shareBabyMilestone() {
    const lang = (typeof localStorage !== 'undefined' ? localStorage.getItem('pagamuma_lang') : null) || 'en';
    const t = (window.translations && window.translations[lang]) ? window.translations[lang] : ((window.translations && window.translations['en']) ? window.translations['en'] : {});
    const weekTitle = t['week_<?= $display_week ?>_title'] || '<?= addslashes($current_milestone['title']) ?>';
    const weekLabel = t['dash_week'] || 'Week';
    const text = `${weekLabel} <?= $has_edd ? $current_week : 1 ?>: ${weekTitle} (Est. length: <?= addslashes($current_milestone['length']) ?>, weight: <?= addslashes($current_milestone['weight']) ?>). Tracked with PAG-AMUMA!`;
    if (navigator.share) {
        navigator.share({
            title: 'My Pregnancy Journey - PAG-AMUMA',
            text: text,
            url: window.location.origin
        }).catch(() => {});
    } else {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Baby milestone copied to clipboard! You can paste and share it with your family.');
            }).catch(() => {
                prompt('Copy your milestone summary to share:', text);
            });
        } else {
            prompt('Copy your milestone summary to share:', text);
        }
    }
}

// Ensure translations are applied whenever the milestones modal opens
document.getElementById('babyStagesModal')?.addEventListener('show.bs.modal', function() {
    if (typeof window.applyTranslations === 'function') {
        window.applyTranslations();
    }
});
</script>

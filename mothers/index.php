<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'admin') {
    header("Location: ../login.php");
    exit;
}
$page = $_GET['page'] ?? 'dashboard';

// Fetch profile picture again just in case it was updated during the session
require_once '../config/db.php';
$stmt_pic = $pdo->prepare("SELECT profile_picture FROM users WHERE id = ?");
$stmt_pic->execute([$_SESSION['user_id']]);
$user_pic = $stmt_pic->fetchColumn();
$profile_pic_src = !empty($user_pic) ? '../uploads/profile_pictures/' . htmlspecialchars($user_pic) : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) . '&background=ffcdd2&color=e57373&rounded=true';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAG-AMUMA: Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="d-flex" id="wrapper">
        
        <!-- Sidebar Backdrop for mobile -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- Sidebar -->
        <div class="sidebar shadow-sm" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 border-bottom position-relative">
                <button class="btn btn-sm btn-light position-absolute top-0 end-0 mt-2 me-2 d-lg-none text-muted border-0" id="sidebar-close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="logo-circle-small mx-auto mb-2">
                    <i class="fa-solid fa-child-reaching text-primary fs-4"></i>
                </div>
                <h5 class="fw-bold text-primary mb-0">PAG-AMUMA</h5>
            </div>
            
            <div class="user-profile text-center py-4">
                <img src="<?= $profile_pic_src ?>" alt="User" class="rounded-circle mb-2 profile-img border border-2 border-white shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                <h6 class="fw-semibold mb-0 text-dark"><?= htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) ?></h6>
                <small class="text-muted" data-i18n="pregnancy_week">Pre-Natal Tracking</small>
            </div>

            <div class="list-group list-group-flush px-3">
                <a href="index.php?page=dashboard" class="list-group-item list-group-item-action <?= $page == 'dashboard' ? 'active' : '' ?> rounded mb-1">
                    <i class="fa-solid fa-house me-2"></i> Dashboard
                </a>
                
                <div class="text-muted small fw-bold text-uppercase px-3 mt-3 mb-2" data-i18n="menu_learning">Learning</div>
                
                <a href="index.php?page=health_modules" class="list-group-item list-group-item-action <?= $page == 'health_modules' ? 'active' : '' ?> rounded mb-1">
                    <i class="fa-solid fa-book-medical me-2"></i> <span data-i18n="menu_health">Health Modules</span>
                </a>
                <a href="index.php?page=parenting" class="list-group-item list-group-item-action <?= $page == 'parenting' ? 'active' : '' ?> rounded mb-1">
                    <i class="fa-solid fa-baby-carriage me-2"></i> <span data-i18n="menu_parenting">Parenting Basics</span>
                </a>
                <a href="index.php?page=emotional" class="list-group-item list-group-item-action <?= $page == 'emotional' ? 'active' : '' ?> rounded mb-1">
                    <i class="fa-solid fa-heart-pulse me-2"></i> <span data-i18n="menu_emotional">Emotional Support</span>
                </a>

                <div class="text-muted small fw-bold text-uppercase px-3 mt-3 mb-2" data-i18n="menu_features">Features</div>

                <a href="index.php?page=tracking" class="list-group-item list-group-item-action <?= $page == 'tracking' ? 'active' : '' ?> rounded mb-1">
                    <i class="fa-solid fa-notes-medical me-2"></i> <span data-i18n="menu_tracking">Health Tracking</span>
                </a>
                <a href="index.php?page=chatbot" class="list-group-item list-group-item-action <?= $page == 'chatbot' ? 'bg-primary text-white' : 'bg-primary-light text-primary fw-medium' ?> rounded mb-1">
                    <i class="fa-solid fa-message me-2"></i> <span data-i18n="menu_chatbot">Ask PAG-AMUMA Chatbot</span>
                </a>

                <div class="text-muted small fw-bold text-uppercase px-3 mt-4 pt-2 border-top mb-2" data-i18n="menu_account">Account</div>
                
                <a href="index.php?page=settings" class="list-group-item list-group-item-action <?= $page == 'settings' ? 'active' : '' ?> rounded mb-1">
                    <i class="fa-solid fa-gear me-2"></i> <span data-i18n="menu_settings">Settings</span>
                </a>
                <a href="index.php?page=reviews" class="list-group-item list-group-item-action <?= $page == 'reviews' ? 'active' : '' ?> rounded mb-1">
                    <i class="fa-solid fa-star me-2"></i> <span data-i18n="menu_reviews">Platform Feedback</span>
                </a>
                <a href="../api/logout_action.php" class="list-group-item list-group-item-action rounded text-danger mt-1">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> <span data-i18n="menu_logout">Log Out</span>
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-grow-1 d-flex flex-column h-100 min-vh-100">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm px-4 py-3">
                <div class="d-flex align-items-center">
                    <button class="btn btn-light" id="menu-toggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h5 class="mb-0 ms-3 fw-semibold text-dark">Dashboard Overview</h5>
                </div>
                
                <!-- Navbar Right Info -->
                <div class="ms-auto d-flex align-items-center">
                     <button class="btn btn-light rounded-circle position-relative me-3">
                        <i class="fa-regular fa-bell text-muted"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                     </button>
                     <div class="language-selector">
                        <select class="form-select form-select-sm border-0 bg-light text-secondary fw-medium" id="languageSelector">
                            <option value="en">English</option>
                            <option value="hil">Hiligaynon</option>
                            <option value="tl">Tagalog</option>
                        </select>
                     </div>
                </div>
            </nav>

            <div class="container-fluid p-4 d-flex flex-column flex-grow-1 bg-light <?= $page !== 'dashboard' ? 'justify-content-start' : 'justify-content-start' ?>">
                <?php
                // Whitelist for security
                $allowed_pages = ['dashboard', 'health_modules', 'parenting', 'emotional', 'tracking', 'chatbot', 'settings', 'read_article', 'reviews'];
                
                if (in_array($page, $allowed_pages)) {
                    $file_path = "pages/{$page}.php";
                    if (file_exists($file_path)) {
                        include $file_path;
                    } else {
                        // Scaffolded placeholder for unbuilt pages
                        echo '<div class="row w-100 mx-auto my-auto h-100 align-items-center"><div class="col-12 text-center text-muted"><i class="fa-solid fa-person-digging fs-1 mb-3 text-secondary" style="opacity: 0.5;"></i><h4 class="text-dark fw-medium">'.htmlspecialchars(ucwords(str_replace('_', ' ', $page))).'</h4><p class="text-muted">This module is currently being built by the PAG-AMUMA team.</p></div></div>';
                    }
                } else {
                    echo "<div class='alert alert-danger'>Page not found!</div>";
                }
                ?>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/translations.js"></script>
    <script>
        // Sidebar Toggle Script
        const wrapper = document.getElementById("wrapper");
        
        document.getElementById("menu-toggle").addEventListener('click', function(e) {
            e.preventDefault();
            wrapper.classList.toggle("toggled");
        });

        document.getElementById("sidebarBackdrop").addEventListener('click', function() {
            wrapper.classList.remove("toggled");
        });

        document.getElementById("sidebar-close").addEventListener('click', function() {
            wrapper.classList.remove("toggled");
        });
    </script>
</body>
</html>

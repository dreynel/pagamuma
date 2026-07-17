<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
$page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RHU Admin: PAG-AMUMA</title>
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
        <div class="sidebar shadow-sm border-end bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 border-bottom position-relative">
                <button class="btn btn-sm btn-light position-absolute top-0 end-0 mt-2 me-2 d-lg-none text-muted border-0" id="sidebar-close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="logo-circle-small mx-auto mb-2 text-danger bg-danger-subtle d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-user-doctor fs-4" style="color: #c62828;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0">RHU ADMIN</h5>
            </div>
            
            <div class="user-profile text-center py-4">
                <div class="rounded-circle mb-2 profile-img border border-2 border-white shadow-sm bg-danger d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                    <span class="text-white fs-4 fw-bold">A</span>
                </div>
                <h6 class="fw-semibold mb-0 text-dark"><?= htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) ?></h6>
                <small class="text-muted">Medical Staff</small>
            </div>

            <div class="list-group list-group-flush px-3">
                <a href="index.php?page=dashboard" class="list-group-item list-group-item-action <?= $page == 'dashboard' ? 'active bg-danger text-white border-0' : '' ?> rounded mb-1">
                    <i class="fa-solid fa-users me-2"></i> Mother Directory
                </a>
                <a href="index.php?page=reviews" class="list-group-item list-group-item-action <?= $page == 'reviews' ? 'active bg-danger text-white border-0' : '' ?> rounded mb-1">
                    <i class="fa-solid fa-star me-2"></i> Manage Reviews
                </a>
                
                <div class="text-muted small fw-bold text-uppercase px-3 mt-4 pt-2 border-top mb-2">Account</div>
                
                <a href="../api/logout_action.php" class="list-group-item list-group-item-action rounded text-danger mt-1">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Log Out
                </a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-grow-1 d-flex flex-column h-100 min-vh-100">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm px-4 py-3">
                <div class="d-flex align-items-center">
                    <button class="btn btn-light" id="menu-toggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h5 class="mb-0 ms-3 fw-semibold text-dark">Management Console</h5>
                </div>
            </nav>

            <div class="container-fluid p-4 d-flex flex-column flex-grow-1 bg-light justify-content-start">
                <?php
                $allowed_pages = ['dashboard', 'mother_logs', 'reviews'];
                if (in_array($page, $allowed_pages)) {
                    $file_path = "pages/{$page}.php";
                    if (file_exists($file_path)) {
                        include $file_path;
                    } else {
                        echo "<div class='alert alert-warning'>Module not fully implemented yet.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Page not found!</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

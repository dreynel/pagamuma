<?php
session_start();
// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: mothers/index.php");
    exit;
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAG-AMUMA: Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="login-wrapper position-relative">
        
        <!-- Language Selector -->
        <div class="position-absolute top-0 end-0 p-3" style="z-index: 10;">
            <select class="form-select border-0 shadow-sm text-secondary fw-medium" id="languageSelector">
                <option value="en">English</option>
                <option value="hil">Hiligaynon</option>
                <option value="tl">Tagalog</option>
            </select>
        </div>

        <div class="container-fluid p-0">
            <div class="row g-0 vh-100">
                <!-- Left Side: Image/Branding -->
                <div class="col-lg-6 d-none d-lg-flex hero-section align-items-center justify-content-center position-relative">
                    <div class="hero-overlay"></div>
                    <div class="hero-content text-center text-white position-relative z-index-1">
                        <div class="logo-circle mb-4 mx-auto">
                            <i class="fa-solid fa-child-reaching fs-1"></i>
                        </div>
                        <h1 class="display-4 fw-bold mb-3">PAG-AMUMA</h1>
                        <p class="lead fw-light mb-4 px-5" data-i18n="hero_desc">A Web-based Health Support and Learning Platform for Early Pregnancy Women</p>
                    </div>
                </div>

                <!-- Right Side: Register Form -->
                <div class="col-lg-6 d-flex align-items-center justify-content-center form-section">
                    <div class="login-card p-5 w-100" style="max-width: 500px; max-height: 100vh; overflow-y: auto;">
                        
                        <div class="d-lg-none text-center mb-4 mt-4">
                            <div class="logo-circle-mobile mb-3 mx-auto">
                                <i class="fa-solid fa-child-reaching text-primary fs-3"></i>
                            </div>
                            <h2 class="fw-bold text-primary">PAG-AMUMA</h2>
                        </div>

                        <div class="mb-4">
                            <h3 class="fw-bold text-dark mb-2">Create an Account</h3>
                            <p class="text-muted">Sign up to access modules and track your health.</p>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger d-flex align-items-center mb-4 border-0 shadow-sm" role="alert">
                                <i class="fa-solid fa-circle-exclamation me-3 fs-5"></i>
                                <div><?php echo htmlspecialchars($error); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success d-flex align-items-center mb-4 border-0 shadow-sm" role="alert">
                                <i class="fa-solid fa-circle-check me-3 fs-5"></i>
                                <div>
                                    <?php echo $success; ?>
                                    <a href="login.php" class="alert-link ms-2">Go to Login <i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form action="api/register_action.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3 input-group-custom">
                                    <label for="first_name" class="form-label fw-medium">First Name</label>
                                    <input type="text" name="first_name" class="form-control bg-light" id="first_name" required>
                                </div>
                                <div class="col-md-6 mb-3 input-group-custom">
                                    <label for="last_name" class="form-label fw-medium">Last Name</label>
                                    <input type="text" name="last_name" class="form-control bg-light" id="last_name" required>
                                </div>
                            </div>

                            <div class="mb-3 input-group-custom">
                                <label for="email" class="form-label fw-medium" data-i18n="email_label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-regular fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control bg-light border-start-0 ps-0" id="email" required>
                                </div>
                            </div>
                            
                            <div class="mb-4 input-group-custom">
                                <label for="password" class="form-label fw-medium" data-i18n="password_label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa-solid fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control bg-light border-start-0 ps-0" id="password" required>
                                    <button class="btn btn-light bg-light border border-start-0" type="button" id="togglePassword">
                                        <i class="fa-regular fa-eye text-muted"></i>
                                    </button>
                                </div>
                                <div class="form-text mt-2 small">Must be at least 6 characters.</div>
                            </div>

                            <div class="mb-4 input-group-custom">
                                <label for="address" class="form-label fw-medium">Address (optional)</label>
                                <textarea name="address" id="address" class="form-control bg-light" rows="3" placeholder="Enter address for easier identification"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold mb-4 btn-login">
                                Create Account
                            </button>

                            <!-- Social Placeholder -->
                            <div class="text-center mb-4">
                                <p class="text-muted small fw-medium mb-3">Or sign up with</p>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="#" class="btn btn-outline-dark rounded-circle d-flex align-items-center justify-content-center" style="width:45px;height:45px;">
                                        <i class="fa-brands fa-google fs-5"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-primary rounded-circle d-flex align-items-center justify-content-center" style="width:45px;height:45px;">
                                        <i class="fa-brands fa-facebook-f fs-5"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="text-center pb-4">
                                <p class="text-muted mb-0">Already have an account? <a href="login.php" class="text-primary fw-bold text-decoration-none">Sign In</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/translations.js"></script>
    <script>
        // Password toggle on Register page
        const togglePassword = document.getElementById('togglePassword');
        if (togglePassword) {
            togglePassword.addEventListener('click', function () {
                const passwordInput = document.getElementById('password');
                const icon = this.querySelector('i');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }
    </script>
</body>
</html>


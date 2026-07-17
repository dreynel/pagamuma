<!-- c:\xampp\htdocs\pagamuma\mothers\pages\settings.php -->
<?php
// Ensure this script is included within a context where $pdo or db logic is applicable, 
// but since it's included inside mothers/index.php, we fetch from DB directly if available.
require_once __DIR__ . '/../../config/db.php';

// Fetch user profile stats
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT u.address, p.* FROM users u LEFT JOIN pregnancy_profiles p ON p.user_id = u.id WHERE u.id = ? AND u.role = 'mother'");
$stmt->execute([$user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

$expected_due_date = $profile['expected_due_date'] ?? '';
$pregnancy_start_date = $profile['pregnancy_start_date'] ?? '';
$blood_type = $profile['blood_type'] ?? '';
$medical_history = $profile['medical_history'] ?? '';

// Fetch user table data for profile_picture
$u_stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE id = ?");
$u_stmt->execute([$user_id]);
$u_data = $u_stmt->fetch(PDO::FETCH_ASSOC);
$profile_picture = $u_data['profile_picture'] ?? '';
$avatar_url = !empty($profile_picture) ? '../uploads/profile_pictures/' . htmlspecialchars($profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) . '&background=random';
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-dark mb-1" data-i18n="menu_settings">Account Settings</h2>
        <p class="text-muted">Manage your profile and pregnancy details.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Personal Information -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold text-dark mb-4">Personal Information</h5>
            <form id="personalInfoForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-medium">First Name</label>
                        <input type="text" class="form-control bg-light border-0" value="<?= htmlspecialchars($_SESSION['first_name'] ?? '') ?>" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-medium">Last Name</label>
                        <input type="text" class="form-control bg-light border-0" value="<?= htmlspecialchars($_SESSION['last_name'] ?? '') ?>" disabled>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-medium">Email Address</label>
                    <input type="email" class="form-control bg-light border-0" value="<?= htmlspecialchars($_SESSION['email'] ?? 'jane@example.com') ?>" disabled>
                    <div class="form-text small">Name and Email cannot be changed directly at the moment.</div>
                </div>
            </form>
        </div>

        <!-- Address (Patient Identification) -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold text-dark mb-4">Patient Address</h5>
            <form id="addressForm">
                <div class="mb-3">
                    <label for="address" class="form-label text-muted small fw-medium">Address</label>
                    <textarea name="address" id="address" class="form-control bg-light border-0" rows="3" placeholder="Enter address for easier identification"><?= htmlspecialchars($profile['address'] ?? '') ?></textarea>
                </div>
                <div id="addressAlert" class="alert d-none" role="alert"></div>
                <button type="button" id="saveAddressBtn" class="btn btn-primary rounded-pill px-4 py-2 fw-medium">Save Address</button>
            </form>
        </div>

        <!-- Pregnancy Profile Setup -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold text-dark mb-4">Pregnancy Profile Settings</h5>
            <form id="pregnancyProfileForm" enctype="multipart/form-data">
                
                <!-- Profile Picture -->
                <div class="mb-4 d-flex align-items-center gap-3">
                    <img src="<?= $avatar_url ?>" alt="Profile" class="rounded-circle shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                    <div>
                        <label class="form-label text-muted small fw-medium">Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control bg-light border-0" accept="image/*">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-medium">Expected Due Date (EDD)</label>
                        <input type="date" name="expected_due_date" id="expected_due_date" class="form-control bg-light border-0" value="<?= htmlspecialchars($expected_due_date) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-medium">Pregnancy Start Date (LMP)</label>
                        <input type="date" name="pregnancy_start_date" id="pregnancy_start_date" class="form-control bg-light border-0" value="<?= htmlspecialchars($pregnancy_start_date) ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-medium">Blood Type</label>
                        <select name="blood_type" id="blood_type" class="form-select bg-light border-0">
                            <option value="">Select Blood Type</option>
                            <option value="A+" <?= $blood_type == 'A+' ? 'selected' : '' ?>>A+</option>
                            <option value="A-" <?= $blood_type == 'A-' ? 'selected' : '' ?>>A-</option>
                            <option value="B+" <?= $blood_type == 'B+' ? 'selected' : '' ?>>B+</option>
                            <option value="B-" <?= $blood_type == 'B-' ? 'selected' : '' ?>>B-</option>
                            <option value="AB+" <?= $blood_type == 'AB+' ? 'selected' : '' ?>>AB+</option>
                            <option value="AB-" <?= $blood_type == 'AB-' ? 'selected' : '' ?>>AB-</option>
                            <option value="O+" <?= $blood_type == 'O+' ? 'selected' : '' ?>>O+</option>
                            <option value="O-" <?= $blood_type == 'O-' ? 'selected' : '' ?>>O-</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label text-muted small fw-medium">Medical History / Allergies</label>
                    <textarea name="medical_history" id="medical_history" class="form-control bg-light border-0" rows="3"><?= htmlspecialchars($medical_history) ?></textarea>
                </div>
                <div id="profileAlert" class="alert d-none" role="alert"></div>
                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-medium">Save Pregnancy Profile</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const addressForm = document.getElementById('addressForm');
    const addressAlert = document.getElementById('addressAlert');
    const saveAddressBtn = document.getElementById('saveAddressBtn');

    if (addressForm && saveAddressBtn) {
        saveAddressBtn.addEventListener('click', function() {
            const btn = this;
            btn.disabled = true;
            const originalText = btn.innerText;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Saving...';

            const formData = new FormData(addressForm);
            fetch('../api/save_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.innerText = originalText;

                addressAlert.classList.remove('d-none', 'alert-success', 'alert-danger');
                addressAlert.classList.add(data.success ? 'alert-success' : 'alert-danger');
                addressAlert.innerText = data.message;

                if (data.success) {
                    setTimeout(() => window.location.reload(), 900);
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerText = originalText;
                addressAlert.classList.remove('d-none', 'alert-success');
                addressAlert.classList.add('alert-danger');
                addressAlert.innerText = 'Server error occurred.';
            });
        });
    }

    const profileForm = document.getElementById('pregnancyProfileForm');
    const profileAlert = document.getElementById('profileAlert');

    // Auto-calculate Expected Due Date (EDD) from Pregnancy Start Date (LMP)
    const lmpInput = document.getElementById('pregnancy_start_date');
    const eddInput = document.getElementById('expected_due_date');
    
    lmpInput.addEventListener('change', function() {
        if (this.value) {
            const lmpDate = new Date(this.value);
            // Add 280 days (40 weeks) for typical human pregnancy
            lmpDate.setDate(lmpDate.getDate() + 280);
            
            // Format to YYYY-MM-DD
            const yyyy = lmpDate.getFullYear();
            const mm = String(lmpDate.getMonth() + 1).padStart(2, '0');
            const dd = String(lmpDate.getDate()).padStart(2, '0');
            
            // Update the expected due date but leave it changeable
            eddInput.value = `${yyyy}-${mm}-${dd}`;
        }
    });

    profileForm.addEventListener('submit', function(e) {


        e.preventDefault();
        
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...';
        
        const formData = new FormData(this);
        
        fetch('../api/save_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerText = 'Save Pregnancy Profile';
            
            profileAlert.classList.remove('d-none', 'alert-success', 'alert-danger');
            profileAlert.classList.add(data.success ? 'alert-success' : 'alert-danger');
            profileAlert.innerText = data.message;
            
            if(data.success) {
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerText = 'Save Pregnancy Profile';
            profileAlert.classList.remove('d-none', 'alert-success');
            profileAlert.classList.add('alert-danger');
            profileAlert.innerText = 'Server error occurred.';
        });
    });
});
</script>

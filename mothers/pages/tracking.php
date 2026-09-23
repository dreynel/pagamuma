<!-- c:\xampp\htdocs\pagamuma\mothers\pages\tracking.php -->
<?php
require_once __DIR__ . '/../../config/db.php';
$user_id = $_SESSION['user_id'];

// Fetch history
$stmt = $pdo->prepare("SELECT * FROM health_logs WHERE user_id = ? ORDER BY log_date DESC, created_at DESC");
$stmt->execute([$user_id]);
$logs = $stmt->fetchAll();
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-dark mb-1" data-i18n="menu_tracking">Health Tracking</h2>
        <p class="text-muted" data-i18n="tracking_subtitle">Keep a daily or weekly record of your pregnancy journey.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold text-dark mb-4" data-i18n="log_vitals_title">Log New Vitals</h5>
            <form id="healthLogForm">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-medium" data-i18n="label_date">Date</label>
                    <input type="date" name="log_date" class="form-control bg-light border-0" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-medium" data-i18n="label_weight_kg">Weight (kg)</label>
                    <input type="number" step="0.1" name="weight_kg" class="form-control bg-light border-0" placeholder="e.g. 65.5" data-i18n-placeholder="placeholder_weight">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-medium" data-i18n="label_bp">Blood Pressure</label>
                    <input type="text" name="blood_pressure" class="form-control bg-light border-0" placeholder="e.g. 120/80" data-i18n-placeholder="placeholder_bp">
                </div>
                <div class="mb-4">
                    <label class="form-label text-muted small fw-medium" data-i18n="label_symptoms">Symptoms / Notes</label>
                    <textarea name="symptoms" class="form-control bg-light border-0" rows="3" placeholder="How are you feeling today?" data-i18n-placeholder="symptoms_placeholder"></textarea>
                </div>
                <div id="logAlert" class="alert d-none" role="alert"></div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-medium" data-i18n="save_log_btn">Save Log</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h5 class="fw-bold text-dark mb-0" data-i18n="recent_history_title">Recent History</h5>
                <div class="d-flex align-items-center gap-2">
                    <a href="../api/download_mother_logs_csv.php" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm" title="Download CSV">
                        <i class="fa-solid fa-file-csv me-1"></i> <span data-i18n="btn_download_csv">Download CSV</span>
                    </a>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-sm" onclick="window.print()">
                        <i class="fa-solid fa-print me-1"></i> <span data-i18n="btn_export">Print</span>
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-muted small fw-medium" data-i18n="th_date">Date</th>
                            <th class="text-muted small fw-medium" data-i18n="th_weight">Weight</th>
                            <th class="text-muted small fw-medium" data-i18n="th_bp_short">BP</th>
                            <th class="text-muted small fw-medium" data-i18n="th_symptoms">Symptoms/Notes</th>
                            <th class="text-muted small fw-medium" data-i18n="th_prescription">Prescription</th>
                            <th class="text-muted small fw-medium text-end" data-i18n="th_action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($logs)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted" data-i18n="no_vitals_logged">No vitals logged yet. Start logging on the left!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($logs as $log): ?>
                                <tr>
                                    <td class="fw-medium text-dark" style="white-space: nowrap;"><?= date('M d, Y', strtotime($log['log_date'])) ?></td>
                                    <td><?= !empty($log['weight_kg']) ? htmlspecialchars($log['weight_kg']) . ' kg' : '--' ?></td>
                                    <td><?= !empty($log['blood_pressure']) ? htmlspecialchars($log['blood_pressure']) : '--' ?></td>
                                    <td class="text-muted small"><?= nl2br(htmlspecialchars($log['symptoms'] ?? '')) ?></td>
                                    <td class="text-muted small">
                                        <?php if (!empty($log['prescription'])): 
                                            // Determine colors
                                            $st = $log['admin_status'] ?? 'Normal';
                                            $bgClass = 'bg-info-subtle border-info text-info-emphasis';
                                            $icon = 'fa-notes-medical';
                                            if ($st === 'Critical') {
                                                $bgClass = 'bg-danger-subtle border-danger text-danger-emphasis';
                                                $icon = 'fa-triangle-exclamation';
                                            } elseif ($st === 'No Worries') {
                                                $bgClass = 'bg-success-subtle border-success text-success-emphasis';
                                                $icon = 'fa-thumbs-up';
                                            }
                                        ?>
                                            <div class="border border-opacity-25 <?= $bgClass ?> rounded p-2">
                                                <strong class="d-block mb-1 text-uppercase" style="font-size:0.75rem;"><i class="fa-solid <?= $icon ?> me-1"></i> <?= htmlspecialchars($st) ?></strong>
                                                <?= nl2br(htmlspecialchars($log['prescription'])) ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-secondary fst-italic" data-i18n="status_pending">Pending...</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-danger btn-delete-log rounded-circle shadow-sm" data-log-id="<?= $log['id'] ?>" title="Delete Log" data-i18n-title="delete_log_title">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const healthForm = document.getElementById('healthLogForm');
    const logAlert = document.getElementById('logAlert');

    healthForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...';
        
        const formData = new FormData(this);
        
        fetch('../api/save_health_log.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerText = 'Save Log';
            
            if(data.success) {
                window.location.reload();
            } else {
                logAlert.classList.remove('d-none');
                logAlert.classList.add('alert-danger');
                logAlert.innerText = data.message;
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerText = 'Save Log';
            logAlert.classList.remove('d-none');
            logAlert.classList.add('alert-danger');
            logAlert.innerText = 'Server error occurred.';
        });
    });

    // Delete Log Logic
    const deleteButtons = document.querySelectorAll('.btn-delete-log');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm('Are you sure you want to delete this health log? This action cannot be undone.')) {
                const logId = this.getAttribute('data-log-id');
                const formData = new FormData();
                formData.append('log_id', logId);

                fetch('../api/delete_health_log.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(err => {
                    alert('Server error occurred.');
                });
            }
        });
    });
});
</script>

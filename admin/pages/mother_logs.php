<?php
// c:\xampp\htdocs\pagamuma\admin\pages\mother_logs.php
require_once '../config/db.php';

$mother_id = $_GET['id'] ?? null;
$download = isset($_GET['download']) && $_GET['download'] == '1';
if (!$mother_id) {
    echo "<script>window.location.href='index.php?page=dashboard';</script>";
    exit;
}

// Prevent any accidental output before CSV headers (which can cause HTML/doctype to appear in the CSV)
if ($download) {
    while (ob_get_level() > 0) { ob_end_clean(); }
}


// Fetch mother's basic info
$stmt = $pdo->prepare("
    SELECT u.first_name, u.last_name, u.profile_picture, u.email, u.address, p.expected_due_date, p.medical_history
    FROM users u
    LEFT JOIN pregnancy_profiles p ON p.user_id = u.id
");
$stmt->execute([$mother_id]);
$mother = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mother) {
    echo "<div class='alert alert-danger'>Mother profile not found.</div>";
    exit;
}

// Fetch logs
$log_stmt = $pdo->prepare("SELECT * FROM health_logs WHERE user_id = ? ORDER BY log_date DESC, created_at DESC");
$log_stmt->execute([$mother_id]);
$logs = $log_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($download) {
    // Simple CSV download for this mother's health logs
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="mother_'.$mother_id.'_health_logs.csv"');

    $out = fopen('php://output', 'w');
    // Match the UI import template header
    fputcsv($out, ['Date','Weight','BP','Symptoms/Notes','Prescription']);

    foreach ($logs as $log) {
        // Strip any HTML that might be stored in symptoms/prescription fields
        $date = $log['log_date'] ?? '';
        $weight = $log['weight_kg'] ?? '';
        $bp = $log['blood_pressure'] ?? '';

        $symptoms = $log['symptoms'] ?? '';
        $symptoms = strip_tags((string)$symptoms);
        $symptoms = str_replace(["\r","\n"], ' ', $symptoms);

        $prescription = $log['prescription'] ?? '';
        $prescription = strip_tags((string)$prescription);
        $prescription = str_replace(["\r","\n"], ' ', $prescription);

        fputcsv($out, [$date, $weight, $bp, $symptoms, $prescription]);
    }

    fclose($out);
    exit;
}
?>


<div class="mb-4">
    <a href="index.php?page=dashboard" class="btn btn-sm btn-outline-secondary rounded-pill shadow-sm px-3 mb-3">
        <i class="fa-solid fa-arrow-left me-2"></i> Back to Directory
    </a>
        <div class="d-flex align-items-center gap-3 bg-white p-4 rounded-4 shadow-sm border-0">
        <img src="<?= !empty($mother['profile_picture']) ? '../uploads/profile_pictures/'.htmlspecialchars($mother['profile_picture']) : 'https://ui-avatars.com/api/?name='.urlencode($mother['first_name'].' '.$mother['last_name']) ?>" alt="Avatar" class="rounded-circle shadow-sm border" style="width: 80px; height: 80px; object-fit: cover;">
        <div>
            <h3 class="fw-bold text-dark mb-0"><?= htmlspecialchars($mother['first_name'].' '.$mother['last_name']) ?></h3>
            <p class="text-muted mb-0"><?= htmlspecialchars($mother['email']) ?> | EDD: <?= !empty($mother['expected_due_date']) ? date('M d, Y', strtotime($mother['expected_due_date'])) : 'Not recorded' ?></p>
            <?php if (!empty($mother['address'])): ?>
                <p class="text-muted small mb-0"><i class="fa-solid fa-location-dot me-2 text-primary"></i><?= htmlspecialchars($mother['address']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!empty($mother['medical_history'])): ?>
<div class="alert alert-warning border-warning border-opacity-50 bg-warning-subtle text-dark rounded-4 shadow-sm mb-4">
    <h6 class="fw-bold mb-2"><i class="fa-solid fa-triangle-exclamation text-warning me-2"></i> Medical History / Allergies</h6>
    <p class="mb-0 small"><?= nl2br(htmlspecialchars($mother['medical_history'])) ?></p>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 h-100 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 gap-3 flex-wrap">
        <h5 class="fw-bold text-dark mb-0">Health & Vitals Log</h5>

        <div class="d-flex align-items-center gap-2">
            <a href="index.php?page=mother_logs&id=<?= (int)$mother_id ?>&download=1" class="btn btn-sm btn-outline-secondary rounded-pill shadow-sm px-3">
                <i class="fa-solid fa-file-csv me-2"></i> Download CSV
            </a>
        </div>
    </div>

    <div class="alert alert-light border rounded-4 shadow-sm mb-4">
        <div class="d-flex flex-column gap-2">
            <div class="fw-bold text-dark">
                <i class="fa-solid fa-file-upload me-2 text-primary"></i> Bulk update from CSV (Prescription)
            </div>
            <div class="text-muted small">
                Use this format: <b>Date, Weight, BP, Symptoms/Notes, Prescription</b>. Prescription will be applied to matching logs.
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <input type="file" id="importCsvFile" accept=".csv,text/csv" class="form-control form-control-sm" style="max-width: 360px;">
                <button type="button" id="btnImportCsv" class="btn btn-sm btn-danger rounded-pill px-4 fw-medium shadow-sm">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Import CSV
                </button>
                <div class="small" id="importCsvStatus"></div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-muted small fw-medium">Date</th>
                    <th class="text-muted small fw-medium">Weight</th>
                    <th class="text-muted small fw-medium">BP</th>
                    <th class="text-muted small fw-medium">Symptoms / Notes</th>
                    <th class="text-muted small fw-medium">Prescription</th>
                    <th class="text-muted small fw-medium text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No health logs submitted by this mother yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($logs as $log): ?>
                        <tr>
                            <td class="fw-medium text-dark" style="white-space: nowrap;"><?= date('M d, Y', strtotime($log['log_date'])) ?></td>
                            <td><?= !empty($log['weight_kg']) ? htmlspecialchars($log['weight_kg']) . ' kg' : '--' ?></td>
                            <td><?= !empty($log['blood_pressure']) ? htmlspecialchars($log['blood_pressure']) : '--' ?></td>
                            <td class="text-dark small" style="max-width: 250px;"><?= nl2br(htmlspecialchars($log['symptoms'] ?? 'No symptoms reported.')) ?></td>
                            <td class="small" style="max-width: 300px;">
                                <?php if (!empty($log['prescription'])): 
                                    $st = $log['admin_status'] ?? 'Normal';
                                    $bg = 'bg-info-subtle border-info text-info-emphasis';
                                    if ($st === 'Critical') $bg = 'bg-danger-subtle border-danger text-danger-emphasis';
                                    if ($st === 'No Worries') $bg = 'bg-success-subtle border-success text-success-emphasis';
                                ?>
                                    <div class="<?= $bg ?> border border-opacity-25 rounded p-2">
                                        <strong class="d-block mb-1 text-uppercase" style="font-size:0.75rem;"><?= htmlspecialchars($st) ?></strong>
                                        <?= nl2br(htmlspecialchars($log['prescription'])) ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-danger bg-danger-subtle px-2 py-1 rounded small"><i class="fa-solid fa-circle-exclamation me-1"></i> Needs Review</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end" style="white-space: nowrap;">
                                <button class="btn btn-sm <?= empty($log['prescription']) ? 'btn-danger' : 'btn-outline-primary' ?> rounded-pill px-3 shadow-sm btn-prescribe" 
                                        data-log-id="<?= $log['id'] ?>" 
                                        data-symptoms="<?= htmlspecialchars($log['symptoms'] ?? 'None') ?>"
                                        data-status="<?= htmlspecialchars($log['admin_status'] ?? 'Normal') ?>"
                                        data-prescription="<?= htmlspecialchars($log['prescription'] ?? '') ?>"
                                        data-bs-toggle="modal" data-bs-target="#prescriptionModal">
                                    <?= empty($log['prescription']) ? 'Add Action' : 'Edit Action' ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Prescription Modal -->
<div class="modal fade" id="prescriptionModal" tabindex="-1" aria-labelledby="prescriptionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-bottom-0 bg-danger text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="prescriptionModalLabel"><i class="fa-solid fa-notes-medical me-2"></i> Manage Prescription</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        
        <div class="alert alert-secondary bg-light border-0 mb-4 rounded-3">
            <h6 class="fw-bold text-dark mb-1">Reported Symptoms:</h6>
            <p class="mb-0 small text-muted" id="modalSymptomsSummary"></p>
        </div>

        <form id="prescriptionForm">
            <input type="hidden" id="modalLogId" name="log_id">
            
            <div class="mb-3">
                <label for="adminStatus" class="form-label fw-bold text-dark">Status Definition</label>
                <select name="admin_status" id="adminStatus" class="form-select bg-light border-0">
                    <option value="Normal">Normal Handling / Advice</option>
                    <option value="Critical">Critical Intervention Needed</option>
                    <option value="No Worries">Status Normal / No Worries</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="modalPrescriptionText" class="form-label fw-bold text-dark">Doctor's Prescription / Advice</label>
                <textarea class="form-control bg-light border-0" id="modalPrescriptionText" name="prescription" rows="5" required placeholder="Type the prescription, advised rest, or vitamins here..."></textarea>
            </div>
            <div id="modalAlert" class="alert d-none py-2" role="alert"></div>
            
            <div class="d-flex gap-2">
                <button type="button" id="btnNoWorries" class="btn btn-outline-secondary w-50 rounded-pill py-2 fw-medium shadow-sm"><i class="fa-solid fa-thumbs-up me-2"></i>No Worries</button>
                <button type="submit" class="btn btn-danger w-50 rounded-pill py-2 fw-medium shadow-sm">Save Action</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bulk CSV import
    const importBtn = document.getElementById('btnImportCsv');
    const importFile = document.getElementById('importCsvFile');
    const statusEl = document.getElementById('importCsvStatus');

    if (importBtn && importFile && statusEl) {
        importBtn.addEventListener('click', async function() {
            statusEl.className = 'small text-muted';
            statusEl.textContent = 'Uploading...';

            if (!importFile.files || importFile.files.length === 0) {
                statusEl.className = 'small text-danger';
                statusEl.textContent = 'Please choose a CSV file first.';
                return;
            }

            const formData = new FormData();
            formData.append('mother_id', '<?= (int)$mother_id ?>');
            formData.append('csv_file', importFile.files[0]);

            try {
                const res = await fetch('../api/import_mother_logs_csv.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    statusEl.className = 'small text-success fw-medium';
                    statusEl.textContent = `Done. Updated: ${data.updated_rows}, Skipped: ${data.skipped_rows}. Reloading...`;
                    setTimeout(() => window.location.reload(), 900);
                } else {
                    statusEl.className = 'small text-danger';
                    statusEl.textContent = data.message || 'Import failed.';
                }
            } catch (e) {
                statusEl.className = 'small text-danger';
                statusEl.textContent = 'Server error occurred during import.';
            }
        });
    }

    // Populate Modal
    const prescribeButtons = document.querySelectorAll('.btn-prescribe');
    prescribeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('modalLogId').value = this.getAttribute('data-log-id');
            document.getElementById('modalSymptomsSummary').innerText = this.getAttribute('data-symptoms') || 'None reported';
            document.getElementById('modalPrescriptionText').value = this.getAttribute('data-prescription') || '';
            document.getElementById('adminStatus').value = this.getAttribute('data-status') || 'Normal';
            document.getElementById('modalAlert').classList.add('d-none');
        });
    });

    const rxForm = document.getElementById('prescriptionForm');
    const submitBtn = rxForm.querySelector('button[type="submit"]');
    
    document.getElementById('btnNoWorries').addEventListener('click', function() {
        document.getElementById('adminStatus').value = 'No Worries';
        document.getElementById('modalPrescriptionText').value = "No worries. Status is completely normal, keep it up!";
        submitBtn.click(); // Auto-submit
    });

    rxForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = this.querySelector('button[type="submit"]');
        const modalAlert = document.getElementById('modalAlert');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...';
        
        const formData = new FormData(this);
        
        fetch('../api/save_prescription.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerText = 'Save Prescription';
            
            modalAlert.classList.remove('d-none', 'alert-success', 'alert-danger');
            modalAlert.classList.add(data.success ? 'alert-success' : 'alert-danger');
            modalAlert.innerText = data.message;
            
            if(data.success) {
                setTimeout(() => window.location.reload(), 1000);
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerText = 'Save Prescription';
            modalAlert.classList.remove('d-none', 'alert-success');
            modalAlert.classList.add('alert-danger');
            modalAlert.innerText = 'Server error occurred.';
        });
    });
});
</script>

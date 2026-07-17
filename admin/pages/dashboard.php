<?php
// c:\xampp\htdocs\pagamuma\admin\pages\dashboard.php
require_once '../config/db.php';

$search = trim($_GET['search'] ?? '');
$sort   = $_GET['sort'] ?? 'date_desc';

// Sorting options
$orderSql = 'u.created_at DESC';
if ($sort === 'date_asc') {
    $orderSql = 'u.created_at ASC';
} elseif ($sort === 'due_date_asc') {
    $orderSql = 'p.expected_due_date ASC';
} elseif ($sort === 'due_date_desc') {
    $orderSql = 'p.expected_due_date DESC';
}

// Mothers query (with optional search)
$mothersSql = "
    SELECT u.id, u.first_name, u.last_name, u.email, u.profile_picture,
           p.expected_due_date,
           (SELECT COUNT(*) FROM health_logs hl WHERE hl.user_id = u.id AND hl.prescription IS NULL) as pending_prescriptions,
           u.address
    FROM users u
    LEFT JOIN pregnancy_profiles p ON p.user_id = u.id
    WHERE u.role = 'mother'
";

$params = [];
if ($search !== '') {
    $mothersSql .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ? OR u.address LIKE ?)";
    $like = '%' . $search . '%';
    $params = [$like, $like, $like, $like];
}

$mothersSql .= " ORDER BY " . $orderSql;

$mothersStmt = $pdo->prepare($mothersSql);
$mothersStmt->execute($params);
$mothers = $mothersStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent 5 reviews
$stmtReviews = $pdo->query("
    SELECT r.*, u.first_name, u.last_name, u.email
    FROM system_reviews r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
    LIMIT 5
");
$recent_reviews = $stmtReviews->fetchAll(PDO::FETCH_ASSOC);

function renderStars($rating) {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $html .= '<i class="fa-solid fa-star text-warning"></i>';
        } else {
            $html .= '<i class="fa-solid fa-star text-muted" style="opacity:0.3"></i>';
        }
    }
    return $html;
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-dark mb-1">Mothers Directory</h2>
        <p class="text-muted">Overview of registered pregnant mothers and pending prescriptions needing your attention.</p>

        <div class="row g-2 mt-3">
            <div class="col-md-6">
                <form method="GET" class="d-flex gap-2">
                    <input type="hidden" name="page" value="dashboard" />
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search by name, email, or address..."
                        value="<?= htmlspecialchars($search) ?>"
                    />
            </div>
            <div class="col-md-3">
                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="date_desc" <?= $sort === 'date_desc' ? 'selected' : '' ?>>Newest</option>
                    <option value="date_asc" <?= $sort === 'date_asc' ? 'selected' : '' ?>>Oldest</option>
                    <option value="due_date_asc" <?= $sort === 'due_date_asc' ? 'selected' : '' ?>>Due date (earliest)</option>
                    <option value="due_date_desc" <?= $sort === 'due_date_desc' ? 'selected' : '' ?>>Due date (latest)</option>
                </select>
                </form>
            </div>
            <div class="col-md-3 text-md-end">
                <button type="submit" class="btn btn-outline-secondary rounded-pill px-4">Search</button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Mother</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Expected Due Date</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($mothers)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No mothers registered yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($mothers as $m): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img
                                        src="<?= !empty($m['profile_picture']) ? '../uploads/profile_pictures/' . htmlspecialchars($m['profile_picture']) : 'https://ui-avatars.com/api/?name=' . urlencode($m['first_name'] . ' ' . $m['last_name']) ?>"
                                        alt="Avatar"
                                        class="rounded-circle shadow-sm"
                                        style="width: 45px; height: 45px; object-fit: cover;"
                                    >
                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?></span>
                                </div>
                            </td>
                            <td class="text-muted"><?= htmlspecialchars($m['email']) ?></td>
                            <td>
                                <?php if (!empty($m['address'])): ?>
                                    <span class="d-inline-block text-truncate" style="max-width: 260px;" title="<?= htmlspecialchars($m['address']) ?>">
                                        <?= htmlspecialchars($m['address']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted"><i>Not set</i></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= !empty($m['expected_due_date']) ? date('M d, Y', strtotime($m['expected_due_date'])) : '<i class="text-muted">Not Set</i>' ?>
                            </td>
                            <td>
                                <?php if ($m['pending_prescriptions'] > 0): ?>
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">
                                        <?= (int)$m['pending_prescriptions'] ?> Logs Need Review
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                        Up to Date
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a
                                        href="index.php?page=mother_logs&id=<?= (int)$m['id'] ?>"
                                        class="btn btn-outline-danger btn-sm rounded-pill fw-medium px-3"
                                    >
                                        View Logs
                                    </a>
                                    <a
                                        href="index.php?page=mother_logs&id=<?= (int)$m['id'] ?>&download=1"
                                        class="btn btn-outline-secondary btn-sm rounded-pill fw-medium px-3"
                                    >
                                        Download
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row mt-5 mb-4 border-top pt-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-star text-warning me-2"></i> Recent User Feedback</h4>
            <p class="text-muted small">Latest 5 reviews from the platform users.</p>
        </div>
        <a href="index.php?page=reviews" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-medium shadow-sm">View All Feedback</a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-5">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">User</th>
                    <th>Rating</th>
                    <th style="width: 45%">Feedback</th>
                    <th>Date</th>
                    <th class="pe-4">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recent_reviews)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No feedback received yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recent_reviews as $rev): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold text-dark"><?= htmlspecialchars($rev['first_name'] . ' ' . $rev['last_name']) ?></div>
                                <div class="small text-muted"><?= htmlspecialchars($rev['email']) ?></div>
                            </td>
                            <td><?= renderStars((int)$rev['rating']) ?></td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 350px;" title="<?= htmlspecialchars($rev['comment']) ?>">
                                    "<?= htmlspecialchars($rev['comment']) ?>"
                                </span>
                            </td>
                            <td>
                                <div class="small text-muted"><?= date('M d, Y', strtotime($rev['created_at'])) ?></div>
                            </td>
                            <td class="pe-4">
                                <?php if ($rev['status'] === 'approved'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success fw-medium px-3 border border-success border-opacity-25 rounded-pill">Approved</span>
                                <?php else: ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning fw-medium px-3 border border-warning border-opacity-25 rounded-pill">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


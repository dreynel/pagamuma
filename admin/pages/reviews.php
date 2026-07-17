<?php
// c:\xampp\htdocs\pagamuma\admin\pages\reviews.php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit;
}

// Fetch all reviews joined with user info
$stmt = $pdo->query("SELECT r.*, u.first_name, u.last_name, u.email FROM system_reviews r JOIN users u ON r.user_id = u.id ORDER BY r.status ASC, r.created_at DESC");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

function renderStars($rating) {
    $html = '';
    for($i=1; $i<=5; $i++) {
        if($i <= $rating) {
            $html .= '<i class="fa-solid fa-star text-warning"></i>';
        } else {
            $html .= '<i class="fa-solid fa-star text-muted" style="opacity:0.3"></i>';
        }
    }
    return $html;
}
?>

<div class="row w-100">
    <div class="col-12">
        <h4 class="fw-bold mb-4">Manage System Reviews</h4>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Mother</th>
                                <th>Rating</th>
                                <th style="width: 40%">Comment</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reviews)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No reviews found.</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($reviews as $rev): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark"><?= htmlspecialchars($rev['first_name'] . ' ' . $rev['last_name']) ?></div>
                                        <div class="small text-muted"><?= htmlspecialchars($rev['email']) ?></div>
                                    </td>
                                    <td><?= renderStars($rev['rating']) ?></td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 300px;" title="<?= htmlspecialchars($rev['comment']) ?>">
                                            <?= htmlspecialchars($rev['comment']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small text-muted"><?= date('M d, Y', strtotime($rev['created_at'])) ?></div>
                                        <div class="small text-muted"><?= date('h:i A', strtotime($rev['created_at'])) ?></div>
                                    </td>
                                    <td>
                                        <?php if ($rev['status'] === 'approved'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success fw-medium px-3 border border-success border-opacity-25 rounded-pill">Approved</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning fw-medium px-3 border border-warning border-opacity-25 rounded-pill">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <?php if ($rev['status'] === 'pending'): ?>
                                            <button class="btn btn-sm btn-outline-success rounded-pill fw-medium px-3 me-2" onclick="reviewAction(<?= $rev['id'] ?>, 'approve')">
                                                <i class="fa-solid fa-check"></i> Approve
                                            </button>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="reviewAction(<?= $rev['id'] ?>, 'delete')">
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
</div>

<script>
function reviewAction(id, action) {
    let confirmMsg = action === 'approve' ? 'Approve this review for public display?' : 'Delete this review permanently?';
    if (!confirm(confirmMsg)) return;

    const formData = new FormData();
    formData.append('review_id', id);
    formData.append('action', action);

    fetch('../api/admin_review_action.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => alert('Network error!'));
}
</script>

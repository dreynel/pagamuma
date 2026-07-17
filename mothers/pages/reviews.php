<?php
// c:\xampp\htdocs\pagamuma\mothers\pages\reviews.php
if (!isset($_SESSION['user_id'])) {
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM system_reviews WHERE user_id = ?");
$stmt->execute([$user_id]);
$review = $stmt->fetch(PDO::FETCH_ASSOC);

$has_review = $review ? true : false;
$rating = $has_review ? $review['rating'] : 5;
$comment = $has_review ? $review['comment'] : '';
$status = $has_review ? $review['status'] : '';
?>
<div class="row w-100">
    <div class="col-lg-8 mx-auto">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-dark mb-2">Platform Feedback</h2>
            <p class="text-muted">Help us improve PAG-AMUMA by sharing your experience.</p>
        </div>

        <?php if ($has_review): ?>
            <div class="alert <?= $status === 'approved' ? 'alert-success' : 'alert-warning' ?> d-flex align-items-center mb-4" role="alert">
                <i class="fa-solid <?= $status === 'approved' ? 'fa-circle-check' : 'fa-clock' ?> fs-4 me-3"></i>
                <div>
                    <strong>Status:</strong> <?= ucfirst($status) ?>
                    <?php if ($status === 'pending'): ?>
                        <div class="small">Your review is currently pending admin approval before it will be shown publicly.</div>
                    <?php else: ?>
                        <div class="small">Your review is public! Thank you for sharing your journey.</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                <form id="reviewForm">
                    <div class="mb-4 text-center">
                        <label class="form-label fw-bold d-block mb-3">Overall Rating</label>
                        <div class="star-rating fs-2" style="color: #ddd;">
                            <!-- Data attribute to hold current rating -->
                            <input type="hidden" name="rating" id="ratingValue" value="<?= htmlspecialchars($rating) ?>">
                            <i class="fa-solid fa-star cursor-pointer star-icon" data-value="1"></i>
                            <i class="fa-solid fa-star cursor-pointer star-icon" data-value="2"></i>
                            <i class="fa-solid fa-star cursor-pointer star-icon" data-value="3"></i>
                            <i class="fa-solid fa-star cursor-pointer star-icon" data-value="4"></i>
                            <i class="fa-solid fa-star cursor-pointer star-icon" data-value="5"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="comment" class="form-label fw-bold">Your Experience</label>
                        <textarea class="form-control bg-light border-0 py-3" id="comment" name="comment" rows="5" placeholder="Tell us how PAG-AMUMA has guided your pregnancy journey..."><?= htmlspecialchars($comment) ?></textarea>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm" id="submitReviewBtn">
                            <?= $has_review ? 'Update Review' : 'Submit Review' ?>
                        </button>
                    </div>
                    
                    <div id="reviewAlert" class="mt-3"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star-icon');
        const ratingInput = document.getElementById('ratingValue');
        
        function updateStars(val) {
            stars.forEach(star => {
                if (parseInt(star.getAttribute('data-value')) <= val) {
                    star.style.color = '#ffc107';
                } else {
                    star.style.color = '#ddd';
                }
            });
        }
        
        // Initialize
        updateStars(ratingInput.value);

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const val = this.getAttribute('data-value');
                ratingInput.value = val;
                updateStars(val);
            });
        });

        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('submitReviewBtn');
            const alertBox = document.getElementById('reviewAlert');
            const formData = new FormData(this);
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...';
            
            fetch('../api/submit_review.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<?= $has_review ? "Update Review" : "Submit Review" ?>';
                
                if (data.success) {
                    alertBox.innerHTML = '<div class="alert alert-success text-center mb-0 border-0 rounded-pilled"><i class="fa-solid fa-check-circle me-2"></i>' + data.message + '</div>';
                    // Reload after 2 seconds to show pending state
                    setTimeout(() => location.reload(), 2000);
                } else {
                    alertBox.innerHTML = '<div class="alert alert-danger text-center mb-0 border-0 rounded-pilled"><i class="fa-solid fa-triangle-exclamation me-2"></i>' + data.message + '</div>';
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<?= $has_review ? "Update Review" : "Submit Review" ?>';
                alertBox.innerHTML = '<div class="alert alert-danger mb-0"><i class="fa-solid fa-times-circle me-2"></i> Connection error.</div>';
            });
        });
    });
</script>

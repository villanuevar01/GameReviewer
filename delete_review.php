
<?php
session_start();
require_once __DIR__ . '/config/database.php';

// Development-only test user.
// Replace with authenticated session later.
$testUserID = 1;

// Only accept POST requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

// Verify CSRF token.
$token = $_POST['csrf_token'] ?? '';

if (
    !is_string($token) ||
    !isset($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $token)
) {
    http_response_code(403);
    exit('Invalid security token.');
}

// Validate review ID.
$reviewID = filter_var(
    $_POST['review_id'] ?? null,
    FILTER_VALIDATE_INT
);

if (!$reviewID || $reviewID < 1) {
    http_response_code(400);
    exit('Invalid review ID.');
}

// Delete only a review belonging to the test user.
$stmt = $pdo->prepare(
    "DELETE FROM Reviews
     WHERE ReviewID = ? AND UserID = ?"
);

$stmt->execute([$reviewID, $testUserID]);

header('Location: reviews.php?deleted=1');
exit;

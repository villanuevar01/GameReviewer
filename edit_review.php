
<?php
require_once __DIR__ . '/config/database.php';

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// DEVELOPMENT ONLY: replace with authenticated session later.
$testUserID = 1;

$reviewID = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$reviewID || $reviewID < 1) {
    http_response_code(400);
    exit('Invalid review ID.');
}

// Only retrieve a review belonging to the test user.
$stmt = $pdo->prepare(
    "SELECT ReviewID, GameID, Rating, ReviewText
     FROM Reviews
     WHERE ReviewID = ? AND UserID = ?"
);

$stmt->execute([$reviewID, $testUserID]);
$review = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$review) {
    http_response_code(404);
    exit('Review not found.');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        http_response_code(403);
        exit('Invalid security token.');
    }
    $rating = filter_var(
        $_POST['rating'] ?? null,
        FILTER_VALIDATE_INT
    );

    $reviewText = trim($_POST['review_text'] ?? '');

    if ($rating === false || $rating === null ||
        $rating < 1 || $rating > 5) {
        $errors[] = 'Rating must be between 1 and 5.';
    }

    if (strlen($reviewText) > 2000) {
        $errors[] = 'Review must be 2000 characters or fewer.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            "UPDATE Reviews
             SET Rating = ?, ReviewText = ?
             WHERE ReviewID = ? AND UserID = ?"
        );

        $stmt->execute([
            $rating,
            $reviewText,
            $reviewID,
            $testUserID
        ]);

        header('Location: reviews.php?updated=1');
        exit;
    }

    $review['Rating'] = $_POST['rating'] ?? '';
    $review['ReviewText'] = $reviewText;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review - GameReviewer</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>GameReviewer</h1>
    <p>Edit your game review.</p>
    <nav>
        <a href="index.php">Home</a>
        <a href="reviews.php">Reviews</a>
    </nav>
</header>

<main>
    <h2>Edit Review</h2>

    <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endforeach; ?>

    <form method="POST">
        <input
    type="hidden"
    name="csrf_token"
    value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
>
        <div>
            <label for="rating">Rating (1–5):</label>
            <input
                type="number"
                id="rating"
                name="rating"
                min="1"
                max="5"
                value="<?= htmlspecialchars((string)$review['Rating'], ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div>
            <label for="review_text">Review:</label>
            <textarea
                id="review_text"
                name="review_text"
                maxlength="2000"
                rows="5"
            ><?= htmlspecialchars($review['ReviewText'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <button type="submit">Save Changes</button>
        <a href="reviews.php">Cancel</a>
    </form>
</main>

</body>
</html>


<?php
require_once __DIR__ . '/config/database.php';

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Development-only test reviewer.
// Replace with authenticated user sessions later.
$testUserID = 1;

$games = $pdo->query(
    "SELECT GameID, Title FROM Games ORDER BY Title"
)->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        http_response_code(403);
        exit('Invalid security token.');
    }
    $gameID = filter_var(
        $_POST['game_id'] ?? null,
        FILTER_VALIDATE_INT
    );

    $rating = filter_var(
        $_POST['rating'] ?? null,
        FILTER_VALIDATE_INT
    );

    $reviewText = trim($_POST['review_text'] ?? '');

    $validGameIDs = array_map(
        'intval',
        array_column($games, 'GameID')
    );

    if (!$gameID || !in_array($gameID, $validGameIDs, true)) {
        $errors[] = 'Please select a valid game.';
    }

    if ($rating === false || $rating < 1 || $rating > 5) {
        $errors[] = 'Rating must be between 1 and 5.';
    }

    if (strlen($reviewText) > 2000) {
        $errors[] = 'Review must be 2000 characters or fewer.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            "INSERT INTO Reviews
             (UserID, GameID, Rating, ReviewText)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->execute([
            $testUserID,
            $gameID,
            $rating,
            $reviewText
        ]);

        header('Location: reviews.php?success=1');
        exit;
    }
}

$success = isset($_GET['success']);
// Retrieve submitted reviews from MariaDB
$reviews = $pdo->query(
    "SELECT
        Reviews.ReviewID,
        Users.Username,
        Games.Title,
        Reviews.Rating,
        Reviews.ReviewText,
        Reviews.ReviewDate
     FROM Reviews
     JOIN Users ON Reviews.UserID = Users.UserID
     JOIN Games ON Reviews.GameID = Games.GameID
     ORDER BY Reviews.ReviewDate DESC, Reviews.ReviewID DESC"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Reviews - GameReviewer</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>GameReviewer</h1>
    <p>Share your thoughts about your favorite games.</p>
    <nav>
        <a href="index.php">Home</a>
        <a href="reviews.php">Reviews</a>
    </nav>
</header>

<main>
    <h2>Write a Review</h2>

    <?php if ($success): ?>
        <p>Review submitted successfully!</p>
    <?php endif; ?>

    <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endforeach; ?>

    <form method="POST" action="reviews.php">
        <input
    type="hidden"
    name="csrf_token"
    value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
>
        <label for="game_id">Choose a game:</label>
        <select name="game_id" id="game_id" required>
            <option value="">Select a game</option>
            <?php foreach ($games as $game): ?>
                <option value="<?= (int)$game['GameID'] ?>">
                    <?= htmlspecialchars($game['Title'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="rating">Rating (1–5):</label>
        <input
            type="number"
            name="rating"
            id="rating"
            min="1"
            max="5"
            required
        >

        <label for="review_text">Your Review:</label>
        <textarea
            name="review_text"
            id="review_text"
            maxlength="2000"
            rows="5"
        ></textarea>

        <button type="submit">Submit Review</button>
    </form>
    <section>
    <h2>Game Reviews</h2>

    <?php if (empty($reviews)): ?>
        <p>No reviews have been submitted yet.</p>
    <?php else: ?>

        <?php foreach ($reviews as $review): ?>
            <article>
                <h3><?= htmlspecialchars($review['Title'], ENT_QUOTES, 'UTF-8') ?></h3>

                <p>
                    Reviewed by:
                    <?= htmlspecialchars($review['Username'], ENT_QUOTES, 'UTF-8') ?>
                </p>

                <p>
                    Rating: <?= (int)$review['Rating'] ?>/5
                </p>

                <p>
                    <?= nl2br(htmlspecialchars($review['ReviewText'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
                </p>

                <p>
                    Date:
                    <?= htmlspecialchars($review['ReviewDate'], ENT_QUOTES, 'UTF-8') ?>
                </p>
            
                
<?php if ((int)$testUserID === 1): ?>
    <p>
        <a href="edit_review.php?id=<?= (int)$review['ReviewID'] ?>">
            Edit Review
        </a>
    </p>

    <form method="POST"
          action="delete_review.php"
          onsubmit="return confirm('Are you sure you want to delete this review?');">

        <input type="hidden"
               name="review_id"
               value="<?= (int)$review['ReviewID'] ?>">

        <input type="hidden"
               name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

        <button type="submit">Delete Review</button>
    </form>
<?php endif; ?>


                <hr>
            </article>
        <?php endforeach; ?>

    <?php endif; ?>
</section>
</main>

</body>
</html>



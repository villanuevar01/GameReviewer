<?php
require_once __DIR__ . '/config/database.php';

$stmt = $pdo->query("SELECT Title, Description, Price FROM Games");
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GameReviewer</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>  
<header>
    <h1>GameReviewer</h1>
    <p>Discover, explore, and review your favorite video games.</p>
</header>
    <h2>Available Games</h2>

    <div class="game-grid">
    <?php foreach ($games as $game): ?>
    <div class="game-card">
        <h3><?= htmlspecialchars($game['Title']) ?></h3>
        <p><?= htmlspecialchars($game['Description'] ?? '') ?></p>
        <p>Price: $<?= htmlspecialchars($game['Price']) ?></p>
    </div>
    <?php endforeach; ?>
    </div>
</body>
</html>

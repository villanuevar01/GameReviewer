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
    <h1>Welcome to GameReviewer!</h1>
    <h2>Available Games</h2>

    <?php foreach ($games as $game): ?>
        <h3><?= htmlspecialchars($game['Title']) ?></h3>
        <p><?= htmlspecialchars($game['Description'] ?? '') ?></p>
        <p>Price: $<?= htmlspecialchars($game['Price']) ?></p>
        <hr>
    <?php endforeach; ?>
</body>
</html>

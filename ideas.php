<?php
session_start();
require 'database.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$authorized_users = ["Zasir", "Axel", "Chris"];

if (!in_array($username, $authorized_users)) {
    die("Accès refusé : utilisateur non autorisé.");
}

$stmt = $pdo->query("SELECT * FROM idea ORDER BY created_date DESC");
$ideas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ideaId = $_POST['idea_id'];
    $voteType = $_POST['vote_type'];

    $stmt = $pdo->prepare("SELECT * FROM vote WHERE id_idea = ? AND voter_name = ?");
    $stmt->execute([$ideaId, $username]);
    $existingVote = $stmt->fetch();

    if ($existingVote) {
        if ($existingVote['vote_type'] !== $voteType) {
            $stmt = $pdo->prepare("UPDATE vote SET vote_type = ? WHERE id_idea = ? AND voter_name = ?");
            $stmt->execute([$voteType, $ideaId, $username]);

            if ($voteType === 'positive') {
                $pdo->prepare("UPDATE idea SET vote_positive = vote_positive + 1, vote_negative = vote_negative - 1 WHERE id = ?")->execute([$ideaId]);
            } else {
                $pdo->prepare("UPDATE idea SET vote_negative = vote_negative + 1, vote_positive = vote_positive - 1 WHERE id = ?")->execute([$ideaId]);
            }
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO vote (id_idea, voter_name, vote_type) VALUES (?, ?, ?)");
        $stmt->execute([$ideaId, $username, $voteType]);

        if ($voteType === 'positive') {
            $pdo->prepare("UPDATE idea SET vote_positive = vote_positive + 1 WHERE id = ?")->execute([$ideaId]);
        } else {
            $pdo->prepare("UPDATE idea SET vote_negative = vote_negative + 1 WHERE id = ?")->execute([$ideaId]);
        }
    }

    header("Location: ideas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des idées</title>
</head>
<body>
    <h2>Liste des idées</h2>
    <?php foreach ($ideas as $idea) : ?>
        <div>
            <h3><?= htmlspecialchars($idea['title']) ?></h3>
            <p><?= htmlspecialchars($idea['description']) ?></p>
            <p>Par : <?= htmlspecialchars($idea['author']) ?>, le <?= htmlspecialchars($idea['created_date']) ?></p>
            <p>Votes positifs : <?= $idea['vote_positive'] ?> | Votes négatifs : <?= $idea['vote_negative'] ?></p>

            <form method="post" action="">
                <input type="hidden" name="idea_id" value="<?= $idea['id'] ?>">
                <button type="submit" name="vote_type" value="positive">Vote positif</button>
                <button type="submit" name="vote_type" value="negative">Vote négatif</button>
            </form>
        </div>
        <hr>
    <?php endforeach; ?>
    
    <p><a href="submit_idea.php">Soumettre une nouvelle idée</a> | <a href="logout.php">Déconnexion</a></p>
</body>
</html>

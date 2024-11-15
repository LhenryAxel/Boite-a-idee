<?php
session_start();
require 'database.php';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$authorized_users = ["Zasir", "Axel", "Chris", "Renaud"]; // List of allowed users

if (!in_array($username, $authorized_users)) {
    die("Accès refusé : utilisateur non autorisé.");
}

// Retrieve all ideas
$stmt = $pdo->query("SELECT * FROM idea ORDER BY created_date DESC");
$ideas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ideaId = $_POST['idea_id'];
    $voteType = $_POST['vote_type'];

    // Check if the user already voted
    $stmt = $pdo->prepare("SELECT * FROM vote WHERE id_idea = ? AND voter_name = ?");
    $stmt->execute([$ideaId, $username]);
    $existingVote = $stmt->fetch();

    if ($existingVote) {
        if ($existingVote['vote_type'] !== $voteType) {
            $stmt = $pdo->prepare("UPDATE vote SET vote_type = ? WHERE id_idea = ? AND voter_name = ?");
            $stmt->execute([$voteType, $ideaId, $username]);

            // Update vote counts
            if ($voteType === 'positive') {
                $pdo->prepare("UPDATE idea SET vote_positive = vote_positive + 1, vote_negative = vote_negative - 1 WHERE id = ?")->execute([$ideaId]);
            } else {
                $pdo->prepare("UPDATE idea SET vote_negative = vote_negative + 1, vote_positive = vote_positive - 1 WHERE id = ?")->execute([$ideaId]);
            }
        }
    } else {
        // If no previous vote
        $stmt = $pdo->prepare("INSERT INTO vote (id_idea, voter_name, vote_type) VALUES (?, ?, ?)");
        $stmt->execute([$ideaId, $username, $voteType]);

        if ($voteType === 'positive') {
            $pdo->prepare("UPDATE idea SET vote_positive = vote_positive + 1 WHERE id = ?")->execute([$ideaId]);
        } else {
            $pdo->prepare("UPDATE idea SET vote_negative = vote_negative + 1 WHERE id = ?")->execute([$ideaId]);
        }
    }

    // Refresh the page
    header("Location: ideas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head><center>
    <meta charset="UTF-8">
    <title>Liste des idées</title>
    <script>
        function showIdeaDetails(ideaId) {
            const ideas = document.querySelectorAll('.idea-details');
            ideas.forEach(idea => {
                if (idea.dataset.id === ideaId) {
                    idea.style.display = 'block';
                } else {
                    idea.style.display = 'none';
                }
            });
        }
    </script>
</head>
<body>
    <h2>Liste des idées</h2>
    <select onchange="showIdeaDetails(this.value)">
        <option value="">Sélectionnez une idée</option>
        <?php foreach ($ideas as $idea) : ?>
            <option value="<?= $idea['id'] ?>"><?= htmlspecialchars($idea['title']) ?></option>
        <?php endforeach; ?>
    </select>

    <?php foreach ($ideas as $idea) : ?>
        <div class="idea-details" data-id="<?= $idea['id'] ?>" style="display: none;">
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
    <?php endforeach; ?>
    
    <div>
        <a href="submit.php">Soumettre une nouvelle idée</a> | 
        <a href="logout.php">Déconnexion</a>
    </div>
</body>
</html>
</center>
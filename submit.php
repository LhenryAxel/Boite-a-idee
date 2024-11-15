<?php
session_start();
require 'database.php';

// If user is not connected then redirect
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author = $_SESSION['username']; // Get the logged in user as the author

    // Insert new idea
    $stmt = $pdo->prepare("INSERT INTO idea (title, description, author) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $author]);

    echo "Idée soumise avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Soumettre une nouvelle idée</title>
</head>
<body>
    <h2>Soumettre une nouvelle idée</h2>
    <form method="post" action="">
        <label>Titre: <input type="text" name="title" required></label><br>
        <label>Description: <textarea name="description" required></textarea></label><br>
        <button type="submit">Soumettre</button>
    </form>
    
    <p><a href="ideas.php">Voir les idées</a> | <a href="logout.php">Déconnexion</a></p>
</body>
</html>
<?php
session_start();

$users = ["Zasir", "Axel", "Chris"]; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];

    if (in_array($username, $users)) {
        $_SESSION['username'] = $username;
        header("Location: submit.php");
        exit;
    } else {
        $error_message = "Nom non reconnu.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <?php if (!empty($error_message)) : ?>
        <p style="color: red;"><?= $error_message; ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>Nom : <input type="text" name="username" required></label><br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>

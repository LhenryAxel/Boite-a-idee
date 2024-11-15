<?php
$host = 'localhost';
$dbname = 'boite_a_idee';
$username = 'Zaxel';
$password = 'YzyKfNPNfmR)mPlj';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

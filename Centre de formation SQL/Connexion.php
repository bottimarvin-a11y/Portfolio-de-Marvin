<?php
// Configuration de la connexion pour AMPPS
$host = "localhost";
$user = "root";
$pass = "mysql"; // Mot de passe par défaut AMPPS
$db = "centre_formation";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Erreur de connexion à la base '$db' : " . mysqli_connect_error());
}
?>
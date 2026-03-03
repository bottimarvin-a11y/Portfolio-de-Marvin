<?php
@include("connexion.php");

// Vérifie la connexion
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Récupération sécurisée des données
$a = mysqli_real_escape_string($conn, $_POST["login"]);
$b = mysqli_real_escape_string($conn, $_POST["profil"]);
$c = mysqli_real_escape_string($conn, $_POST["mdp"]);

// Requête d'insertion
$reql = "INSERT INTO users (login, profil, mdp) VALUES ('$a', '$b', '$c')";
$rl = mysqli_query($conn, $reql);

if ($rl) {
    echo "<center><p>Enregistrement OK</p></center>";
    echo '<center><a href="index.php">Retour</a></center>';
} else {
    echo "<center><p>Erreur d'enregistrement : " . mysqli_error($conn) . "</p></center>";
    echo '<center><a href="enregistrer.html">Retour</a></center>';
}

mysqli_close($conn);
?>
<?php
// Active l'affichage des erreurs pour le débogage
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

@include("connexion.php");

// Vérifie si la connexion a réussi
if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

// Récupère et échappe les entrées pour éviter les injections SQL
$login = isset($_POST["login"]) ? mysqli_real_escape_string($conn, $_POST["login"]) : "";
$profil = isset($_POST["profil"]) ? mysqli_real_escape_string($conn, $_POST["profil"]) : "";
$mdp = isset($_POST["mdp"]) ? mysqli_real_escape_string($conn, $_POST["mdp"]) : "";

try {
    // Insertion avec les noms de colonnes explicites
    $reql = "INSERT INTO users (login, profil, mdp) VALUES ('$login', '$profil', '$mdp')";

    if (mysqli_query($conn, $reql)) {
        echo "<center><p>Enregistrement OK</p></center>";
        echo '<center><a href="index.php">Retour</a></center>';
    } else {
        // En cas d'erreur de requête (théoriquement attrapé par le catch grâce au report mode, mais sécurité supplémentaire)
        echo "<center><p>Erreur d'enregistrement : " . mysqli_error($conn) . "</p></center>";
        echo '<center><a href="enregistrer.html">Retour</a></center>';
    }
} catch (mysqli_sql_exception $e) {
    // Affiche l'erreur spécifique
    echo "<center><p>Erreur SQL : " . $e->getMessage() . "</p></center>";
    echo '<center><a href="enregistrer.html">Retour</a></center>';
}

mysqli_close($conn);
?>
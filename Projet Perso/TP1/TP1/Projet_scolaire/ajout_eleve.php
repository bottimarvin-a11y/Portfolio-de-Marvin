<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
@include("connexion.php");

// Récupération des données du formulaire
$nom = $_POST["nom"];
$adresse = $_POST["adresse"];
$tel = $_POST["tel"];

// On prépare la requête pour insérer dans la table 'eleves'
// Colonnes supposées : nom, adresse, num_telephone (basé sur l'affichage demandé plus tard)
// Note : 'num' est probablement auto-incrémenté

try {
    // J'utilise des noms de variables simples comme dans le reste du projet
    $requete = "INSERT INTO eleves (nom, adresse, num_telephone) VALUES ('$nom', '$adresse', '$tel')";

    // Exécution
    mysqli_query($conn, $requete);

    // Message de succès simple
    echo "<center><h3>Succès !</h3></center>";
    echo "<center><p>L'élève a bien été ajouté.</p></center>";
    echo '<center><a href="ajout_eleve.html">Ajouter un autre</a></center><br>';
    echo '<center><a href="menu.html">Retour au menu</a></center>';

} catch (Exception $e) {
    echo "<center><h3 style='color:red'>Erreur</h3></center>";
    echo "<center><p>Impossible d'ajouter l'élève : " . $e->getMessage() . "</p></center>";
    echo '<center><a href="ajout_eleve.html">Réessayer</a></center>';
}

mysqli_close($conn);
?>
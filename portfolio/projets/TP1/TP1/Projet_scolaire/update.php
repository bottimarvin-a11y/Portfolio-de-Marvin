<?php
include("connexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num = mysqli_real_escape_string($conn, $_POST['num']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
    $num_telephone = mysqli_real_escape_string($conn, $_POST['num_telephone']);

    // On tente la mise à jour sur id ou num selon la structure existante
    $requete = "UPDATE eleves SET nom='$nom', adresse='$adresse', num_telephone='$num_telephone' WHERE id='$num' OR num='$num'";
    $resultat = mysqli_query($conn, $requete);

    if ($resultat) {
        echo "<script>alert('Modification effectuée avec succès !'); window.location.href='menu.html';</script>";
    } else {
        echo "Échec de la modification : " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
<?php
// D'après vos instructions : "1- Créer la base de données : projet_scolaire"
$conn = mysqli_connect("localhost", "root", "mysql", "projet_scolaire");

if (!$conn) {
    die("Erreur de connexion à la base 'projet_scolaire' : " . mysqli_connect_error());
}
?>
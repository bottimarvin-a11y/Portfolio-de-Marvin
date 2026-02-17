<?php
// Ta base s'appelle scolarite_test d'après l'image
$conn = mysqli_connect("localhost", "root", "mysql", "scolarite");

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}
?>
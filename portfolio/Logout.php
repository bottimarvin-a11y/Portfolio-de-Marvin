<?php
session_start();
$_SESSION['nom'] = $_POST['t1'];
$_SESSION['prenom'] = $_POST['t2'];

echo " La session est ouvert au nom de     :  <br>";
    echo " $_SESSION[nom]";
    echo " $_SESSION[prenom]";

    echo '<h3><a href="page3.php">TRANSMETTRE</a></h3>';
?>
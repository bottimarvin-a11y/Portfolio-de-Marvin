<?php
    session_start();
    
    echo "La session est ouvert au nom de:       <br>";
    echo " $_SESSION[nom] $_SESSION[prenom]";
    
echo '<h3><a href="logout.php">Se deconnecter</a></h3>';
?>
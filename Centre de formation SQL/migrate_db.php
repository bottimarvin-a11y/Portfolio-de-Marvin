<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("Connexion.php");

function ensure_column($conn, $table, $column, $definition)
{
    $result = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    if (mysqli_num_rows($result) == 0) {
        $sql = "ALTER TABLE `$table` ADD `$column` $definition";
        if (mysqli_query($conn, $sql)) {
            echo "✅ Colonne '$column' ajoutée à la table '$table'.<br>";
        } else {
            echo "❌ Erreur lors de l'ajout de '$column' : " . mysqli_error($conn) . "<br>";
        }
    } else {
        // Force update the type/size
        $sql = "ALTER TABLE `$table` MODIFY `$column` $definition";
        if (mysqli_query($conn, $sql)) {
            echo "✅ Colonne '$column' mise à jour (taille/type) dans '$table'.<br>";
        } else {
            echo "❌ Erreur lors de la mise à jour de '$column' : " . mysqli_error($conn) . "<br>";
        }
    }
}

echo "<h3>Mise à jour de la base de données...</h3>";

// Mise à jour de la table 'users'
ensure_column($conn, "users", "nom", "VARCHAR(255)");
ensure_column($conn, "users", "prenom", "VARCHAR(255)");
ensure_column($conn, "users", "photo", "VARCHAR(255)");

// S'assurer que les colonnes dans 'etudiants' et 'formateurs' acceptent des valeurs vides
echo "<h4>Ajustement des tables Étudiants et Formateurs...</h4>";

mysqli_query($conn, "ALTER TABLE etudiants MODIFY email VARCHAR(100) NULL, MODIFY telephone VARCHAR(20) NULL, MODIFY date_naissance DATE NULL");
mysqli_query($conn, "ALTER TABLE formateurs MODIFY email VARCHAR(100) NULL, MODIFY specialite VARCHAR(100) NULL");

echo "✅ Tables ajustées pour permettre les champs optionnels.<br>";

echo "<br><b>Terminé !</b> <a href='login.php'>Cliquez ici pour vous connecter</a>";

mysqli_close($conn);
?>
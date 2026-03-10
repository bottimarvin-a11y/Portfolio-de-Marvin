<?php
include("Connexion.php");
echo "<h3>Schéma de la base de données</h3>";

$tables = ['users', 'etudiants', 'formateurs', 'formations', 'modules', 'notes', 'salles'];

foreach ($tables as $table) {
    echo "<h4>Table: $table</h4>";
    $res = mysqli_query($conn, "DESCRIBE $table");
    if ($res) {
        echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<tr><td>" . implode("</td><td>", $row) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "Erreur : " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>
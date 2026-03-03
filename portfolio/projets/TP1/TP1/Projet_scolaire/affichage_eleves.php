<html>

<head>
    <title>Liste des Élèves</title>
    <!-- On réutilise le CSS du dashboard pour que ce soit joli -->
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .container {
            padding: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Liste des Élèves</h1>

        <?php
        @include("connexion.php");

        // On récupère tous les élèves
        $requete = "SELECT * FROM eleves";
        $resultat = mysqli_query($conn, $requete);

        // On affiche le nombre de résultats (optionnel mais demandé dans le TP initial)
        $nb = mysqli_num_rows($resultat);
        echo "<p>Nombre d'élèves inscrits : <strong>$nb</strong></p>";
        ?>

        <center>
            <table>
                <tr>
                    <th>Code élève</th>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Téléphone</th>
                </tr>

                <?php
                while ($enreg = mysqli_fetch_array($resultat)) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $enreg["id"] ?? $enreg["num"]; // Gestion "id" ou "num" selon la base ?>
                        </td>
                        <td>
                            <?php echo $enreg["nom"]; ?>
                        </td>
                        <td>
                            <?php echo $enreg["adresse"]; ?>
                        </td>
                        <td>
                            <?php echo $enreg["num_telephone"]; ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </center>

        <br><br>
        <a href="menu.html" class="action-btn" style="text-decoration:none;">Retour au menu</a>

        <?php
        mysqli_close($conn);
        ?>
    </div>
</body>

</html>
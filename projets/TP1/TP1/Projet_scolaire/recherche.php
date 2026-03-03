<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rechercher et Modifier un Élève</title>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <style>
        .search-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .style1 {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .style2 {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .style2:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <div class="search-container">
        <h1>Recherche d'élève</h1>
        <form action="recherche.php" method="POST">
            Saisir le numéro de l'élève :
            <input type="text" name="id_recherche" required>
            <input type="submit" value="Rechercher" class="style2">
        </form>

        <?php
        if (isset($_POST['id_recherche'])) {
            include("connexion.php");
            $id = mysqli_real_escape_string($conn, $_POST['id_recherche']);
            $requete = "SELECT * FROM eleves WHERE id='$id' OR num='$id'";
            $resultat = mysqli_query($conn, $requete);

            if ($enreg = mysqli_fetch_array($resultat)) {
                ?>
                <hr>
                <h2>Modifier l'élève</h2>
                <form action="update.php" method="POST">
                    <input type="hidden" name="num" value="<?php echo $enreg['id'] ?? $enreg['num']; ?>">
                    Nom : <input type="text" name="nom" value="<?php echo $enreg['nom']; ?>" class="style1">
                    Adresse : <input type="text" name="adresse" value="<?php echo $enreg['adresse']; ?>" class="style1">
                    Téléphone : <input type="text" name="num_telephone" value="<?php echo $enreg['num_telephone']; ?>"
                        class="style1">
                    <input type="submit" value="Mettre à jour" class="style2">
                </form>
                <?php
            } else {
                echo "<p style='color:red;'>Aucun élève trouvé avec le numéro $id.</p>";
            }
            mysqli_close($conn);
        }
        ?>
        <br>
        <a href="menu.html">Retour au menu</a>
    </div>
</body>

</html>
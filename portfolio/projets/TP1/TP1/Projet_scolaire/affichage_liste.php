<html>

<head>
    <meta charset="UTF-8">
    <title>Recherche Élève</title>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <style>
        body {
            background-color: #pink;
            /* Demandé dans le TP: "background-color: pink;" - je garde 'pink' mais en hex #ffc0cb pour être propre ou juste style inline comme demandé */
            background-color: pink;
            text-align: center;
            padding-top: 50px;
        }

        select {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <h1>Sélectionner un élève</h1>

    <?php
    @include("connexion.php");
    $requete = "SELECT * FROM eleves";
    $resultat = mysqli_query($conn, $requete);
    ?>

    <form>
        <select name="eleve" style="text-align: center; font-weight: bold;">
            <option value="">-- Choisissez un élève --</option>
            <?php
            while ($enre = mysqli_fetch_array($resultat)) {
                ?>
                <!-- Utilisation de utf8_encode si nécessaire pour les accents, sinon direct -->
                <option value="<?php echo $enre['id'] ?? $enre['num']; ?>">
                    <?php echo $enre['nom']; ?>
                </option>
                <?php
            }
            ?>
        </select>
    </form>

    <br><br>
    <a href="menu.html" class="action-btn"
        style="text-decoration:none; background-color: white; color: black; border: 1px solid black;">Retour</a>

    <?php mysqli_close($conn); ?>
</body>

</html>
<?php
session_start();
include("Connexion.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = mysqli_real_escape_string($conn, $_POST["login"]);
    $mdp = mysqli_real_escape_string($conn, $_POST["mdp"]);
    $role = mysqli_real_escape_string($conn, $_POST["role"]);

    // Vérifier si le login existe déjà
    $check = mysqli_query($conn, "SELECT id FROM users WHERE login='$login'");
    if (mysqli_num_rows($check) > 0) {
        $message = "Cet identifiant est déjà utilisé.";
    } else {
        $sql = "INSERT INTO users (login, mdp, role) VALUES ('$login', '$mdp', '$role')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Compte créé avec succès !'); window.location='login.php';</script>";
            exit();
        } else {
            $message = "Erreur : " . mysqli_error($conn);
        }
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription — Centre de Formation</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .register-box {
            max-width: 400px;
            margin: 100px auto;
            padding: 2rem;
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius);
            backdrop-filter: blur(16px);
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-glass);
            color: white;
            border-radius: 8px;
        }

        select option {
            background: var(--bg-secondary);
            color: white;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--accent-indigo), var(--accent-violet));
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }

        .error {
            color: var(--accent-rose);
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="register-box">
        <h2>Créer un compte</h2>
        <?php if ($message)
            echo "<p class='error'>$message</p>"; ?>
        <form method="POST">
            <input type="text" name="login" placeholder="Identifiant" required>
            <input type="password" name="mdp" placeholder="Mot de passe" required>
            <select name="role" required>
                <option value="" disabled selected>Choisir un rôle</option>
                <option value="admin">Administrateur</option>
                <option value="enseignant">Enseignant</option>
                <option value="etudiant">Étudiant</option>
            </select>
            <button type="submit">S'inscrire</button>
        </form>
        <p style="margin-top:20px; text-align:center;">
            Déjà un compte ? <a href="login.php" style="color:var(--accent-cyan);">Se connecter</a>
        </p>
    </div>
</body>

</html>
<?php
session_start();

// Gestion de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

include("Connexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = mysqli_real_escape_string($conn, $_POST["login"]);
    $mdp = mysqli_real_escape_string($conn, $_POST["mdp"]);

    $sql = "SELECT * FROM users WHERE login='$login' AND mdp='$mdp'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $user['login'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];

        // Redirection selon le rôle
        switch ($user['role']) {
            case 'admin':
                header("Location: menu_admin.php");
                break;
            case 'enseignant':
                header("Location: menu_enseignant.php");
                break;
            case 'etudiant':
                header("Location: menu_etudiant.php");
                break;
            default:
                header("Location: enregistrer.html");
        }
    } else {
        echo "<script>alert('Login ou mot de passe incorrect'); window.location='login.php';</script>";
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion — Centre de Formation</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-box {
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

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--accent-indigo), var(--accent-violet));
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h2>Se Connecter</h2>
        <form method="POST">
            <input type="text" name="login" placeholder="Identifiant" required>
            <input type="password" name="mdp" placeholder="Mot de passe" required>
            <button type="submit">Entrer</button>
        </form>
        <p style="margin-top:20px; text-align:center;">
            Pas de compte ? <a href="enregistrer.php" style="color:var(--accent-cyan);">S'inscrire</a>
        </p>
    </div>
</body>

</html>
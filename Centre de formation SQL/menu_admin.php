<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include("Connexion.php");

// Récupérer les infos du profil de l'admin connecté
$admin_id = $_SESSION['user_id'];
$rAdmin = mysqli_query($conn, "SELECT * FROM users WHERE id=$admin_id");
$adminData = mysqli_fetch_assoc($rAdmin);
$adminPhoto = $adminData['photo'] ?? '';
$adminFullName = trim(($adminData['prenom'] ?? '') . ' ' . ($adminData['nom'] ?? ''));
if ($adminFullName == '')
    $adminFullName = $_SESSION['user'];

// ---- Traitement des actions ----
$message = '';
$msgType = '';

// Modifier un utilisateur
if (isset($_POST['edit_user_profile'])) {
    $id = intval($_POST['user_id']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);

    // Gestion de la photo
    $photo_sql = "";
    if (isset($_FILES['photo']) && $_FILES['photo']['name'] != '') {
        if ($_FILES['photo']['error'] == 0) {
            $upload_dir = 'uploads/profiles/';
            if (!is_dir($upload_dir))
                mkdir($upload_dir, 0777, true);

            $file_ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            if (in_array($file_ext, $allowed_ext)) {
                $file_name = "profile_" . $id . "_" . time() . "." . $file_ext;
                $target_file = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                    $photo_sql = ", photo='$target_file'";
                } else {
                    $message = "Erreur : Impossible de déplacer l'image vers le dossier 'uploads/profiles/'. Vérifiez les permissions du dossier.";
                    $msgType = "error";
                }
            } else {
                $message = "Erreur : Extension de fichier non autorisée. Utilisez JPG, PNG ou WEBP.";
                $msgType = "error";
            }
        } else {
            $message = "Erreur de téléchargement : Code " . $_FILES['photo']['error'];
            $msgType = "error";
        }
    }

    if ($msgType != 'error') {
        $sql = "UPDATE users SET nom='$nom', prenom='$prenom' $photo_sql WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            $message = "Profil utilisateur mis à jour !";
            $msgType = "success";
        } else {
            $db_error = mysqli_error($conn);
            if (strpos($db_error, "Unknown column 'photo'") !== false) {
                $message = "Erreur : La base de données n'est pas à jour. Veuillez <a href='migrate_db.php' style='color:inherit; font-weight:bold; text-decoration:underline;'>exécuter le script de migration ici</a>.";
            } else {
                $message = "Erreur SQL : " . $db_error;
            }
            $msgType = "error";
        }
    }
}

// Supprimer un utilisateur
if (isset($_GET['delete_user'])) {
    $id = intval($_GET['delete_user']);
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");
    $message = "Utilisateur supprimé.";
    $msgType = "success";
}

// Supprimer un étudiant
if (isset($_GET['delete_etudiant'])) {
    $id = intval($_GET['delete_etudiant']);
    mysqli_query($conn, "DELETE FROM notes WHERE id_etudiant=$id");
    mysqli_query($conn, "DELETE FROM inscriptions WHERE id_etudiant=$id");
    mysqli_query($conn, "DELETE FROM etudiants WHERE id_etudiant=$id");
    $message = "Étudiant supprimé.";
    $msgType = "success";
}

// Supprimer un formateur
if (isset($_GET['delete_formateur'])) {
    $id = intval($_GET['delete_formateur']);
    mysqli_query($conn, "UPDATE formations SET id_formateur=NULL WHERE id_formateur=$id");
    mysqli_query($conn, "DELETE FROM formateurs WHERE id_formateur=$id");
    $message = "Formateur supprimé.";
    $msgType = "success";
}

// Supprimer une formation
if (isset($_GET['delete_formation'])) {
    $id = intval($_GET['delete_formation']);
    mysqli_query($conn, "DELETE FROM inscriptions WHERE id_formation=$id");
    mysqli_query($conn, "DELETE FROM modules WHERE id_formation=$id");
    mysqli_query($conn, "DELETE FROM formations WHERE id_formation=$id");
    $message = "Formation supprimée.";
    $msgType = "success";
}

// Ajouter un étudiant
if (isset($_POST['add_etudiant'])) {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $date = !empty($_POST['date_naissance']) ? "'" . mysqli_real_escape_string($conn, $_POST['date_naissance']) . "'" : "NULL";
    $email = !empty($_POST['email']) ? "'" . mysqli_real_escape_string($conn, $_POST['email']) . "'" : "NULL";
    $tel = !empty($_POST['telephone']) ? "'" . mysqli_real_escape_string($conn, $_POST['telephone']) . "'" : "NULL";

    $sql = "INSERT INTO etudiants (nom, prenom, date_naissance, email, telephone) VALUES ('$nom','$prenom',$date,$email,$tel)";
    if (mysqli_query($conn, $sql)) {
        $message = "Étudiant ajouté avec succès !";
        $msgType = "success";
    } else {
        $message = "Erreur : " . mysqli_error($conn) . " [SQL: $sql]";
        $msgType = "error";
    }
}

// Ajouter un formateur
if (isset($_POST['add_formateur'])) {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $specialite = !empty($_POST['specialite']) ? "'" . mysqli_real_escape_string($conn, $_POST['specialite']) . "'" : "NULL";
    $email = !empty($_POST['email']) ? "'" . mysqli_real_escape_string($conn, $_POST['email']) . "'" : "NULL";

    $sql = "INSERT INTO formateurs (nom, prenom, specialite, email) VALUES ('$nom','$prenom',$specialite,$email)";
    if (mysqli_query($conn, $sql)) {
        $message = "Formateur ajouté avec succès !";
        $msgType = "success";
    } else {
        $message = "Erreur : " . mysqli_error($conn) . " [SQL: $sql]";
        $msgType = "error";
    }
}

// Ajouter une formation
if (isset($_POST['add_formation'])) {
    $intitule = mysqli_real_escape_string($conn, $_POST['intitule']);
    $duree = intval($_POST['duree']);
    $niveau = mysqli_real_escape_string($conn, $_POST['niveau']);
    $sql = "INSERT INTO formations (intitule, duree, niveau, id_formateur) VALUES ('$intitule',$duree,'$niveau',$id_formateur)";
    if (mysqli_query($conn, $sql)) {
        $message = "Formation ajoutée avec succès !";
        $msgType = "success";
    } else {
        $message = "Erreur : " . mysqli_error($conn) . " [SQL: $sql]";
        $msgType = "error";
    }
}

// Ajouter une note
if (isset($_POST['add_note'])) {
    $id_etudiant = intval($_POST['id_etudiant']);
    $id_module = intval($_POST['id_module']);
    $sql = "INSERT INTO notes (id_etudiant, id_module, note) VALUES ($id_etudiant, $id_module, $note)";
    if (mysqli_query($conn, $sql)) {
        $message = "Note ajoutée avec succès !";
        $msgType = "success";
    } else {
        $message = "Erreur : " . mysqli_error($conn) . " [SQL: $sql]";
        $msgType = "error";
    }
}

// Statistiques
$nbEtudiants = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM etudiants"))['c'];
$nbFormateurs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM formateurs"))['c'];
$nbFormations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM formations"))['c'];
$nbModules = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM modules"))['c'];
$nbNotes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM notes"))['c'];
$nbUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))['c'];
$avgNote = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ROUND(AVG(note),2) as avg FROM notes"))['avg'] ?? '—';

$section = $_GET['section'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🛡️ Admin — Centre de Formation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* ---- Admin Layout ---- */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-glass);
            padding: 1.5rem 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 50;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid var(--border-glass);
            margin-bottom: 1rem;
        }

        .sidebar-brand h2 {
            font-size: 1.1rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent-indigo), var(--accent-violet));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-brand small {
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        .sidebar-section {
            padding: 0.5rem 1rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            font-weight: 700;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: var(--transition);
            border-left: 3px solid transparent;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: var(--text-primary);
            background: rgba(99, 102, 241, 0.08);
            border-left-color: var(--accent-indigo);
        }

        .sidebar a .nav-icon {
            font-size: 1.1rem;
        }

        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 2rem 2.5rem;
            position: relative;
            z-index: 1;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-glass);
        }

        .top-bar h1 {
            font-size: 1.6rem;
            font-weight: 800;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 999px;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .user-badge .role-tag {
            padding: 2px 10px;
            background: linear-gradient(135deg, var(--accent-indigo), var(--accent-violet));
            border-radius: 999px;
            color: white;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* ---- Dashboard Cards ---- */
        .dash-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.2rem;
            margin-bottom: 2.5rem;
        }

        .dash-card {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius);
            padding: 1.5rem;
            backdrop-filter: blur(16px);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .dash-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-indigo), var(--accent-violet));
            opacity: 0;
            transition: var(--transition);
        }

        .dash-card:hover {
            transform: translateY(-4px);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.12);
        }

        .dash-card:hover::after {
            opacity: 1;
        }

        .dash-card .card-icon {
            font-size: 1.5rem;
            margin-bottom: 0.8rem;
        }

        .dash-card .card-value {
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--accent-indigo), var(--accent-cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dash-card .card-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        /* ---- Panels ---- */
        .panel {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius);
            padding: 1.8rem;
            backdrop-filter: blur(16px);
            margin-bottom: 2rem;
        }

        .panel h2 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ---- Forms ---- */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.78rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 14px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-glass);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--accent-indigo);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .form-group select option {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 28px;
            background: linear-gradient(135deg, var(--accent-indigo), var(--accent-violet));
            border: none;
            border-radius: 10px;
            color: white;
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        /* ---- Alert Message ---- */
        .alert {
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            animation: fadeInDown 0.4s ease-out;
        }

        .alert-success {
            background: rgba(52, 211, 153, 0.12);
            border: 1px solid rgba(52, 211, 153, 0.3);
            color: var(--accent-emerald);
        }

        .alert-error {
            background: rgba(251, 113, 133, 0.12);
            border: 1px solid rgba(251, 113, 133, 0.3);
            color: var(--accent-rose);
        }

        /* ---- Admin Table ---- */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }

        .admin-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
            background: rgba(255, 255, 255, 0.02);
            border-bottom: 1px solid var(--border-glass);
        }

        .admin-table td {
            padding: 12px 16px;
            color: var(--text-secondary);
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .admin-table tr:hover td {
            background: rgba(99, 102, 241, 0.04);
        }

        .btn-delete {
            padding: 5px 14px;
            background: rgba(251, 113, 133, 0.12);
            border: 1px solid rgba(251, 113, 133, 0.3);
            border-radius: 8px;
            color: var(--accent-rose);
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-delete:hover {
            background: rgba(251, 113, 133, 0.25);
            transform: translateY(-1px);
        }

        /* Search Input */
        .search-input {
            padding: 8px 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-glass);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.85rem;
            outline: none;
            width: 250px;
            transition: var(--transition);
        }

        .search-input:focus {
            border-color: var(--accent-indigo);
            background: rgba(255, 255, 255, 0.08);
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .dash-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="admin-layout">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-brand">
                <h2>🛡️ Centre Formation</h2>
                <small>Panneau d'administration</small>
            </div>

            <!-- Profile Section -->
            <div class="sidebar-profile">
                <?php if ($adminPhoto): ?>
                    <img src="<?= $adminPhoto ?>" class="profile-photo" alt="Profile">
                <?php else: ?>
                    <div class="profile-photo"
                        style="display:flex; align-items:center; justify-content:center; background:var(--bg-glass); font-size:1.5rem;">
                        👤</div>
                <?php endif; ?>
                <div class="user-name"><?= htmlspecialchars($adminFullName ?? '') ?></div>
                <div class="user-role">Administrateur</div>
            </div>

            <div class="sidebar-section">Navigation</div>
            <a href="?section=dashboard" class="<?= $section == 'dashboard' ? 'active' : '' ?>">
                <span class="nav-icon">📊</span> Tableau de bord
            </a>

            <div class="sidebar-section">Gestion</div>
            <a href="?section=etudiants" class="<?= $section == 'etudiants' ? 'active' : '' ?>">
                <span class="nav-icon">👥</span> Étudiants
            </a>
            <a href="?section=formateurs" class="<?= $section == 'formateurs' ? 'active' : '' ?>">
                <span class="nav-icon">🎓</span> Formateurs
            </a>
            <a href="?section=formations" class="<?= $section == 'formations' ? 'active' : '' ?>">
                <span class="nav-icon">📚</span> Formations
            </a>
            <a href="?section=modules" class="<?= $section == 'modules' ? 'active' : '' ?>">
                <span class="nav-icon">📦</span> Modules
            </a>
            <a href="?section=notes" class="<?= $section == 'notes' ? 'active' : '' ?>">
                <span class="nav-icon">📝</span> Notes
            </a>
            <a href="?section=salles" class="<?= $section == 'salles' ? 'active' : '' ?>">
                <span class="nav-icon">🏫</span> Salles
            </a>

            <div class="sidebar-section">Administration</div>
            <a href="?section=users" class="<?= $section == 'users' ? 'active' : '' ?>">
                <span class="nav-icon">🔑</span> Utilisateurs
            </a>
            <a href="?section=add_etudiant" class="<?= $section == 'add_etudiant' ? 'active' : '' ?>">
                <span class="nav-icon">➕</span> Ajouter étudiant
            </a>
            <a href="?section=add_formateur" class="<?= $section == 'add_formateur' ? 'active' : '' ?>">
                <span class="nav-icon">➕</span> Ajouter formateur
            </a>
            <a href="?section=add_formation" class="<?= $section == 'add_formation' ? 'active' : '' ?>">
                <span class="nav-icon">➕</span> Ajouter formation
            </a>
            <a href="?section=add_note" class="<?= $section == 'add_note' ? 'active' : '' ?>">
                <span class="nav-icon">➕</span> Ajouter note
            </a>

            <div class="sidebar-section">Vues</div>
            <a href="bdd.php">
                <span class="nav-icon">🗄️</span> Voir la BDD
            </a>

            <div class="sidebar-section" style="margin-top:auto;"></div>
            <a href="login.php?logout=1" style="color:var(--accent-rose);">
                <span class="nav-icon">🚪</span> Déconnexion
            </a>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <h1>
                    <?php
                    $titles = [
                        'dashboard' => '📊 Tableau de bord',
                        'etudiants' => '👥 Étudiants',
                        'formateurs' => '🎓 Formateurs',
                        'formations' => '📚 Formations',
                        'modules' => '📦 Modules',
                        'notes' => '📝 Notes',
                        'salles' => '🏫 Salles',
                        'users' => '🔑 Utilisateurs',
                        'add_etudiant' => '➕ Ajouter un étudiant',
                        'add_formateur' => '➕ Ajouter un formateur',
                        'add_formation' => '➕ Ajouter une formation',
                        'add_note' => '➕ Ajouter une note',
                    ];
                    echo $titles[$section] ?? 'Admin';
                    ?>
                </h1>
                <div class="user-badge" onclick="window.location='?section=edit_user&id=<?= $admin_id ?>'">
                    <?php if ($adminPhoto): ?>
                        <img src="<?= $adminPhoto ?>" class="profile-photo" alt="Avatar">
                    <?php else: ?>
                        <span class="nav-icon">👤</span>
                    <?php endif; ?>
                    <span class="role-tag">Admin</span>
                    <?= htmlspecialchars($adminFullName ?? '') ?>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?= $msgType ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <?php if ($section === 'dashboard'): ?>
                <!-- ============ DASHBOARD ============ -->
                <div class="dash-grid">
                    <div class="dash-card">
                        <div class="card-icon">👥</div>
                        <div class="card-value">
                            <?= $nbEtudiants ?>
                        </div>
                        <div class="card-label">Étudiants</div>
                    </div>
                    <div class="dash-card">
                        <div class="card-icon">🎓</div>
                        <div class="card-value">
                            <?= $nbFormateurs ?>
                        </div>
                        <div class="card-label">Formateurs</div>
                    </div>
                    <div class="dash-card">
                        <div class="card-icon">📚</div>
                        <div class="card-value">
                            <?= $nbFormations ?>
                        </div>
                        <div class="card-label">Formations</div>
                    </div>
                    <div class="dash-card">
                        <div class="card-icon">📦</div>
                        <div class="card-value">
                            <?= $nbModules ?>
                        </div>
                        <div class="card-label">Modules</div>
                    </div>
                    <div class="dash-card">
                        <div class="card-icon">📝</div>
                        <div class="card-value">
                            <?= $nbNotes ?>
                        </div>
                        <div class="card-label">Notes</div>
                    </div>
                    <div class="dash-card">
                        <div class="card-icon">🔑</div>
                        <div class="card-value">
                            <?= $nbUsers ?>
                        </div>
                        <div class="card-label">Utilisateurs</div>
                    </div>
                    <div class="dash-card">
                        <div class="card-icon">⭐</div>
                        <div class="card-value">
                            <?= $avgNote ?>
                        </div>
                        <div class="card-label">Moyenne générale</div>
                    </div>
                </div>

                <!-- Derniers étudiants inscrits -->
                <div class="panel">
                    <h2>📋 Derniers étudiants inscrits</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $r = mysqli_query($conn, "SELECT e.*, u.photo FROM etudiants e LEFT JOIN users u ON u.login = e.email ORDER BY id_etudiant DESC LIMIT 5");
                            while ($row = mysqli_fetch_assoc($r)):
                                ?>
                                <tr>
                                    <td>
                                        <?php if ($row['photo']): ?>
                                            <img src="<?= $row['photo'] ?>" class="profile-photo" alt="Avatar">
                                        <?php else: ?>
                                            <span class="nav-icon">👤</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="id-cell">#
                                        <?= $row['id_etudiant'] ?>
                                    </td>
                                    <td class="name-cell">
                                        <?= $row['nom'] ?>
                                    </td>
                                    <td>
                                        <?= $row['prenom'] ?>
                                    </td>
                                    <td>
                                        <?= $row['email'] ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'etudiants'): ?>
                <!-- ============ LISTE ÉTUDIANTS ============ -->
                <div class="panel">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                        <h2>👥 Liste des étudiants</h2>
                        <input type="text" class="search-input" placeholder="🔍 Rechercher un étudiant..."
                            onkeyup="filterTable(this, 'table-students')">
                    </div>
                    <table class="admin-table" id="table-students">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Naissance</th>
                                <th>Email</th>
                                <th>Tél</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $r = mysqli_query($conn, "SELECT e.*, u.photo FROM etudiants e LEFT JOIN users u ON u.login = e.email ORDER BY id_etudiant");
                            while ($row = mysqli_fetch_assoc($r)):
                                ?>
                                <tr>
                                    <td>
                                        <?php if ($row['photo']): ?>
                                            <img src="<?= $row['photo'] ?>" class="profile-photo" alt="Avatar">
                                        <?php else: ?>
                                            <span class="nav-icon">👤</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="id-cell">#
                                        <?= $row['id_etudiant'] ?>
                                    </td>
                                    <td class="name-cell">
                                        <?= $row['nom'] ?>
                                    </td>
                                    <td>
                                        <?= $row['prenom'] ?>
                                    </td>
                                    <td>
                                        <?= $row['date_naissance'] ?>
                                    </td>
                                    <td>
                                        <?= $row['email'] ?>
                                    </td>
                                    <td>
                                        <?= $row['telephone'] ?>
                                    </td>
                                    <td><a href="?section=etudiants&delete_etudiant=<?= $row['id_etudiant'] ?>"
                                            class="btn-delete" onclick="return confirm('Supprimer cet étudiant ?')">🗑️
                                            Suppr.</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'formateurs'): ?>
                <!-- ============ LISTE FORMATEURS ============ -->
                <div class="panel">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                        <h2>🎓 Liste des formateurs</h2>
                        <input type="text" class="search-input" placeholder="🔍 Rechercher un formateur..."
                            onkeyup="filterTable(this, 'table-trainers')">
                    </div>
                    <table class="admin-table" id="table-trainers">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Spécialité</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $r = mysqli_query($conn, "SELECT f.*, u.photo FROM formateurs f LEFT JOIN users u ON u.login = f.email ORDER BY id_formateur");
                            while ($row = mysqli_fetch_assoc($r)):
                                ?>
                                <tr>
                                    <td>
                                        <?php if ($row['photo']): ?>
                                            <img src="<?= $row['photo'] ?>" class="profile-photo" alt="Avatar">
                                        <?php else: ?>
                                            <span class="nav-icon">👤</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="id-cell">#
                                        <?= $row['id_formateur'] ?>
                                    </td>
                                    <td class="name-cell">
                                        <?= $row['nom'] ?>
                                    </td>
                                    <td>
                                        <?= $row['prenom'] ?>
                                    </td>
                                    <td><span class="badge badge-violet">
                                            <?= $row['specialite'] ?>
                                        </span></td>
                                    <td>
                                        <?= $row['email'] ?>
                                    </td>
                                    <td><a href="?section=formateurs&delete_formateur=<?= $row['id_formateur'] ?>"
                                            class="btn-delete" onclick="return confirm('Supprimer ce formateur ?')">🗑️
                                            Suppr.</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'formations'): ?>
                <!-- ============ LISTE FORMATIONS ============ -->
                <div class="panel">
                    <h2>📚 Liste des formations</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Intitulé</th>
                                <th>Durée (h)</th>
                                <th>Niveau</th>
                                <th>Formateur</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $r = mysqli_query($conn, "SELECT f.*, fo.nom as f_nom, fo.prenom as f_prenom, u.photo as f_photo FROM formations f LEFT JOIN formateurs fo ON f.id_formateur=fo.id_formateur LEFT JOIN users u ON u.login = fo.email ORDER BY f.id_formation");
                            while ($row = mysqli_fetch_assoc($r)):
                                ?>
                                <tr>
                                    <td class="id-cell">#
                                        <?= $row['id_formation'] ?>
                                    </td>
                                    <td class="name-cell">
                                        <?= $row['intitule'] ?>
                                    </td>
                                    <td>
                                        <?= $row['duree'] ?>h
                                    </td>
                                    <td><span class="badge badge-cyan">
                                            <?= $row['niveau'] ?>
                                        </span></td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <?php if ($row['f_photo'] ?? ''): ?>
                                                <img src="<?= $row['f_photo'] ?>" class="profile-photo"
                                                    style="width:25px; height:25px;" alt="Avatar">
                                            <?php else: ?>
                                                <span class="nav-icon" style="font-size:1rem;">👤</span>
                                            <?php endif; ?>
                                            <div>
                                                <?= htmlspecialchars($row['f_prenom'] ?? '') ?>
                                                <?= htmlspecialchars($row['f_nom'] ?? '') ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><a href="?section=formations&delete_formation=<?= $row['id_formation'] ?>"
                                            class="btn-delete" onclick="return confirm('Supprimer cette formation ?')">🗑️
                                            Suppr.</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'modules'): ?>
                <!-- ============ LISTE MODULES ============ -->
                <div class="panel">
                    <h2>📦 Modules</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Module</th>
                                <th>Coefficient</th>
                                <th>Formation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $r = mysqli_query($conn, "SELECT m.*, f.intitule FROM modules m LEFT JOIN formations f ON m.id_formation=f.id_formation ORDER BY m.id_module");
                            while ($row = mysqli_fetch_assoc($r)):
                                ?>
                                <tr>
                                    <td class="id-cell">#
                                        <?= $row['id_module'] ?>
                                    </td>
                                    <td class="name-cell">
                                        <?= $row['nom_module'] ?>
                                    </td>
                                    <td><span class="badge badge-amber">Coeff.
                                            <?= $row['coefficient'] ?>
                                        </span></td>
                                    <td>
                                        <?= $row['intitule'] ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'notes'): ?>
                <!-- ============ LISTE NOTES ============ -->
                <div class="panel">
                    <h2>📝 Toutes les notes</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Module</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $r = mysqli_query($conn, "SELECT n.note, e.nom, e.prenom, u.photo, m.nom_module FROM notes n JOIN etudiants e ON n.id_etudiant=e.id_etudiant JOIN modules m ON n.id_module=m.id_module LEFT JOIN users u ON u.login = e.email ORDER BY n.id_note DESC");
                            while ($row = mysqli_fetch_assoc($r)):
                                $color = $row['note'] >= 15 ? 'emerald' : ($row['note'] >= 10 ? 'amber' : 'rose');
                                ?>
                                <tr>
                                    <td class="name-cell">
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <?php if ($row['photo'] ?? ''): ?>
                                                <img src="<?= $row['photo'] ?>" class="profile-photo"
                                                    style="width:25px; height:25px;" alt="Avatar">
                                            <?php else: ?>
                                                <span class="nav-icon" style="font-size:1rem;">👤</span>
                                            <?php endif; ?>
                                            <div>
                                                <?= htmlspecialchars($row['nom'] ?? '') ?>
                                                <?= htmlspecialchars($row['prenom'] ?? '') ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?= $row['nom_module'] ?>
                                    </td>
                                    <td><span class="badge badge-<?= $color ?>">
                                            <?= $row['note'] ?>/20
                                        </span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'salles'): ?>
                <!-- ============ SALLES ============ -->
                <div class="panel">
                    <h2>🏫 Salles disponibles</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Capacité</th>
                                <th>Équipement</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $r = mysqli_query($conn, "SELECT * FROM salles ORDER BY id_salle");
                            while ($row = mysqli_fetch_assoc($r)):
                                ?>
                                <tr>
                                    <td class="id-cell">#
                                        <?= $row['id_salle'] ?>
                                    </td>
                                    <td class="name-cell">
                                        <?= $row['nom_salle'] ?>
                                    </td>
                                    <td>
                                        <?= $row['capacite'] ?> places
                                    </td>
                                    <td>
                                        <?= $row['equipement'] ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'users'): ?>
                <!-- ============ UTILISATEURS ============ -->
                <div class="panel">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                        <h2>🔑 Gestion des utilisateurs</h2>
                        <input type="text" class="search-input" placeholder="🔍 Rechercher un compte..."
                            onkeyup="filterTable(this, 'table-users')">
                    </div>
                    <table class="admin-table" id="table-users">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>ID</th>
                                <th>Email / Login</th>
                                <th>Prénom</th>
                                <th>Nom</th>
                                <th>Rôle</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $r = mysqli_query($conn, "SELECT * FROM users ORDER BY id");
                            while ($row = mysqli_fetch_assoc($r)):
                                $roleColor = ['admin' => 'indigo', 'enseignant' => 'violet', 'etudiant' => 'cyan'][$row['role']] ?? 'amber';
                                ?>
                                <tr>
                                    <td>
                                        <?php if ($row['photo']): ?>
                                            <img src="<?= $row['photo'] ?>" class="profile-photo" alt="Profile">
                                        <?php else: ?>
                                            <span class="nav-icon">👤</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="id-cell">#
                                        <?= $row['id'] ?>
                                    </td>
                                    <td class="name-cell">
                                        <?= htmlspecialchars($row['login'] ?? '') ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['prenom'] ?? '') ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['nom'] ?? '') ?>
                                    </td>
                                    <td><span class="badge badge-<?= $roleColor ?>">
                                            <?= ucfirst($row['role']) ?>
                                        </span></td>
                                    <td>
                                        <div style="display:flex; gap:5px;">
                                            <a href="?section=edit_user&id=<?= $row['id'] ?>" class="btn-submit"
                                                style="padding: 5px 10px; margin:0; font-size:0.75rem;">✏️ Editer</a>
                                            <a href="?section=users&delete_user=<?= $row['id'] ?>" class="btn-delete"
                                                onclick="return confirm('Supprimer cet utilisateur ?')">🗑️ Suppr.</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'edit_user'):
                $id = intval($_GET['id']);
                $userRes = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
                $u = mysqli_fetch_assoc($userRes);
                ?>
                <!-- ============ FORMULAIRE ÉDITION PROFIL ============ -->
                <div class="panel">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                        <h2>✏️ Modifier Profil : <?= htmlspecialchars($u['login'] ?? '') ?></h2>
                        <a href="?section=users" class="back-btn" style="margin:0;">⬅️ Retour</a>
                    </div>

                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">

                        <div class="profile-header">
                            <?php if ($u['photo']): ?>
                                <img src="<?= $u['photo'] ?>" class="profile-photo-lg" id="previewImg">
                            <?php else: ?>
                                <div class="profile-photo-lg"
                                    style="display:flex; align-items:center; justify-content:center; background:var(--bg-glass); font-size:3rem;">
                                    👤</div>
                            <?php endif; ?>

                            <label class="profile-upload-btn">
                                📷 Changer la photo
                                <input type="file" name="photo" class="hidden-input" accept="image/*"
                                    onchange="previewFile(this)">
                            </label>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" name="prenom" value="<?= htmlspecialchars($u['prenom'] ?? '') ?>"
                                    required>
                            </div>
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="nom" value="<?= htmlspecialchars($u['nom'] ?? '') ?>" required>
                            </div>
                        </div>

                        <button type="submit" name="edit_user_profile" class="btn-submit">💾 Enregistrer les
                            modifications</button>
                    </form>
                </div>

                <script>
                    function previewFile(input) {
                        if (input.files && input.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                var preview = document.getElementById('previewImg') || document.querySelector('.profile-photo-lg');
                                if (preview.tagName === 'IMG') {
                                    preview.src = e.target.result;
                                } else {
                                    var img = document.createElement('img');
                                    img.src = e.target.result;
                                    img.className = 'profile-photo-lg';
                                    img.id = 'previewImg';
                                    // Remplacer le div ou l'icône par l'image
                                    preview.parentNode.replaceChild(img, preview);
                                }
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                    }

                    // Drag & Drop
                    const dropZone = document.querySelector('.profile-header');
                    const fileInput = document.querySelector('input[name="photo"]');

                    if (dropZone && fileInput) {
                        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                            dropZone.addEventListener(eventName, e => {
                                e.preventDefault();
                                e.stopPropagation();
                            }, false);
                        });

                        ['dragenter', 'dragover'].forEach(eventName => {
                            dropZone.addEventListener(eventName, () => dropZone.classList.add('drag-over'), false);
                        });

                        ['dragleave', 'drop'].forEach(eventName => {
                            dropZone.addEventListener(eventName, () => dropZone.classList.remove('drag-over'), false);
                        });

                        dropZone.addEventListener('drop', e => {
                            const dt = e.dataTransfer;
                            const files = dt.files;
                            fileInput.files = files;
                            previewFile(fileInput);
                        }
                            , false);

                        // Click to trigger file input
                        dropZone.style.cursor = 'pointer';
                        dropZone.addEventListener('click', (e) => {
                            // Empêcher le clic si on clique directement sur l'input (pour éviter récursion)
                            if (e.target !== fileInput && e.target.tagName !== 'LABEL') {
                                fileInput.click();
                            }
                        });
                    }
                </script>

            <?php elseif ($section === 'add_etudiant'): ?>
                <!-- ============ FORMULAIRE AJOUT ÉTUDIANT ============ -->
                <div class="panel">
                    <h2>➕ Nouvel étudiant</h2>
                    <form method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="nom" required>
                            </div>
                            <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" name="prenom" required>
                            </div>
                            <div class="form-group">
                                <label>Date de naissance</label>
                                <input type="date" name="date_naissance">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email">
                            </div>
                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="text" name="telephone">
                            </div>
                        </div>
                        <button type="submit" name="add_etudiant" class="btn-submit">✅ Enregistrer</button>
                    </form>
                </div>

            <?php elseif ($section === 'add_formateur'): ?>
                <!-- ============ FORMULAIRE AJOUT FORMATEUR ============ -->
                <div class="panel">
                    <h2>➕ Nouveau formateur</h2>
                    <form method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="nom" required>
                            </div>
                            <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" name="prenom" required>
                            </div>
                            <div class="form-group">
                                <label>Spécialité</label>
                                <input type="text" name="specialite">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email">
                            </div>
                        </div>
                        <button type="submit" name="add_formateur" class="btn-submit">✅ Enregistrer</button>
                    </form>
                </div>

            <?php elseif ($section === 'add_formation'): ?>
                <!-- ============ FORMULAIRE AJOUT FORMATION ============ -->
                <div class="panel">
                    <h2>➕ Nouvelle formation</h2>
                    <form method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Intitulé</label>
                                <input type="text" name="intitule" required>
                            </div>
                            <div class="form-group">
                                <label>Durée (heures)</label>
                                <input type="number" name="duree" required>
                            </div>
                            <div class="form-group">
                                <label>Niveau</label>
                                <select name="niveau" required>
                                    <option value="Debutant">Débutant</option>
                                    <option value="BTS">BTS</option>
                                    <option value="BUT">BUT</option>
                                    <option value="Licence">Licence</option>
                                    <option value="Master">Master</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Formateur</label>
                                <select name="id_formateur" required>
                                    <?php
                                    $rf = mysqli_query($conn, "SELECT * FROM formateurs");
                                    while ($f = mysqli_fetch_assoc($rf)):
                                        ?>
                                        <option value="<?= $f['id_formateur'] ?>">
                                            <?= $f['prenom'] ?>
                                            <?= $f['nom'] ?> —
                                            <?= $f['specialite'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="add_formation" class="btn-submit">✅ Enregistrer</button>
                    </form>
                </div>

            <?php elseif ($section === 'add_note'): ?>
                <!-- ============ FORMULAIRE AJOUT NOTE ============ -->
                <div class="panel">
                    <h2>➕ Nouvelle note</h2>
                    <form method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Étudiant</label>
                                <select name="id_etudiant" required>
                                    <?php
                                    $re = mysqli_query($conn, "SELECT * FROM etudiants");
                                    while ($e = mysqli_fetch_assoc($re)):
                                        ?>
                                        <option value="<?= $e['id_etudiant'] ?>">
                                            <?= $e['nom'] ?>
                                            <?= $e['prenom'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Module</label>
                                <select name="id_module" required>
                                    <?php
                                    $rm = mysqli_query($conn, "SELECT * FROM modules");
                                    while ($m = mysqli_fetch_assoc($rm)):
                                        ?>
                                        <option value="<?= $m['id_module'] ?>">
                                            <?= $m['nom_module'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Note (/20)</label>
                                <input type="number" name="note" min="0" max="20" step="0.01" required>
                            </div>
                        </div>
                        <button type="submit" name="add_note" class="btn-submit">✅ Enregistrer</button>
                    </form>
                </div>
            <?php endif; ?>
        </main>
    </div>
    <script>
        function filterTable(input, tableId) {
            let filter = input.value.toLowerCase();
            let table = document.getElementById(tableId);
            let rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        }
    </script>
</body>

</html>
<?php mysqli_close($conn); ?>
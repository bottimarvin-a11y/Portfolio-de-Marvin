<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'enseignant') {
    header("Location: login.php");
    exit();
}
include("Connexion.php");

$message = '';
$msgType = '';

// L'enseignant peut ajouter / modifier des notes
if (isset($_POST['add_note'])) {
    $id_etudiant = intval($_POST['id_etudiant']);
    $id_module = intval($_POST['id_module']);
    $note = floatval($_POST['note']);
    if ($note >= 0 && $note <= 20) {
        if (mysqli_query($conn, "INSERT INTO notes (id_etudiant, id_module, note) VALUES ($id_etudiant, $id_module, $note)")) {
            $message = "Note enregistrée avec succès !";
            $msgType = "success";
        } else {
            $message = "Erreur : " . mysqli_error($conn);
            $msgType = "error";
        }
    } else {
        $message = "La note doit être entre 0 et 20.";
        $msgType = "error";
    }
}

// Supprimer une note (enseignant peut corriger)
if (isset($_GET['delete_note'])) {
    $id = intval($_GET['delete_note']);
    mysqli_query($conn, "DELETE FROM notes WHERE id_note=$id");
    $message = "Note supprimée.";
    $msgType = "success";
}

// Stats pour l'enseignant
$nbEtudiants = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM etudiants"))['c'];
$nbFormations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM formations"))['c'];
$nbModules = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM modules"))['c'];
$nbNotes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM notes"))['c'];
$avgNote = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ROUND(AVG(note),2) as avg FROM notes"))['avg'] ?? '—';

$section = $_GET['section'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎓 Enseignant — Centre de Formation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
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
            background: linear-gradient(135deg, #a78bfa, #c084fc);
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
            background: rgba(167, 139, 250, 0.08);
            border-left-color: #a78bfa;
        }

        .sidebar a .nav-icon {
            font-size: 1.1rem;
        }

        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 2rem 2.5rem;
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
            background: linear-gradient(135deg, #a78bfa, #c084fc);
            border-radius: 999px;
            color: white;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
        }

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
            background: linear-gradient(90deg, #a78bfa, #c084fc);
            opacity: 0;
            transition: var(--transition);
        }

        .dash-card:hover {
            transform: translateY(-4px);
            border-color: rgba(167, 139, 250, 0.3);
            box-shadow: 0 8px 30px rgba(167, 139, 250, 0.12);
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
            background: linear-gradient(135deg, #a78bfa, var(--accent-cyan));
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
            border-color: #a78bfa;
            box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.12);
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
            background: linear-gradient(135deg, #a78bfa, #c084fc);
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
            box-shadow: 0 6px 20px rgba(167, 139, 250, 0.4);
        }

        .alert {
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
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
            background: rgba(167, 139, 250, 0.04);
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
        }

        .perm-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            background: rgba(167, 139, 250, 0.06);
            border: 1px solid rgba(167, 139, 250, 0.15);
            border-radius: 12px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }

        .perm-info .perm-icon {
            font-size: 1.3rem;
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
        <nav class="sidebar">
            <div class="sidebar-brand">
                <h2>🎓 Centre Formation</h2>
                <small>Espace Enseignant</small>
            </div>

            <div class="sidebar-section">Navigation</div>
            <a href="?section=dashboard" class="<?= $section == 'dashboard' ? 'active' : '' ?>">
                <span class="nav-icon">📊</span> Tableau de bord
            </a>

            <div class="sidebar-section">Consulter</div>
            <a href="?section=etudiants" class="<?= $section == 'etudiants' ? 'active' : '' ?>">
                <span class="nav-icon">👥</span> Mes étudiants
            </a>
            <a href="?section=formations" class="<?= $section == 'formations' ? 'active' : '' ?>">
                <span class="nav-icon">📚</span> Formations
            </a>
            <a href="?section=modules" class="<?= $section == 'modules' ? 'active' : '' ?>">
                <span class="nav-icon">📦</span> Modules
            </a>
            <a href="?section=salles" class="<?= $section == 'salles' ? 'active' : '' ?>">
                <span class="nav-icon">🏫</span> Salles
            </a>

            <div class="sidebar-section">Évaluation</div>
            <a href="?section=notes" class="<?= $section == 'notes' ? 'active' : '' ?>">
                <span class="nav-icon">📝</span> Notes
            </a>
            <a href="?section=add_note" class="<?= $section == 'add_note' ? 'active' : '' ?>">
                <span class="nav-icon">➕</span> Ajouter une note
            </a>
            <a href="?section=moyennes" class="<?= $section == 'moyennes' ? 'active' : '' ?>">
                <span class="nav-icon">📈</span> Moyennes
            </a>

            <div class="sidebar-section">Vues</div>
            <a href="enregistrer.html">
                <span class="nav-icon">🗄️</span> Voir la BDD
            </a>

            <div class="sidebar-section" style="margin-top:auto;"></div>
            <a href="login.php?logout=1" style="color:var(--accent-rose);">
                <span class="nav-icon">🚪</span> Déconnexion
            </a>
        </nav>

        <main class="main-content">
            <div class="top-bar">
                <h1>
                    <?php
                    $titles = [
                        'dashboard' => '📊 Tableau de bord',
                        'etudiants' => '👥 Étudiants',
                        'formations' => '📚 Formations',
                        'modules' => '📦 Modules',
                        'notes' => '📝 Notes',
                        'salles' => '🏫 Salles',
                        'add_note' => '➕ Ajouter une note',
                        'moyennes' => '📈 Moyennes par étudiant',
                    ];
                    echo $titles[$section] ?? 'Enseignant';
                    ?>
                </h1>
                <div class="user-badge">
                    <span class="role-tag">Enseignant</span>
                    <?= htmlspecialchars($_SESSION['user']) ?>
                </div>
            </div>

            <div class="perm-info">
                <span class="perm-icon">ℹ️</span>
                <span><strong>Permissions :</strong> Consultation des étudiants, formations, modules et salles · Gestion
                    complète des notes (ajout, suppression) · Calcul des moyennes</span>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?= $msgType ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <?php if ($section === 'dashboard'): ?>
                <div class="dash-grid">
                    <div class="dash-card">
                        <div class="card-icon">👥</div>
                        <div class="card-value">
                            <?= $nbEtudiants ?>
                        </div>
                        <div class="card-label">Étudiants</div>
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
                        <div class="card-label">Notes saisies</div>
                    </div>
                    <div class="dash-card">
                        <div class="card-icon">⭐</div>
                        <div class="card-value">
                            <?= $avgNote ?>
                        </div>
                        <div class="card-label">Moyenne générale</div>
                    </div>
                </div>

                <div class="panel">
                    <h2>📋 Dernières notes saisies</h2>
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
                            $r = mysqli_query($conn, "SELECT n.note, e.nom, e.prenom, m.nom_module FROM notes n JOIN etudiants e ON n.id_etudiant=e.id_etudiant JOIN modules m ON n.id_module=m.id_module ORDER BY n.id_note DESC LIMIT 10");
                            while ($row = mysqli_fetch_assoc($r)):
                                $color = $row['note'] >= 15 ? 'emerald' : ($row['note'] >= 10 ? 'amber' : 'rose');
                                ?>
                                <tr>
                                    <td class="name-cell">
                                        <?= $row['nom'] ?>
                                        <?= $row['prenom'] ?>
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

            <?php elseif ($section === 'etudiants'): ?>
                <div class="panel">
                    <h2>👥 Liste des étudiants <small
                            style="color:var(--text-muted); font-size:0.75rem; margin-left:10px;">— Lecture seule</small>
                    </h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Naissance</th>
                                <th>Email</th>
                                <th>Tél</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $r = mysqli_query($conn, "SELECT * FROM etudiants ORDER BY nom");
                            while ($row = mysqli_fetch_assoc($r)):
                                ?>
                                <tr>
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
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'formations'): ?>
                <div class="panel">
                    <h2>📚 Formations <small style="color:var(--text-muted); font-size:0.75rem; margin-left:10px;">— Lecture
                            seule</small></h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Intitulé</th>
                                <th>Durée</th>
                                <th>Niveau</th>
                                <th>Formateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $r = mysqli_query($conn, "SELECT f.*, fo.nom as f_nom, fo.prenom as f_prenom FROM formations f LEFT JOIN formateurs fo ON f.id_formateur=fo.id_formateur ORDER BY f.id_formation");
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
                                        <?= $row['f_prenom'] ?>
                                        <?= $row['f_nom'] ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'modules'): ?>
                <div class="panel">
                    <h2>📦 Modules <small style="color:var(--text-muted); font-size:0.75rem; margin-left:10px;">— Lecture
                            seule</small></h2>
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

            <?php elseif ($section === 'salles'): ?>
                <div class="panel">
                    <h2>🏫 Salles <small style="color:var(--text-muted); font-size:0.75rem; margin-left:10px;">— Lecture
                            seule</small></h2>
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

            <?php elseif ($section === 'notes'): ?>
                <div class="panel">
                    <h2>📝 Toutes les notes</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Module</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $r = mysqli_query($conn, "SELECT n.id_note, n.note, e.nom, e.prenom, m.nom_module FROM notes n JOIN etudiants e ON n.id_etudiant=e.id_etudiant JOIN modules m ON n.id_module=m.id_module ORDER BY n.id_note DESC");
                            while ($row = mysqli_fetch_assoc($r)):
                                $color = $row['note'] >= 15 ? 'emerald' : ($row['note'] >= 10 ? 'amber' : 'rose');
                                ?>
                                <tr>
                                    <td class="name-cell">
                                        <?= $row['nom'] ?>
                                        <?= $row['prenom'] ?>
                                    </td>
                                    <td>
                                        <?= $row['nom_module'] ?>
                                    </td>
                                    <td><span class="badge badge-<?= $color ?>">
                                            <?= $row['note'] ?>/20
                                        </span></td>
                                    <td><a href="?section=notes&delete_note=<?= $row['id_note'] ?>" class="btn-delete"
                                            onclick="return confirm('Supprimer cette note ?')">🗑️ Suppr.</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($section === 'add_note'): ?>
                <div class="panel">
                    <h2>➕ Saisir une note</h2>
                    <form method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Étudiant</label>
                                <select name="id_etudiant" required>
                                    <?php
                                    $re = mysqli_query($conn, "SELECT * FROM etudiants ORDER BY nom");
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
                        <button type="submit" name="add_note" class="btn-submit">✅ Enregistrer la note</button>
                    </form>
                </div>

            <?php elseif ($section === 'moyennes'): ?>
                <div class="panel">
                    <h2>📈 Moyennes par étudiant</h2>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Nb. Notes</th>
                                <th>Moyenne</th>
                                <th>Min</th>
                                <th>Max</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $r = mysqli_query($conn, "SELECT e.nom, e.prenom, COUNT(n.note) as nb, ROUND(AVG(n.note),2) as moy, MIN(n.note) as mini, MAX(n.note) as maxi FROM etudiants e LEFT JOIN notes n ON e.id_etudiant=n.id_etudiant GROUP BY e.id_etudiant ORDER BY moy DESC");
                            while ($row = mysqli_fetch_assoc($r)):
                                $moy = $row['moy'] ?? 0;
                                $color = $moy >= 15 ? 'emerald' : ($moy >= 10 ? 'amber' : 'rose');
                                $statut = $moy >= 10 ? '✅ Admis' : '❌ Ajourné';
                                if ($row['nb'] == 0) {
                                    $statut = '⏳ Aucune note';
                                    $color = 'cyan';
                                }
                                ?>
                                <tr>
                                    <td class="name-cell">
                                        <?= $row['nom'] ?>
                                        <?= $row['prenom'] ?>
                                    </td>
                                    <td>
                                        <?= $row['nb'] ?>
                                    </td>
                                    <td><span class="badge badge-<?= $color ?>">
                                            <?= $moy ?: '—' ?>
                                        </span></td>
                                    <td>
                                        <?= $row['mini'] ?? '—' ?>
                                    </td>
                                    <td>
                                        <?= $row['maxi'] ?? '—' ?>
                                    </td>
                                    <td>
                                        <?= $statut ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>
<?php mysqli_close($conn); ?>
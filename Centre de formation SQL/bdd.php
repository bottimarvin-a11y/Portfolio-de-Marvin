<?php
session_start();
include("Connexion.php");

// Récupérer les statistiques
$resEtudiants = mysqli_query($conn, "SELECT COUNT(*) as nb FROM etudiants");
$nbEtudiants = mysqli_fetch_assoc($resEtudiants)['nb'];

$resFormateurs = mysqli_query($conn, "SELECT COUNT(*) as nb FROM formateurs");
$nbFormateurs = mysqli_fetch_assoc($resFormateurs)['nb'];

$resFormations = mysqli_query($conn, "SELECT COUNT(*) as nb FROM formations");
$nbFormations = mysqli_fetch_assoc($resFormations)['nb'];

$resModules = mysqli_query($conn, "SELECT COUNT(*) as nb FROM modules");
$nbModules = mysqli_fetch_assoc($resModules)['nb'];

$resNotes = mysqli_query($conn, "SELECT COUNT(*) as nb FROM notes");
$nbNotes = mysqli_fetch_assoc($resNotes)['nb'];

$resSalles = mysqli_query($conn, "SELECT COUNT(*) as nb FROM salles");
$nbSalles = mysqli_fetch_assoc($resSalles)['nb'];

// Logic for the back button
$back_url = "index.php";
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin')
        $back_url = "menu_admin.php";
    elseif ($_SESSION['role'] === 'enseignant')
        $back_url = "menu_enseignant.php";
    elseif ($_SESSION['role'] === 'etudiant')
        $back_url = "menu_etudiant.php";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🗄️ Centre de Formation — Base de Données</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-photo-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--border-glass);
        }

        .search-container {
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: center;
        }

        .search-input {
            width: 100%;
            max-width: 500px;
            padding: 12px 20px;
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: 12px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.95rem;
            outline: none;
            transition: var(--transition);
        }

        .search-input:focus {
            border-color: var(--accent-indigo);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
    </style>
</head>

<body style="min-height: 100vh; background: var(--bg-main); color: var(--text-primary);">

    <!-- Floating Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="container">

        <!-- Back Button -->
        <a href="<?= $back_url ?>" class="back-btn">← Retour</a>

        <!-- Header -->
        <header class="header">
            <div class="header-badge">
                <span class="dot"></span> Données dynamiques · MySQL 8.0
            </div>
            <h1>Centre de Formation</h1>
            <p>Visualisation complète des données du système de gestion : étudiants, formateurs, formations et notes.
            </p>
        </header>

        <!-- Stats Bar -->
        <div class="stats-bar">
            <div class="stat-chip">
                <span class="icon">👥</span>
                <div>
                    <div class="value">
                        <?= $nbEtudiants ?>
                    </div>
                    <div class="label">Étudiants</div>
                </div>
            </div>
            <div class="stat-chip">
                <span class="icon">🎓</span>
                <div>
                    <div class="value">
                        <?= $nbFormateurs ?>
                    </div>
                    <div class="label">Formateurs</div>
                </div>
            </div>
            <div class="stat-chip">
                <span class="icon">📚</span>
                <div>
                    <div class="value">
                        <?= $nbFormations ?>
                    </div>
                    <div class="label">Formations</div>
                </div>
            </div>
            <div class="stat-chip">
                <span class="icon">📦</span>
                <div>
                    <div class="value">
                        <?= $nbModules ?>
                    </div>
                    <div class="label">Modules</div>
                </div>
            </div>
            <div class="stat-chip">
                <span class="icon">📝</span>
                <div>
                    <div class="value">
                        <?= $nbNotes ?>
                    </div>
                    <div class="label">Notes</div>
                </div>
            </div>
            <div class="stat-chip">
                <span class="icon">🏫</span>
                <div>
                    <div class="value">
                        <?= $nbSalles ?>
                    </div>
                    <div class="label">Salles</div>
                </div>
            </div>
        </div>

        <!-- Global Search -->
        <div class="search-container">
            <input type="text" class="search-input" placeholder="🔍 Rechercher dans toute la base de données..."
                onkeyup="globalSearch(this)">
        </div>

        <!-- Navigation Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="showSection('etudiants')">👥 Étudiants</button>
            <button class="tab-btn" onclick="showSection('formateurs')">🎓 Formateurs</button>
            <button class="tab-btn" onclick="showSection('utilisateurs')">🔑 Utilisateurs</button>
            <button class="tab-btn" onclick="showSection('formations')">📚 Formations</button>
            <button class="tab-btn" onclick="showSection('modules')">📦 Modules</button>
            <button class="tab-btn" onclick="showSection('notes')">📝 Notes</button>
            <button class="tab-btn" onclick="showSection('salles')">🏫 Salles</button>
        </div>

        <!-- ========== ÉTUDIANTS ========== -->
        <div id="etudiants" class="table-section active">
            <div class="section-header">
                <div class="section-icon students">👥</div>
                <div>
                    <div class="section-title">Étudiants</div>
                    <div class="section-subtitle">Liste complète récupérée en temps réel</div>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
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
                                        <img src="<?= $row['photo'] ?>" class="profile-photo-small" alt="Avatar">
                                    <?php else: ?>
                                        <span class="nav-icon">👤</span>
                                    <?php endif; ?>
                                </td>
                                <td class="id-cell">#
                                    <?= $row['id_etudiant'] ?>
                                </td>
                                <td class="name-cell">
                                    <?= htmlspecialchars($row['nom'] ?? '') ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['prenom'] ?? '') ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['email'] ?? '') ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['telephone'] ?? '') ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ========== FORMATEURS ========== -->
        <div id="formateurs" class="table-section">
            <div class="section-header">
                <div class="section-icon trainers">🎓</div>
                <div>
                    <div class="section-title">Formateurs</div>
                    <div class="section-subtitle">Liste complète récupérée en temps réel</div>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Spécialité</th>
                            <th>Email</th>
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
                                        <img src="<?= $row['photo'] ?>" class="profile-photo-small" alt="Avatar">
                                    <?php else: ?>
                                        <span class="nav-icon">👤</span>
                                    <?php endif; ?>
                                </td>
                                <td class="id-cell">#
                                    <?= $row['id_formateur'] ?>
                                </td>
                                <td class="name-cell">
                                    <?= htmlspecialchars($row['nom'] ?? '') ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['prenom'] ?? '') ?>
                                </td>
                                <td><span class="badge badge-indigo">
                                        <?= htmlspecialchars($row['specialite'] ?? '') ?>
                                    </span></td>
                                <td>
                                    <?= htmlspecialchars($row['email'] ?? '') ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ========== UTILISATEURS ========== -->
        <div id="utilisateurs" class="table-section">
            <div class="section-header">
                <div class="section-icon users">🔑</div>
                <div>
                    <div class="section-title">Utilisateurs (Comptes)</div>
                    <div class="section-subtitle">Liste des comptes d'accès au système</div>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>ID</th>
                            <th>Login / Email</th>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Rôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $r = mysqli_query($conn, "SELECT * FROM users ORDER BY id");
                        while ($row = mysqli_fetch_assoc($r)):
                            ?>
                            <tr>
                                <td>
                                    <?php if ($row['photo']): ?>
                                        <img src="<?= $row['photo'] ?>" class="profile-photo-small" alt="Avatar">
                                    <?php else: ?>
                                        <span class="nav-icon">👤</span>
                                    <?php endif; ?>
                                <td>
                                    <?php if ($row['photo']): ?>
                                        <img src="<?= $row['photo'] ?>" class="profile-photo-small" alt="Avatar">
                                    <?php else: ?>
                                        <div class="profile-photo-small"
                                            style="display:flex; align-items:center; justify-content:center; background:var(--bg-glass); font-size:1.2rem;">
                                            👤</div>
                                    <?php endif; ?>
                                </td>
                                <td class="id-cell">#<?= $row['id'] ?></td>
                                <td class="name-cell"><?= htmlspecialchars($row['login'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['prenom'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['nom'] ?? '') ?></td>
                                <td><span class="badge badge-indigo"><?= ucfirst($row['role']) ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ========== FORMATIONS ========== -->
        <div id="formations" class="table-section">
            <div class="section-header">
                <div class="section-icon formations">📚</div>
                <div>
                    <div class="section-title">Formations</div>
                    <div class="section-subtitle">Liste complète récupérée en temps réel</div>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
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
                        $r = mysqli_query($conn, "SELECT f.*, fo.nom as f_nom, fo.prenom as f_prenom FROM formations f LEFT JOIN formateurs fo ON f.id_formateur=fo.id_formateur ORDER BY id_formation");
                        while ($row = mysqli_fetch_assoc($r)):
                            ?>
                            <tr>
                                <td class="id-cell">#
                                    <?= $row['id_formation'] ?>
                                </td>
                                <td class="name-cell">
                                    <?= htmlspecialchars($row['intitule'] ?? '') ?>
                                </td>
                                <td>
                                    <?= $row['duree'] ?>h
                                </td>
                                <td><span class="badge badge-indigo">
                                        <?= htmlspecialchars($row['niveau'] ?? '') ?>
                                    </span></td>
                                <td>
                                    <?= htmlspecialchars(($row['f_prenom'] ?? '') . ' ' . ($row['f_nom'] ?? '')) ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ========== MODULES ========== -->
        <div id="modules" class="table-section">
            <div class="section-header">
                <div class="section-icon modules">📦</div>
                <div>
                    <div class="section-title">Modules</div>
                    <div class="section-subtitle">Liste complète récupérée en temps réel</div>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom du Module</th>
                            <th>Coefficient</th>
                            <th>Formation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $r = mysqli_query($conn, "SELECT m.*, f.intitule FROM modules m LEFT JOIN formations f ON m.id_formation=f.id_formation ORDER BY id_module");
                        while ($row = mysqli_fetch_assoc($r)):
                            ?>
                            <tr>
                                <td class="id-cell">#
                                    <?= $row['id_module'] ?>
                                </td>
                                <td class="name-cell">
                                    <?= htmlspecialchars($row['nom_module'] ?? '') ?>
                                </td>
                                <td><span class="badge badge-amber">Coef.
                                        <?= $row['coefficient'] ?>
                                    </span></td>
                                <td>
                                    <?= htmlspecialchars($row['intitule'] ?? '') ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ========== NOTES ========== -->
        <div id="notes" class="table-section">
            <div class="section-header">
                <div class="section-icon notes">📝</div>
                <div>
                    <div class="section-title">Notes</div>
                    <div class="section-subtitle">Liste complète récupérée en temps réel</div>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Étudiant</th>
                            <th>Module</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $r = mysqli_query($conn, "SELECT n.*, e.nom, e.prenom, m.nom_module FROM notes n JOIN etudiants e ON n.id_etudiant=e.id_etudiant JOIN modules m ON n.id_module=m.id_module ORDER BY id_note DESC");
                        while ($row = mysqli_fetch_assoc($r)):
                            $note = floatval($row['note']);
                            $perc = ($note / 20) * 100;
                            $cls = $note >= 15 ? 'high' : ($note >= 10 ? 'mid' : 'low');
                            ?>
                            <tr>
                                <td class="id-cell">#
                                    <?= $row['id_note'] ?>
                                </td>
                                <td class="name-cell">
                                    <?= htmlspecialchars(($row['prenom'] ?? '') . ' ' . ($row['nom'] ?? '')) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['nom_module'] ?? '') ?>
                                </td>
                                <td>
                                    <div class="note-bar"><span class="note-value note-<?= $cls ?>">
                                            <?= number_format($note, 2) ?>
                                        </span>
                                        <div class="bar-track">
                                            <div class="bar-fill" style="width:<?= $perc ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ========== SALLES ========== -->
        <div id="salles" class="table-section">
            <div class="section-header">
                <div class="section-icon salles">🏫</div>
                <div>
                    <div class="section-title">Salles</div>
                    <div class="section-subtitle">Liste complète récupérée en temps réel</div>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
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
                                    <?= htmlspecialchars($row['nom_salle'] ?? '') ?>
                                </td>
                                <td>
                                    <?= $row['capacite'] ?> places
                                </td>
                                <td><span class="badge badge-indigo">
                                        <?= htmlspecialchars($row['equipement'] ?? '') ?>
                                    </span></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div><!-- /.container -->

    <script>
        function showSection(id) {
            document.querySelectorAll('.table-section').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(id).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        function globalSearch(input) {
            let filter = input.value.toLowerCase();
            let tables = document.querySelectorAll('table');

            tables.forEach(table => {
                let rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    let text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        }
    </script>

</body>

</html>